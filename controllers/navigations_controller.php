<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class NavigationsController extends AppController {

	var $name = 'Navigations';
        var $components = array('Security');
        var $helpers = array();

        function beforeFilter() {
            parent::beforeFilter();

            // ensure our ajax methods are POSTed
            // $this->Security->requirePost('admin_get_nodes', 'admin_reorder', 'admin_reparent', 'admin_rename_node');
        }

        function admin_index() {}


        function admin_read() {
            $parent = intval($this->params['url']['node']);
            $data = array();

            $nodes = $this->Navigation->children($parent, true);

            foreach ($nodes as $node) {
                $data[] = array(
                    'id'   => $node['Navigation']['id'],
                    'text' => $node['Navigation']['title'],
                    'link' => $node['Navigation']['link'],
                    'leaf' => ($node['Navigation']['lft'] + 1 === $node['Navigation']['rght'])
                );
            }

            $this->set('data', $data);
            return $this->render(null, null, '/elements/ajaxreturn');
        }

        function admin_destroy() {}

        /**
         * Retrieves request from Ajax and finds the parent and it's children
         */
        function admin_get_nodes() {
            // retreive the node id that ExtJS posts via Ajax
            $parent = intval($this->params['url']['node']);

            // find all the nodes underneath the parent node defined above
            // the second parameter (true) means we only want direct children
            $nodes = $this->Navigation->children($parent, true);

            $this->set(compact('nodes'));
        }

        function admin_reorder(){
            $success = false;
            // retrieve the node instructions from javascript
            // delta is the difference in position (1 = next node, -1 = previous node)
            $node = intval($this->params['form']['node']);
            $delta = intval($this->params['form']['delta']);
			
			FireCake::log($this->params);

            if ($delta > 0) {
            	FireCake::log('delta is greater than 0');
                if ($this->Navigation->moveDown($node, abs($delta))) {
                    $success = true;
                }
            } elseif ($delta < 0) {
            	FireCake::log('delta is less than 0');
                if ($this->Navigation->moveUp($node, abs($delta))) {
                    $success = true;
                }
            }
			
			FireCake::log($success, 'success');

            $this->set(compact('success'));
        }

        function admin_reparent(){
            $success = false;
            $node = intval($this->params['form']['node']);
            $parent = intval($this->params['form']['parent']);
            $position = intval($this->params['form']['position']);

            // save the navigation node with the new parent id
            // this will move the employee node to the bottom of the parent list
            $this->Navigation->id = $node;
            $this->Navigation->saveField('parent_id', $parent);

            $count = $this->Navigation->childcount($parent, true);
            $delta = $count-$position-1;
            if ($delta > 0){
                if ($this->Navigation->moveup($node, $delta)) {
                    $success = true;
                }
            }

            $this->set(compact('success'));
        }
		
		function admin_update() {
			$params = $this->params;

            FireCake::log($params);
			
			if (substr($params['form']['link'], 0, 1) !== '/' && substr($params['form']['link'], 0, 4) !== 'http') {
				$params['form']['link'] = '/' . $params['form']['link'];
			}
			
			$this->Navigation->read(null, $params['form']['id']);
			$this->Navigation->set(array(
				'title' => $params['form']['name'],
				'link' => $params['form']['link']
			));

            die();

			$record = $this->Navigation->save();
			
			if ($record) {
				$data['success'] = true;
				$data['navigation'] = $record['Navigation'];
				$data['navigation']['id'] = $params['form']['id'];
			} else {
				$data['success'] = false;
			}
			
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');
		}
		
		function admin_create() {			
			$params = $this->params;
			if (substr($params['form']['link'], 0, 1) !== '/' && substr($params['form']['link'], 0, 4) !== 'http') {
				$params['form']['link'] = '/' . $params['form']['link'];
			}
			
			$this->data = array(
				'Navigation' => array(
					'title' => $params['form']['name'],
					'link'  => $params['form']['link'],
					'parent_id' => $params['form']['parentId']
				)
			);
			$record = $this->Navigation->save($this->data);
			
			if ($record) {
				$data['success'] = true;
				$data['navigation'] = $record['Navigation'];
				$data['navigation']['id'] = $this->Navigation->getLastInsertId();
			} else {
				$data['success'] = false;
			}
			
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');			
		}

        function admin_rename_node() {
            $success = false;
            $nodeId = intval($this->params['form']['id']);
            $nodeTitle = $this->params['form']['title'];
            
            $this->Navigation->read(null, $nodeId);
            $this->Navigation->set('title', $nodeTitle);
            if ($this->Navigation->save()) {
                $success = true;
            }

            $this->set(compact('success'));
        }

        function admin_delete_node() {
            $success = false;
            $nodeId = intval($this->params['form']['id']);
            if ($this->Navigation->delete($nodeId)) {
                $success = true;
            }

            $this->set(compact('success'));
        }
}
?>
