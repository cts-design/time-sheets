<?php
class CareerSeekersSurveysController extends AppController {

	var $name = 'CareerSeekersSurveys';

	function index() {
		$this->CareerSeekersSurvey->recursive = 0;
		$this->set('careerSeekersSurveys', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->CareerSeekersSurvey->create();
			if ($this->CareerSeekersSurvey->save($this->data)) {
				$this->Session->setFlash(__('The career seekers survey has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The career seekers survey could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid career seekers survey', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->CareerSeekersSurvey->save($this->data)) {
				$this->Session->setFlash(__('The career seekers survey has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The career seekers survey could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->CareerSeekersSurvey->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for career seekers survey', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CareerSeekersSurvey->delete($id)) {
			$this->Session->setFlash(__('Career seekers survey deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Career seekers survey was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	function admin_index() {
		$this->CareerSeekersSurvey->recursive = 0;
		$this->set('careerSeekersSurveys', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->CareerSeekersSurvey->create();
			if ($this->CareerSeekersSurvey->save($this->data)) {
				$this->Session->setFlash(__('The career seekers survey has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The career seekers survey could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid career seekers survey', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->CareerSeekersSurvey->save($this->data)) {
				$this->Session->setFlash(__('The career seekers survey has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The career seekers survey could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->CareerSeekersSurvey->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for career seekers survey', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CareerSeekersSurvey->delete($id)) {
			$this->Session->setFlash(__('Career seekers survey deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Career seekers survey was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>