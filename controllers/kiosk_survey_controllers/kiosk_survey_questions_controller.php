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
                                            'survey_response_id' => $responseId,
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
        if (count($survey[0]['KioskSurveyQuestion']) < $questionNumber) {
            $this->Session->setFlash(__('Thanks', true), 'flash_success');
            $this->redirect('/kiosk');
        }

        $question = Set::extract("/KioskSurveyQuestion/.[{$questionNumber}]", $survey);

        if ($question[0]['type'] === 'multi') {
            $question[0]['options'] = explode(',', $question[0]['options']);
        }


        $this->set('survey', $survey);
        $this->set('question', $question[0]);
    }

	function index() {
		$this->KioskSurveyQuestion->recursive = 0;
		$this->set('kioskSurveyQuestions', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->KioskSurveyQuestion->create();
			if ($this->KioskSurveyQuestion->save($this->data)) {
				$this->Session->setFlash(__('The kiosk survey question has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The kiosk survey question could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid kiosk survey question', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->KioskSurveyQuestion->save($this->data)) {
				$this->Session->setFlash(__('The kiosk survey question has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The kiosk survey question could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->KioskSurveyQuestion->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for kiosk survey question', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->KioskSurveyQuestion->delete($id)) {
			$this->Session->setFlash(__('Kiosk survey question deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Kiosk survey question was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
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

        $this->KioskSurveyQuestion->read(null, $params['id']);
        $this->KioskSurveyQuestion->set($params);

        if ($this->KioskSurveyQuestion->save()) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
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
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	function admin_destroy() {
	  $recordIdString = json_decode($this->params['form']['surveyQuestions']);
	  $recordId = intval($recordIdString);
	  
	  if ($this->KioskSurveyQuestion->delete($recordId)) {
	    $data['success'] = true;
	  } else {
	    $data['success'] = false;
	  }
	  
	  $this->set('data', $data);
	  return $this->render(null, null, '/elements/ajaxreturn');  
	}

	function admin_index() {
		$this->KioskSurveyQuestion->recursive = 0;
		$this->set('kioskSurveyQuestions', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->KioskSurveyQuestion->create();
			if ($this->KioskSurveyQuestion->save($this->data)) {
				$this->Session->setFlash(__('The kiosk survey question has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The kiosk survey question could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid kiosk survey question', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->KioskSurveyQuestion->save($this->data)) {
				$this->Session->setFlash(__('The kiosk survey question has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The kiosk survey question could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->KioskSurveyQuestion->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for kiosk survey question', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->KioskSurveyQuestion->delete($id)) {
			$this->Session->setFlash(__('Kiosk survey question deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Kiosk survey question was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>
