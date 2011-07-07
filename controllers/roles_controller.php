<?php
class RolesController extends AppController {

	var $name = 'Roles';

	function admin_index() {
		$this->Role->recursive = 0;
		$this->paginate = array(
		    'conditions' => array('Role.id >' => 3)
		);
		$this->set('roles', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Role->create();
			if ($this->Role->save($this->data)) {
				$this->Session->setFlash(__('The role has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The role could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id || $id <= 3 && empty($this->data)) {
			$this->Session->setFlash(__('Invalid role', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Role->save($this->data)) {
				$this->Session->setFlash(__('The role has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The role could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Role->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id || $id <= 3) {
			$this->Session->setFlash(__('Invalid id for role', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Role->delete($id)) {
			$this->Session->setFlash(__('Role deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Role was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
