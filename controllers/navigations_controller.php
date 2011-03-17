<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
App::import('Vendor', 'DebugKit.FireCake'); // @TODO remove from production
class NavigationsController extends AppController {

	var $name = 'Navigations';
        var $components = array('Security');
        var $helpers = array();

        function beforeFilter() {
            parent::beforeFilter();

            // ensure our ajax methods are POSTed
            $this->Security->requirePost('admin_get_nodes', 'admin_reorder', 'admin_reparent', 'admin_rename_node');
        }

	function admin_index() {
		$this->Navigation->recursive = 0;
		$this->set('navigations', $this->paginate());
	}

        /**
         * Retrieves request from Ajax and finds the parent and it's children
         */
        function admin_get_nodes() {
            // retreive the node id that ExtJS posts via Ajax
            $parent = intval($this->params['form']['node']);

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
   			FireCake::log($this->params);
		}
		
		function admin_create() {			
			$params = $this->params;
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