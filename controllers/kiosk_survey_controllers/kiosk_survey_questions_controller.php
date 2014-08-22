<?php
class KioskSurveyQuestionsController extends AppController {

	var $name = 'KioskSurveyQuestions';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('question');
    }

    function question() {
        $this->layout = 'kiosk';
        $responseId = $this->Session->read('surveyResponseId');

        if (! empty($this->data)) {
            $questionNumber = $this->data['KioskSurveyQuestions']['question_number'];
            $surveyId = $this->data['KioskSurveyQuestions']['survey_id'];

            $this->KioskSurveyQuestion->KioskSurvey
                ->KioskSurveyResponse
                ->KioskSurveyResponseAnswer->create(
                array(
                    'kiosk_survey_response_id' => $responseId,
                    'question_id' => $this->data['KioskSurveyQuestions']['question_id'],
                    'answer' => $this->data['KioskSurveyQuestions']['answer']
                )
                );

            $this->KioskSurveyQuestion->KioskSurvey
                ->KioskSurveyResponse
                ->KioskSurveyResponseAnswer->save();

            $nextQuestion = $questionNumber + 1;
            $this->redirect("/kiosk/survey/{$surveyId}/question/{$nextQuestion}");
        } else {
            $questionNumber = $this->params['question_number'];
        }

        $this->KioskSurveyQuestion->KioskSurvey->recursive = -1;
        $this->KioskSurveyQuestion->KioskSurvey->Behaviors->attach('Containable');

        $survey = $this->KioskSurveyQuestion->KioskSurvey->find(
            'all',
            array(
                'conditions' => array(
                    'KioskSurvey.id' => $this->params['survey_id']
                ),
                'contain' => array(
                    'KioskSurveyQuestion' => array(
                        'order' => 'KioskSurveyQuestion.order ASC'
                    ),
                    'KioskSurveyResponse' => array(
                        'conditions' => array("KioskSurveyResponse.id = {$responseId}")
                    )
                )
            )
        );

        // check to see if there are more questions
        $more_questions = count($survey[0]['KioskSurveyQuestion']) < $questionNumber;
        if($more_questions)
        {
            $this->Session->setFlash(__('Thank you for taking the time to complete the survey. Your input is very important to us.', true), 'flash_success');
            $this->redirect('/kiosk/kiosks/self_sign_service_selection');
        }
        
        $question = Set::extract("/KioskSurveyQuestion/.[{$questionNumber}]", $survey);

        if ($question[0]['type'] === 'multi') {
            $question[0]['options'] = explode(',', $question[0]['options']);
        }


        $this->set('questionNumber', $questionNumber);
        $this->set('totalCount', count($survey[0]['KioskSurveyQuestion']));
        $this->set('survey', $survey);
        $this->set('question', $question[0]);
    }


	function admin_create() {
		$params = json_decode($this->params['form']['surveyQuestions'], true);
		$data = null;
		
		$this->data['KioskSurveyQuestion'] = array(
			'kiosk_survey_id' => $params['kiosk_survey_id'],
			'question' => $params['question'],
			'type' => $params['type'],
            'options' => (isset($params['options'])) ? $params['options'] : null,
            'order' => $params['order']
		);
		
		$result = $this->KioskSurveyQuestion->save($this->data);
		
		if ($result) {
			$this->Transaction->createUserTransaction('Programs', null, null,
				'Created self sign survey question for survey id ' . $params['kiosk_survey_id']);				
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	function admin_read() {
		$this->KioskSurveyQuestion->recursive = -1;
		$params = $this->params['url'];
		$kioskId = $params['kiosk_id'];
		$questions = $this->KioskSurveyQuestion->findAllByKioskSurveyId($kioskId);
		$data = null;
		
		$questions = Set::extract('/KioskSurveyQuestion', $questions);

		if ($questions) {
			$data['success'] = true;
			$data['total'] = count($questions);
			foreach ($questions as $key => $value) {
				foreach ($value['KioskSurveyQuestion'] as $k => $v) {
					$data['surveyQuestions'][$key][$k] = $v;
				}
			}
		} else {
			$data['success'] = true;
			$data['total'] = 0;
			$data['surveyQuestions'] = array();
		}

		$this->set('data', $data);	
		return $this->render(null, null, '/elements/ajaxreturn');
	}

    function admin_update() {
        $data = null;
        $params = json_decode($this->params['form']['surveyQuestions'], true);

        if (isset($params['type'])) {
            if ($params['type'] === 'yesno' || $params['type'] === 'truefalse') {
                $params['options'] = null;
            }
        }
        
        FireCake::log($this->params);

        $this->KioskSurveyQuestion->read(null, $params['id']);
        $this->KioskSurveyQuestion->set($params);

        if ($this->KioskSurveyQuestion->save()) {
			$this->Transaction->createUserTransaction('Programs', null, null,
				'Updated self sign survey question id ' . $params['id']);				
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

	function admin_destroy() {
	  $recordIdString = json_decode($this->params['form']['surveyQuestions'], true);
	  $recordId = intval($recordIdString['id']);
	  
	  FireCake::log($recordIdString);
	  
	  if ($this->KioskSurveyQuestion->delete($recordId)) {
			$this->Transaction->createUserTransaction('Programs', null, null,
				'Deleted self sign survey question id ' . $recordId);				
	    $data['success'] = true;
	  } else {
	    $data['success'] = false;
	  }
	  
	  $this->set('data', $data);
	  return $this->render(null, null, '/elements/ajaxreturn');  
	}
}
?>
