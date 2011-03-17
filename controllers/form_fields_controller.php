<?php
class FormFieldsController extends AppController {

	var $name = 'FormFields';

	function index() {
		$this->FormField->recursive = 0;
		$this->set('formFields', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->FormField->create();
			if ($this->FormField->save($this->data)) {
				$this->Session->setFlash(__('The form field has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The form field could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid form field', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->FormField->save($this->data)) {
				$this->Session->setFlash(__('The form field has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The form field could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->FormField->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for form field', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->FormField->delete($id)) {
			$this->Session->setFlash(__('Form field deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Form field was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	function admin_index() {
		$this->FormField->recursive = 0;
		$this->set('formFields', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->FormField->create();
			if ($this->FormField->save($this->data)) {
				$this->Session->setFlash(__('The form field has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The form field could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid form field', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->FormField->save($this->data)) {
				$this->Session->setFlash(__('The form field has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The form field could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->FormField->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for form field', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->FormField->delete($id)) {
			$this->Session->setFlash(__('Form field deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Form field was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>