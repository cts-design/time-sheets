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
	
	function admin_index() {
		$this->KioskSurvey->recursive = 0;
		$this->set('kioskSurveys', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->KioskSurvey->create();
			if ($this->KioskSurvey->save($this->data)) {
				$this->Session->setFlash(__('The kiosk survey has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The kiosk survey could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid kiosk survey', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->KioskSurvey->save($this->data)) {
				$this->Session->setFlash(__('The kiosk survey has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The kiosk survey could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->KioskSurvey->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for kiosk survey', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->KioskSurvey->delete($id)) {
			$this->Session->setFlash(__('Kiosk survey deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Kiosk survey was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>
