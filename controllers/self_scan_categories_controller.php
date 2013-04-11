<?php
class SelfScanCategoriesController extends AppController {

	var $name = 'SelfScanCategories';

	public function beforeFilter(){
		parent::beforeFilter();
			if($this->Auth->user('role_id') > 1) {
				$this->Auth->allow('admin_get_cats');		
			}
	}

	function admin_index() {
		$this->SelfScanCategory->recursive = 0;
		$order = array('SelfScanCategory.name' => 'asc');
		$this->set('selfScanCategories', $this->SelfScanCategory->find('threaded', array('order' => $order)));
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid self scan category', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		$this->set('selfScanCategory', $this->SelfScanCategory->read(null, $id));
	}

	function admin_add($parentId=null) {
		if (!empty($this->data)) {
			$this->SelfScanCategory->create();
			if($parentId){
			    $this->data['SelfScanCategory']['parent_id'] = $parentId;
			}
			if ($this->SelfScanCategory->save($this->data)) {
				$this->Session->setFlash(__('The self scan category has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The self scan category could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if($parentId) {
		    $parent = $this->SelfScanCategory->read(null, $parentId);
		    $title_for_layout = 'Add Child Self Scan Category to Parent ' . $parent['SelfScanCategory']['name'];
		}
		else {
		    $title_for_layout = 'Add Self Scan Category';
		}
		$conditions = array('DocumentFilingCategory.parent_id' => null);
		$cat1 = $this->SelfScanCategory->DocumentFilingCategory->find('list', array('conditions' => $conditions));
		$conditions = array('DocumentQueueCategory.deleted' => 0);
		$queueCatList = $this->SelfScanCategory->DocumentQueueCategory->find('list', array('conditions' => $conditions));
		$this->set(compact('queueCatList', 'parentId', 'cat1', 'title_for_layout'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid self scan category', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->SelfScanCategory->save($this->data)) {
				$this->Session->setFlash(__('The self scan category has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The self scan category could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->SelfScanCategory->read(null, $id);
		$conditions = array('DocumentFilingCategory.parent_id' => null);
		$cat1 = $this->SelfScanCategory->DocumentFilingCategory->find('list', array('conditions' => $conditions));
		$conditions = array('DocumentQueueCategory.deleted' => 0);
		$queueCatList = $this->SelfScanCategory->DocumentQueueCategory->find('list', array('conditions' => $conditions));
		$this->set(compact('queueCatList', 'cat1'));
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for self scan category', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		$count = $this->SelfScanCategory->find('count', array(
		    'conditions' => array('SelfScanCategory.parent_id' => $id)));
		if($count > 0 ) {
			$this->Session->setFlash(__('Cannot delete a category that has children', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->SelfScanCategory->delete($id)) {
			$this->Session->setFlash(__('Self scan category deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Self scan category was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}

    function admin_get_cats() {
		if($this->RequestHandler->isAjax()) {
		    $cats = $this->SelfScanCategory->find('all', array(
				'fields' => array(
					'SelfScanCategory.id',
					'SelfScanCategory.name',
					'SelfScanCategory.cat_1',
					'SelfScanCategory.cat_2',
					'SelfScanCategory.cat_3')));
			$i = 0;
			if($cats) {
				foreach ($cats as $cat) {
					$data['cats'][$i] = $cat['SelfScanCategory'];
					$i++;				
				}				
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
