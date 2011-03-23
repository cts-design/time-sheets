<?php
class CareerSeekersSurveysController extends AppController {

	var $name = 'CareerSeekersSurveys';

	function index() {

	}

	function add() {
		if (!empty($this->data)) {
			$data = json_encode($this->data['CareerSeekersSurvey']);
			$this->data['CareerSeekersSurvey']['answers'] = $data;
			$this->CareerSeekersSurvey->create();
			if ($this->CareerSeekersSurvey->save($this->data)) {
				$this->Session->setFlash(__('The career seekers survey has been submitted', true), 'flash_success');
				$this->redirect(array('action' => 'success'));
			} else {
				$this->Session->setFlash(__('The career seekers survey could not be submitted. Please, try again.', true), 'flash_failure');
			}
		}
	}
	
	function success() {}
	
	function admin_index() {
		$this->CareerSeekersSurvey->recursive = 0;
		$this->set('careerSeekersSurveys', $this->paginate());
	}
	
	function admin_read() {
		$surveys = $this->CareerSeekersSurvey->find('all');
		$i = 0;
		foreach ($surveys as $key => $value) {
			$value['CareerSeekersSurvey']['answers'] = json_decode($value['CareerSeekersSurvey']['answers'], true);
			$data['surveys'][] = $value['CareerSeekersSurvey'];
			debug($value['CareerSeekersSurvey']['answers']);
			$i++;
		}
		
		debug($data);
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
	
	function admin_destroy() {
		$surveyId = str_replace("\"", "", $this->params['form']['surveys']);
		$surveyId = intval($surveyId);

		if ($this->CareerSeekersSurvey->delete($surveyId)) {
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}

}
?>