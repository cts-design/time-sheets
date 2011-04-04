<?php
class ProgramEmailsController extends AppController {

	var $name = 'ProgramEmails';

	function admin_index() {
		$this->ProgramEmail->recursive = 0;
		$this->set('programEmails', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->ProgramEmail->create();
			if ($this->ProgramEmail->save($this->data)) {
				$this->Session->setFlash(__('The program email has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The program email could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		$programs = $this->ProgramEmail->Program->find('list');
		$this->set(compact('programs'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid program email', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ProgramEmail->save($this->data)) {
				$this->Session->setFlash(__('The program email has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The program email could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ProgramEmail->read(null, $id);
		}
		$programs = $this->ProgramEmail->Program->find('list');
		$this->set(compact('programs'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for program email', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ProgramEmail->delete($id)) {
			$this->Session->setFlash(__('Program email deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Program email was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>