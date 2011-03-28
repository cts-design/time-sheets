<?php
class EmployersSurveysController extends AppController {

	var $name = 'EmployersSurveys';

	function index() {}

	function add() {
		if (!empty($this->data)) {
			$data = json_encode($this->data['EmployersSurvey']);
			$this->data['EmployersSurvey']['answers'] = $data;
			$this->EmployersSurvey->create();
			if ($this->EmployersSurvey->save($this->data)) {
				$this->Session->setFlash(__('The employers survey has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'success'));
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

	function admin_read() {
		$surveys = $this->EmployersSurvey->find('all');
		$i = 0;
		foreach ($surveys as $key => $value) {
			$value['EmployersSurvey']['answers'] = json_decode($value['EmployersSurvey']['answers'], true);
			$data['surveys'][] = $value['EmployersSurvey'];
			debug($value['EmployersSurvey']['answers']);
			$i++;
		}
		
		debug($data);
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
	
	function admin_destroy() {
		$surveyId = str_replace("\"", "", $this->params['form']['surveys']);
		$surveyId = intval($surveyId);

		if ($this->EmployersSurvey->delete($surveyId)) {
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}

}
?>