<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class NavigationsController extends AppController {
	var $name = 'Navigations';
        var $helpers = array();

        function beforeFilter() {
            parent::beforeFilter();
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

        function admin_reorder() {
    	    // retrieve the node instructions from javascript
    	    // delta is the difference in position (1 = next node, -1 = previous node)
    	    if($this->RequestHandler->isAjax()){
    	    	if(isset($this->params['form']['node'], $this->params['form']['delta'])) {
    	    		$node = intval($this->params['form']['node']);
    		    	$delta = intval($this->params['form']['delta']);
    			    if ($delta > 0) {
    			        $this->Navigation->movedown($node, abs($delta));
    			    } 
    			    elseif ($delta < 0) {
    			        $this->Navigation->moveup($node, abs($delta));
    			    }
    			    // send success response
    			    $data['success'] = true;
    	    	}
    			else {
    				$data['success'] = false;
    			}
    			$this->set('data', $data);
    			return $this->render(null, null, '/elements/ajaxreturn');	   				
    	    }		
        }

        function admin_reparent() {
        	if($this->RequestHandler->isAjax()){
        		if(isset($this->params['form']['node'], 
        			$this->params['form']['parent'], $this->params['form']['position'] )){
    					$node = intval($this->params['form']['node']);
    					$parent = intval($this->params['form']['parent']);
    					$position = intval($this->params['form']['position']);

    					$parents = $this->Navigation->getpath($this->params['form']['parent']);

    					$children = $this->Navigation->children($node);

    					$result = Set::combine($children, '{n}.Navigation.parent_id');			   

    					if((count($parents) + count($result)) > 2 ) {
    						$data['success'] = false;
    					}
    					else {		  
    						// save the node with the new parent id
    						// this will move the node to the bottom of the parent list

    						$this->Navigation->id = $node;
    						$this->Navigation->saveField('parent_id', $parent);

    						// If position == 0, then we move it straight to the top
    						// otherwise we calculate the distance to move ($delta).
    						// We have to check if $delta > 0 before moving due to a bug
    						// in the tree behavior (https://trac.cakephp.org/ticket/4037)

    						if($position == 0) {
    							$this->Navigation->moveup($node, true);
    						}
    						else {
    							$count = $this->Navigation->childcount($parent, true);
    							$delta = $count - $position - 1;
    							if($delta > 0) {
    								$this->Navigation->moveup($node, $delta);
    							}
    						}
    						// send success response
    						$data['success'] = true;   			  	
    					}

    	    	}
    			else {
    				$data['success'] = false;
    			}			 
    			$this->set('data', $data);
    			return $this->render(null, null, '/elements/ajaxreturn');
    		}	
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

			$record = $this->Navigation->save();
			
			if ($record) {
				$data['success'] = true;
				$data['message'] = 'Navigation link edited successfully.';
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
				$data['message'] = 'Navigation link saved successfully';
				$data['node'] = $this->params['form']['parentPath'];
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
