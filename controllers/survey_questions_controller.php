<?php
class SurveyQuestionsController extends AppController {

	var $name = 'SurveyQuestions';

	function admin_create() {
		$params = json_decode($this->params['form'], true);
		
		$this->data = array(
			'SurveyQuestion' => $params
		);
		
		FireCake::log($this->data);
		
		$record = $this->SurveyQuestion->save();
		FireCake::log($record, 'record after save: create');
		if ($record) {
			$data['questions'] = $record['SurveyQuestion'];
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');		
	}
	
	function admin_read() {
		$surveyId = (isset($this->params['url']['survey_id'])) ? $this->params['url']['survey_id'] : null;
		
		if ($surveyId) {
			$conditions = array('survey_id' => $surveyId);
		} else {
			$conditions = null;
		}
		
		App::import('Vendor', 'DebugKit.FireCake');
		FireCake::log($this->params);
		$surveyQuestions = $this->SurveyQuestion->find('all', array('conditions' => $conditions));

		if ($surveyQuestions) {
			$i = 0;
			foreach ($surveyQuestions as $key => $value) {
				$data['questions'][$i] = $surveyQuestions[$key]['SurveyQuestion'];
				$i++;
			}
			
			$data['total'] = count($surveyQuestions);
			$data['success'] = true;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	function admin_update() {

	}

	function admin_destroy() {

	}
}
?>