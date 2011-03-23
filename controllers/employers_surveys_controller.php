<?php
class EmployersSurveysController extends AppController {

	var $name = 'EmployersSurveys';

	function index() {}

	function add() {
		if (!empty($this->data)) {
			$this->EmployersSurvey->create();
			if ($this->EmployersSurvey->save($this->data)) {
				$this->Session->setFlash(__('The employers survey has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The employers survey could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function success() {}
	
	function admin_index() {
		$this->EmployersSurvey->recursive = 0;
		$this->set('employersSurveys', $this->paginate());
	}

	function admin_read() {}
	function admin_destroy() {}
}
?>