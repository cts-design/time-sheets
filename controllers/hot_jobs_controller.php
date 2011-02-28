<?php
class HotJobsController extends AppController {

	var $name = 'HotJobs';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	function index() {
		$this->HotJob->recursive = 0;
		$this->set('hotJobs', $this->paginate());
	}

	function admin_index() {
		$this->HotJob->recursive = 0;
		$this->set('hotJobs', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			if ($this->data['HotJob']['file']['error'] === 4) {
				unset($this->data['HotJob']['file']);
			}
		
			$this->HotJob->create();
			if ($this->HotJob->save($this->data)) {
				$this->Session->setFlash(__('The hot job has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hot job could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid hot job', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->data['HotJob']['file']['error'] === 4) {
				unset($this->data['HotJob']['file']);
			}

			if ($this->HotJob->save($this->data)) {
				$this->Session->setFlash(__('The hot job has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hot job could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->HotJob->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for hot job', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->HotJob->delete($id)) {
			$this->Session->setFlash(__('Hot job deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Hot job was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>