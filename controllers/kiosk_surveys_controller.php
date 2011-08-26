<?php
class KioskSurveysController extends AppController {

	var $name = 'KioskSurveys';
	
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
}

