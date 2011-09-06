<?php
class KioskSurveysController extends AppController {

	var $name = 'KioskSurveys';
    var $survey = null;
    var $questions = null;
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('start');
	}
	
	function start() {
        $survey = $this->KioskSurvey->findById($this->params['survey_id']);
        debug($survey);
        $this->set('survey', $survey);

		$this->layout = 'kiosk';
	}

    function question() {
        
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
	
	function admin_update() {
        $this->KioskSurvey->recursive = -1;
        $data = null;
        $params = $this->params['form'];

        FireCake::log($params);

        $this->data('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
	}
	
	function admin_destroy() {
	  $recordIdString = json_decode($this->params['form']['surveys']);
	  $recordId = intval($recordIdString);
	  
	  if ($this->KioskSurvey->delete($recordId)) {
	    $data['success'] = true;
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
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
}

