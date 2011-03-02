<?php
class RfpsController extends AppController {

	var $name = 'Rfps';

	function index() {
		$this->Rfp->recursive = 0;
		$this->set('rfps', $this->paginate());
	}

	function admin_index() {
		$this->Rfp->recursive = 0;
		$this->set('rfps', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Rfp->create();
			if ($this->Rfp->save($this->data)) {
				$this->Session->setFlash(__('The rfp has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rfp could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid rfp', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Rfp->save($this->data)) {
				$this->Session->setFlash(__('The rfp has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rfp could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Rfp->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for rfp', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Rfp->delete($id)) {
			$this->Session->setFlash(__('Rfp deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Rfp was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>