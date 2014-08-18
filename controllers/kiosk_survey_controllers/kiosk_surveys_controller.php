<?php
class KioskSurveysController extends AppController {

	var $name = 'KioskSurveys';
    var $survey = null;
	var $questions = null;
	var $helpers = array('Excel');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('start', 'cancel');
	}
	
	function start() {
        $surveyId = $this->params['survey_id'];


        $user_id = $this->Auth->user('id') ? $this->Auth->user('id') : 0;

        // create the response record
        $this->KioskSurvey->KioskSurveyResponse->create(
            array(
                'kiosk_survey_id' => $surveyId,
                'user_id' => $user_id
            )
        );
        $this->KioskSurvey->KioskSurveyResponse->save();
        $this->Session->write('surveyResponseId', $this->KioskSurvey->KioskSurveyResponse->id);

        $this->redirect("/kiosk/survey/{$surveyId}/question/1");
	}
	
	function cancel() {
		$this->Session->destroy();
		$this->redirect('/kiosk');
	}

	function admin_index() {}
	
	function admin_create() {
	  $params = json_decode($this->params['form']['surveys'], true);
	  $data = null;
	  
	  $this->KioskSurvey->create();
	  $this->data = array(
	    'KioskSurvey' => array(
	      'name' => $params['name']
	    )
	  );
	  
	  $result = $this->KioskSurvey->save($this->data);
	  if ($result) {
		  $data['success'] = true;
		  $data['surveys'] = $result['KioskSurvey'];
		  $data['surveys']['id'] = $this->KioskSurvey->id;
		  $this->Transaction->createUserTransaction('Self Sign Survey', null, null,
			  'Created self sign survey ' . $params['name']);
	  } else {
	    $data['success'] = false;
	  }
	  
	  $this->set('data', $data);
	  return $this->render(null, null, '/elements/ajaxreturn');
	}

	function admin_read() {
		$this->KioskSurvey->recursive = -1;
		$data = null;
		$surveys = $this->KioskSurvey->find('all');

		foreach ($surveys as $key => $value) {
			foreach ($value['KioskSurvey'] as $k => $v) {
				$data['surveys'][$key][$k] = $v;
			}
		}

		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
	
	function admin_destroy() {
	  $recordIdString = json_decode($this->params['form']['surveys'], true);
	  $recordId = intval($recordIdString['id']);
	  
	  FireCake::log($recordIdString);
	  FireCake::log($recordId);
	  
	  if ($this->KioskSurvey->delete($recordId)) {
	    $data['success'] = true;
		  $this->Transaction->createUserTransaction('Self Sign Survey', null, null,
			  'Deleted self sign survey id ' . $recordId);
	  } else {
	    $data['success'] = false;
	  }
	  
	  $this->set('data', $data);
	  return $this->render(null, null, '/elements/ajaxreturn');  
	}
	
	/**
	 * Responsible for attaching surveys to kiosks
	 *
	 * @return Element The ajaxreturn element is returned to provide the json result
	 */
	function admin_attach() {
		$this->KioskSurvey->recursive = -1;
        $kioskId = intval($this->params['form']['kiosk_id']);
        $surveyId = intval($this->params['form']['survey_id']);
		
		$survey = $this->KioskSurvey->findById($surveyId);
		
		$this->data = $survey;
		$this->data['Kiosk'] = array(
			'id' => $kioskId
		);
		
		if ($this->KioskSurvey->saveAll($this->data)) {
		  $this->Transaction->createUserTransaction('Self Sign Survey', null, null,
			  'Attached self sign survey id ' . $surveyId . ' to kiosk id ' . $kioskId);
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
	
	/**
	 * Responsible for detaching surveys to kiosks
	 *
	 * @return Element The ajaxreturn element is returned to provide the json result
	 */
	function admin_detach() {
		$this->KioskSurvey->recursive = -1;
		$kioskId = intval($this->params['form']['kiosk_id']);
		
		if ($this->KioskSurvey->KiosksKioskSurvey->deleteAll(array('KiosksKioskSurvey.kiosk_id' => $kioskId))) {
		  $this->Transaction->createUserTransaction('Self Sign Survey', null, null,
			  'Detached self sign survey from kiosk id ' . $kioskId);
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	function admin_report() {
		FireCake::log($this->params);
		$data = null;
		$report = null;
		$surveyId = $this->params['url']['survey_id'];
		$this->KioskSurvey->recursive = -1;
		$this->KioskSurvey->Behaviors->attach('Containable');

		$survey = $this->KioskSurvey->find(
			'first',
			array(
				'conditions' => array(
					"KioskSurvey.id = {$surveyId}"
				),
				'contain' => array(
					'KioskSurveyQuestion' => array(
						'conditions' => "KioskSurveyQuestion.kiosk_survey_id = {$surveyId}",
						'fields' => array(
							'KioskSurveyQuestion.id',
							'KioskSurveyQuestion.question',
							'KioskSurveyQuestion.order'
						),
						'order' => 'KioskSurveyQuestion.order ASC'
					),
					'KioskSurveyResponse' => array(
						'KioskSurveyResponseAnswer' => array(
							'fields' => array(
								'KioskSurveyResponseAnswer.question_id',
								'KioskSurveyResponseAnswer.answer',
								'KioskSurveyResponseAnswer.created'
							),
							'order' => 'KioskSurveyResponseAnswer.created ASC'
						),
						'conditions' => "KioskSurveyResponse.kiosk_survey_id = {$surveyId}",
						'fields' => array('KioskSurveyResponse.id', 'KioskSurveyResponse.created'),
						'order' => 'KioskSurveyResponse.created ASC'
					)
				)
			)
		);

		$surveyQuestion = Set::combine($survey['KioskSurveyQuestion'], '{n}.id', '{n}.question');

		$title = 'Self sign survey report: ' . $survey['KioskSurvey']['name'] . ' ' . date('m/d/Y');

        foreach ($surveyQuestion as $key => $value) {
            $report[0][$value] = null;
        }

		foreach($survey['KioskSurveyResponse'] as $key => $value) {
			$skip = true;
			foreach($value['KioskSurveyResponseAnswer'] as $k => $v) {
				if (isset($v['answer'])) {
					$report[$key][$surveyQuestion[$v['question_id']]] = Inflector::humanize($v['answer']);
					$skip = false;
				} 
			}
			if (! $skip)
				$report[$key]['Date Taken'] = date('m/d/y h:i a', strtotime($value['created']));
		}

		$data = array(
			'data' => $report,
			'title' => $title
		);

		  $this->Transaction->createUserTransaction('Self Sign Survey', null, null,
			  'Created self sign survey report for survey id ' . $surveyId);

		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$this->set($data);
		$this->render('/elements/excelreport');
	}
}

