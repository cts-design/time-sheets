<?php 

class ProgramRegistrationsController extends AppController {
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->ProgramRegistration->Program->ProgramField->recursive = -1;
		$query = $this->ProgramRegistration->Program->ProgramField->findAllByProgramId(1);
		$fields = Set::classicExtract($query, '{n}.ProgramField');
		foreach($fields as $k => $v) {
			if(!empty($v['validation'])) 
				$validate[$v['name']] = json_decode($v['validation'], true); 
		}
		$this->ProgramRegistration->modifyValidate($validate);
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
