<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class DocumentFilingCategoriesController extends AppController {

    var $name = 'DocumentFilingCategories';
    var $helpers = array('Tree');
	var $components = array('RequestHandler');
	
    function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;
		if($this->Auth->user('role_id') > 1) {
		    $this->Auth->allow('admin_get_child_cats', 'admin_get_grand_child_cats', 'admin_get_cats');
		}
    }

    function admin_index() {
    	if($this->RequestHandler->isAjax()) {			
			 $parent = intval($this->params['url']['node']);
			 $nodes = $this->DocumentFilingCategory->children($parent, true);
			 $data = array();
			 if($nodes) {
				foreach ($nodes as $node){
					if($node['DocumentFilingCategory']['disabled'] == 0) {
						$disabled = false;
					}
					else {
						$disabled = true;
					}
				    $data[] = array(
				    	"success" => true,
				        "text" => $node['DocumentFilingCategory']['name'], 
				        "id" => $node['DocumentFilingCategory']['id'],
				        "disabled" =>  $disabled,
				        "cls" => "folder",
				        "leaf" => ($node['DocumentFilingCategory']['lft'] + 1 == $node['DocumentFilingCategory']['rght']),
				        "secure" => intval($node['DocumentFilingCategory']['secure'])
				    );
				}			 	
			 }
			 else {
			 	$data['success'] = false;
				$data['message'] = 'No categories found';
			 }
			$this->set('data', $data);
			return $this->render(null, null, '/elements/ajaxreturn');    		
    	}	
    }

    function admin_add() {
		if($this->RequestHandler->isAjax()) {

			if(!empty($this->params['form']['parentId']) && !empty($this->params['form']['catName'])){
	
				$parents = $this->DocumentFilingCategory->getpath($this->params['form']['parentId']);

				if(count($parents) > 2) {
					$data['success'] = false;
					$data['message'] = 'Categories cannot be more than three levels deep.';
 				}
				else {
					$this->data['DocumentFilingCategory']['name'] = $this->params['form']['catName'];
					if($this->params['form']['parentId'] == 'source') {
						$this->params['form']['parentId'] = null;
					}
					$this->data['DocumentFilingCategory']['parent_id'] = $this->params['form']['parentId'];
					if($this->DocumentFilingCategory->save($this->data)){
						$data['success'] = true;
						$data['message'] = 'Category was saved successfully.';
						$data['node'] = $this->params['form']['parentPath'];
					}
					else{
						$data['message'] = 'Unable to save category at this time, please try again.';
						$data['success'] = false;	
					} 			
				}		
			}
			$this->set('data', $data);
			return $this->render(null, null, '/elements/ajaxreturn');	
		}	
    }

    function admin_reorder_categories() {
	    // retrieve the node instructions from javascript
	    // delta is the difference in position (1 = next node, -1 = previous node)
	    if($this->RequestHandler->isAjax()){
	    	if(isset($this->params['form']['node'], $this->params['form']['delta'])) {
	    		$node = intval($this->params['form']['node']);
		    	$delta = intval($this->params['form']['delta']);
			    if ($delta > 0) {
			        $this->DocumentFilingCategory->movedown($node, abs($delta));
			    } 
			    elseif ($delta < 0) {
			        $this->DocumentFilingCategory->moveup($node, abs($delta));
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
	
    function admin_reparent_categories() {
    	if($this->RequestHandler->isAjax()){
    		if(isset($this->params['form']['node'], 
    			$this->params['form']['parent'], $this->params['form']['position'] )){
					$node = intval($this->params['form']['node']);
					$parent = intval($this->params['form']['parent']);
					$position = intval($this->params['form']['position']);
					
					$parents = $this->DocumentFilingCategory->getpath($this->params['form']['parent']);
					  
					$children = $this->DocumentFilingCategory->children($node);
					  
					$result = Set::combine($children, '{n}.DocumentFilingCategory.parent_id');			   
			  
					if((count($parents) + count($result)) > 2 ) {
						$data['success'] = false;
					}
					else {		  
						// save the node with the new parent id
						// this will move the node to the bottom of the parent list
						
						$this->DocumentFilingCategory->id = $node;
						$this->DocumentFilingCategory->saveField('parent_id', $parent);
						
						// If position == 0, then we move it straight to the top
						// otherwise we calculate the distance to move ($delta).
						// We have to check if $delta > 0 before moving due to a bug
						// in the tree behavior (https://trac.cakephp.org/ticket/4037)
						
						if($position == 0) {
							$this->DocumentFilingCategory->moveup($node, true);
						}
						else {
							$count = $this->DocumentFilingCategory->childcount($parent, true);
							$delta = $count - $position - 1;
							if($delta > 0) {
								$this->DocumentFilingCategory->moveup($node, $delta);
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

    function admin_edit() {
		if($this->RequestHandler->isAjax()){
			if(!empty($this->params['form']['category'])){
				$params = json_decode($this->params['form']['category']);
				$this->data['DocumentFilingCategory']['id'] = $params->id;
				$this->data['DocumentFilingCategory']['name'] = $params->text;
				if($this->DocumentFilingCategory->save($this->data)){
					$data['success'] = true;
				}
				else {
					$data['success'] = false;
				}
			}
			else $data['success'] = false;
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');
		}
    }
	
	function admin_toggle_disabled() {
		if($this->RequestHandler->isAjax()){
			$this->DocumentFilingCategory->recursive = 0;
			if(!empty($this->data)) {			
				if($this->data['DocumentFilingCategory']['id'] == 'source'){
					$data['success'] = false;
					$data['message'] = 'Cannot disable root category.';
					$this->set(compact('data'));
					return $this->render(null, null, '/elements/ajaxreturn');				
				}			
				if($this->data['DocumentFilingCategory']['disabled'] == 1) {
					$count = $this->DocumentFilingCategory->find('count', array(
						'conditions' => array(
							'DocumentFilingCategory.parent_id' => $this->data['DocumentFilingCategory']['id'],
							'DocumentFilingCategory.disabled' => 0)));
					if(isset($count) && $count > 0){
						$data['success'] = false;
						$data['message'] = 'Cannot disable category that has children.';
						$this->set(compact('data'));
						return $this->render(null, null, '/elements/ajaxreturn');							
					}							
				}				
				if($this->data['DocumentFilingCategory']['disabled'] == 0) {
					$cat = $this->DocumentFilingCategory->findById($this->data['DocumentFilingCategory']['id']);

					if($cat['DocumentFilingCategory']['parent_id'] != NULL) {
						$parent = $this->DocumentFilingCategory->findById($cat['DocumentFilingCategory']['parent_id']);

						if($parent['DocumentFilingCategory']['disabled'] == 1) {
							$data['success'] = false;
							$data['message'] = 'Cannot enable child category of disabled parent.';	
							$this->set(compact('data'));
							return $this->render(null, null, '/elements/ajaxreturn');
						}						
					}
				}
				if($this->DocumentFilingCategory->save($this->data)){
					$data['success'] = true;
					if($this->data['DocumentFilingCategory']['disabled'] == 0){
						$data['message'] = 'Category enabled successfully.';
						$data['disabled'] = false;
					}
					elseif($this->data['DocumentFilingCategory']['disabled'] == 1) {
						$data['message'] = 'Category disabled successfully.';
						$data['disabled'] = true;						
					}
 				}
				else $data['success'] = false;
			}
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_toggle_secure() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				if(!$this->data['DocumentFilingCategory']['secure']) {
					$this->data['DocumentFilingCategory']['secure_admins'] = '[]';
				} 
				if($this->DocumentFilingCategory->save($this->data)){
					$data['success'] = true;
					if($this->data['DocumentFilingCategory']['secure'] == 1){
						$data['message'] = 'Category secured successfully.';
						$data['secure'] = true;
					}
					elseif($this->data['DocumentFilingCategory']['secure'] == 0) {
						$data['message'] = 'Category unsecured successfully.';
						$data['disabled'] = false;						
					}
				}
				else $data['success'] = false;
			}
			else $data['success'] = false;
			$this->set(compact(('data')));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
	
    function admin_get_cats() {
		if($this->RequestHandler->isAjax()) {
			if($this->params['url']['parentId'] == 'parent') {
				$conditions['DocumentFilingCategory.parent_id'] = null;
			}
			elseif($this->params['url']['parentId'] == 'notParent') {
				$conditions['not'] = array('DocumentFilingCategory.parent_id' => null);
			}
			else{
				$conditions['DocumentFilingCategory.parent_id'] = $this->params['url']['parentId'] ;
			}
			$conditions['DocumentFilingCategory.disabled'] = 0;
			$this->DocumentFilingCategory->recursive = -1;
		    $cats = $this->DocumentFilingCategory->find('all', array(
				'conditions' => $conditions,
				'fields' => array(
					'DocumentFilingCategory.id', 
					'DocumentFilingCategory.parent_id', 
					'DocumentFilingCategory.name',
					'DocumentFilingCategory.secure')));
			$i = 0;
			foreach($cats as $cat){
				if($cat['DocumentFilingCategory']['secure']) {}
				$data['cats'][$i]['id'] = $cat['DocumentFilingCategory']['id'];
				$data['cats'][$i]['parent_id'] = $cat['DocumentFilingCategory']['parent_id'];
				$data['cats'][$i]['name'] = $cat['DocumentFilingCategory']['name'];
				$data['cats'][$i]['secure'] = intval($cat['DocumentFilingCategory']['secure']);
				$i++;
			}
			if(!empty($data['cats'])){
				$data['success'] = true;
			}
			else {
				$data['success'] = true;
				$data['cats'] = array();
			}		
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');
		}
    }
			
	// @TODO remove this function when we switch the entire admin area to EXTJS 
    function admin_get_child_cats() {
		if($this->RequestHandler->isAjax()) {
		    $data = $this->DocumentFilingCategory->find('list', array(
				'conditions' => array(
					'DocumentFilingCategory.parent_id' => $this->params['url']['id'],
					'DocumentFilingCategory.disabled' => 0),
				'fields' => array('DocumentFilingCategory.id', 'DocumentFilingCategory.name')));	
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');
		}
    }

	// @TODO remove this function when we switch the entire admin area to EXTJS
    function admin_get_grand_child_cats() {
		if($this->RequestHandler->isAjax()) {
		    $data = $this->DocumentFilingCategory->find('list', array(
				'conditions' => array(
					'DocumentFilingCategory.parent_id' => $this->params['url']['id'],
					'DocumentFilingCategory.disabled' => 0)));
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');
		}
    }
}
