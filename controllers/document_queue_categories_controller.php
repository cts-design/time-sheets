<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class DocumentQueueCategoriesController extends AppController {

	var $name = 'DocumentQueueCategories';
	
    function beforeFilter() {
		parent::beforeFilter();
		if($this->Auth->user('role_id') > 1) {
		    $this->Auth->allow('admin_get_cats');
		}
    }
		
	function admin_index() {
		$this->DocumentQueueCategory->recursive = 0;
		$this->paginate = array(
		    'conditions' => array('DocumentQueueCategory.deleted' => 0)
		);
		$this->set('documentQueueCategories', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->DocumentQueueCategory->create();
			if ($this->DocumentQueueCategory->save($this->data)) {
				$this->Session->setFlash(__('The document queue category has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The document queue category could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid document queue category', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->DocumentQueueCategory->save($this->data)) {
				$this->Session->setFlash(__('The document queue category has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The document queue category could not be saved. Please, try again.', true), 'flash_success');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->DocumentQueueCategory->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for document queue category', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->DocumentQueueCategory->delete($id)) {
			$this->Session->setFlash(__('Document queue category deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Document queue category was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	
    function admin_get_cats() {
		if($this->RequestHandler->isAjax()) {
			$this->DocumentQueueCategory->recursive -1;
		    $cats = $this->DocumentQueueCategory->find('all', array(
				'fields' => array('DocumentQueueCategory.id', 'DocumentQueueCategory.name')));
			$i = 0;
			foreach($cats as $cat){
				$data['cats'][$i]['id'] = $cat['DocumentQueueCategory']['id'];
				$data['cats'][$i]['name'] = $cat['DocumentQueueCategory']['name'];
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
}
