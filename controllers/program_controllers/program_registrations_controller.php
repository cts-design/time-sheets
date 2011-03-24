<?php 

class ProgramRegistrationsController extends AppController {
	
	function beforeFilter() {
		parent::beforeFilter();	
		$this->ProgramRegistration->modifyValidate(array(
		'vpk_program_year' => array('rule' => 'notEmpty')));
	}
	
	function index() {
		if(!empty($this->data)) {
			if($this->ProgramRegistration->save($this->data)) {
				$this->Session->setFlash(__('Saved', true), 'flash_success');
			}
			else {
				$this->Session->setFlash(__('Unable to save', true), 'flash_failure');
			}			
		}
		$program = $this->ProgramRegistration->Program->findById('1');
		$title_for_layout = $program['Program']['name'] . ' Registration Form' ;
		$this->set(compact('program', 'title_for_layout'));	
	}
}
