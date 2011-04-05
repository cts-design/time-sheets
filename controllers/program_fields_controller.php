<?php
class ProgramFieldsController extends AppController {

	var $name = 'ProgramFields';

	function index() {
		$this->ProgramField->recursive = 0;
		$this->set('programFields', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->ProgramField->create();
			if ($this->ProgramField->save($this->data)) {
				$this->Session->setFlash(__('The program field has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The program field could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid program field', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ProgramField->save($this->data)) {
				$this->Session->setFlash(__('The program field has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The program field could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ProgramField->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for program field', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ProgramField->delete($id)) {
			$this->Session->setFlash(__('Program field deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Program field was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	function admin_index() {
		$this->ProgramField->recursive = 0;
		$this->set('programFields', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->ProgramField->create();
			if ($this->ProgramField->save($this->data)) {
				$this->Session->setFlash(__('The program field has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The program field could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid program field', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ProgramField->save($this->data)) {
				$this->Session->setFlash(__('The program field has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The program field could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ProgramField->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for program field', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ProgramField->delete($id)) {
			$this->Session->setFlash(__('Program field deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Program field was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>