<?php
class AlertsController extends AppController {

	var $name = 'Alerts';

	function admin_index() {
		$this->Alert->recursive = 0;
		$this->set('alerts', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Alert->create();
			if ($this->Alert->save($this->data)) {
				$this->Session->setFlash(__('The alert has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The alert could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		$users = $this->Alert->User->find('list');
		$this->set(compact('users'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid alert', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Alert->save($this->data)) {
				$this->Session->setFlash(__('The alert has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The alert could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Alert->read(null, $id);
		}
		$users = $this->Alert->User->find('list');
		$this->set(compact('users'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for alert', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Alert->delete($id)) {
			$this->Session->setFlash(__('Alert deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Alert was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>