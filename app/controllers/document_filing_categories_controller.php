<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class DocumentFilingCategoriesController extends AppController {

    var $name = 'DocumentFilingCategories';
    var $helpers = array('Tree');
	var $components = array('RequestHandler');
	
    function beforeFilter() {
		parent::beforeFilter();
		if($this->Auth->user('role_id') > 1) {
		    $this->Auth->allow('admin_get_child_cats_ajax', 'admin_get_grand_child_cats_ajax');
		}
    }

    function admin_index() {
    	if($this->RequestHandler->isAjax()) {
			 $parent = intval($this->params['form']['node']);
			 $nodes = $this->DocumentFilingCategory->children($parent, true);
			 $data = array();
			foreach ($nodes as $node){
			    $data[] = array(
			        "text" => $node['DocumentFilingCategory']['name'], 
			        "id" => $node['DocumentFilingCategory']['id'], 
			        "cls" => "folder",
			        "leaf" => ($node['DocumentFilingCategory']['lft'] + 1 == $node['DocumentFilingCategory']['rght'])
			    );
			}
			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');    		
    	}	
    }

    function admin_add() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->params['form']['parentId']) && !empty($this->params['form']['catName'])){
				fireCake::log($this->params);
				$this->data['DocumentFilingCategory']['name'] = $this->params['form']['catName'];
				if($this->params['form']['parentId'] == 'source') {
					$this->params['form']['parentId'] = null;
				}
				$this->data['DocumentFilingCategory']['parent_id'] = $this->params['form']['parentId'];
				if($this->DocumentFilingCategory->save($this->data)){
					$data['success'] = true;
				}
				else $data['success'] = false;					
			}
			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');	
		}
		
    }

    function admin_reorder_categories_ajax() {
	    // retrieve the node instructions from javascript
	    // delta is the difference in position (1 = next node, -1 = previous node)
	    if($this->RequestHandler->isAjax()){
	    	$node = intval($this->params['form']['node']);
	    	$delta = intval($this->params['form']['delta']);
		    if ($delta > 0) {
		        $this->DocumentFilingCategory->movedown($node, abs($delta));
		    } elseif ($delta < 0) {
		        $this->DocumentFilingCategory->moveup($node, abs($delta));
		    }
		    // send success response
		    exit('1');    				
	    }		
    }

    function admin_edit($id = null) {
	if(!$id && empty($this->data)) {
	    $this->Session->setFlash(__('Invalid category', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	if(!empty($this->data)) {
	    if($this->DocumentFilingCategory->save($this->data)) {
		$this->Session->setFlash(__('The category has been saved', true), 'flash_success');
		$this->redirect(array('action' => 'index'));
	    }
	    else {
		$this->Session->setFlash(__('The category could not be saved. Please, try again.', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	    }
	}
	if(empty($this->data)) {
	    $this->data = $this->DocumentFilingCategory->read(null, $id);
	}
    }

    function admin_delete($id = null) {
	if(!$id) {
	    $this->Session->setFlash(__('Invalid id for category', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	} else {
            $count = $this->DocumentFilingCategory->find('count', array(
                        'conditions' => array(
                            'DocumentFilingCategory.parent_id' => $id,
                            'DocumentFilingCategory.deleted' => 0)));
            if($count > 0) {
                $this->Session->setFlash(__('Cannot delete category that has children', true), 'flash_failure');
                $this->redirect(array('action' => 'index'));
            } else {
                if($this->DocumentFilingCategory->delete($id)) {
                    $this->Session->setFlash(__('Category deleted', true), 'flash_success');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('Category was not deleted', true), 'flash_failure');
                    $this->redirect(array('action' => 'index'));
                }
            }
        }
    }

    function admin_get_child_cats_ajax() {
	if($this->RequestHandler->isAjax()) {
	    $options = $this->DocumentFilingCategory->find('list', array(
			'conditions' => array('DocumentFilingCategory.parent_id' => $this->params['url']['id']),
			'fields' => array('DocumentFilingCategory.id', 'DocumentFilingCategory.name')));
	    $this->set(compact('options'));
	}
    }

    function admin_get_grand_child_cats_ajax() {
	if($this->RequestHandler->isAjax()) {
	    $options = $this->DocumentFilingCategory->find('list', array(
			'conditions' => array('DocumentFilingCategory.parent_id' => $this->params['url']['id'])));
	    $this->set(compact('options'));
	}
    }

    function _setTreeData() {
	$this->DocumentFilingCategory->recursive = -1;
	$data = $this->DocumentFilingCategory->find('threaded',
			array('order' => array('DocumentFilingCategory.order' => 'asc'),
			    'conditions' => array('DocumentFilingCategory.deleted' => 0)));
	$this->set('data', $data);
    }
}