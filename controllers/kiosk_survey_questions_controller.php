<?php
class KioskSurveyQuestionsController extends AppController {

	var $name = 'KioskSurveyQuestions';

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

	function admin_create() {
		$params = json_decode($this->params['form']['surveyQuestions'], true);
		$data = null;
		
		$this->data['KioskSurveyQuestion'] = array(
			'kiosk_survey_id' => $params['kiosk_survey_id'],
			'question' => $params['question'],
			'type' => $params['type'],
			'options' => $params['options']
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
