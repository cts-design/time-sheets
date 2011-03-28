<?php
class InTheNewsController extends AppController {

	var $name = 'InTheNews';

	function index() {
		$this->InTheNews->recursive = 0;
		$this->set('inTheNews', $this->paginate());
	}

	function admin_index() {
		$this->InTheNews->recursive = 0;
		$this->set('inTheNews', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->InTheNews->create();
			if ($this->InTheNews->save($this->data)) {
				$this->Transaction->createUserTransaction('InTheNews', null, null,
                                        'Created news article ID ' . $this->InTheNews->id);
				$this->Session->setFlash(__('The in the news article has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The in the news article could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid in the news article', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->InTheNews->save($this->data)) {
				$this->Transaction->createUserTransaction('InTheNews', null, null,
                                        'Edited news article ID ' . $id);
				$this->Session->setFlash(__('The in the news article has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The in the news article could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->InTheNews->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for in the news article', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->InTheNews->delete($id)) {
			$this->Transaction->createUserTransaction('InTheNews', null, null,
                                        'Deleted news article ID ' . $id);
			$this->Session->setFlash(__('In the news article deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		} else {
			$this->Session->setFlash(__('In the news article was not deleted', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));	
		}
	}
}
?>