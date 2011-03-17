<?php
class FormsController extends AppController {

	var $name = 'Forms';

	function index() {
		$this->Form->recursive = 0;
		$this->set('forms', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->Form->create();
			if ($this->Form->save($this->data)) {
				$this->Session->setFlash(__('The form has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The form could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid form', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Form->save($this->data)) {
				$this->Session->setFlash(__('The form has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The form could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Form->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for form', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Form->delete($id)) {
			$this->Session->setFlash(__('Form deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Form was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	function admin_index() {
		$this->Form->recursive = 0;
		$this->set('forms', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Form->create();
			if ($this->Form->save($this->data)) {
				$this->Session->setFlash(__('The form has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The form could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid form', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Form->save($this->data)) {
				$this->Session->setFlash(__('The form has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The form could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Form->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for form', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Form->delete($id)) {
			$this->Session->setFlash(__('Form deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Form was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>