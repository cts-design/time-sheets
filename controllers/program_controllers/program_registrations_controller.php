<?php 

class ProgramRegistrationsController extends AppController {
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->ProgramRegistration->Program->ProgramField->recursive = -1;
		if(!empty($this->params['pass'][0])) {
			$query = $this->ProgramRegistration->Program->ProgramField->findAllByProgramId($this->params['pass'][0]);			
			$fields = Set::classicExtract($query, '{n}.ProgramField');
			foreach($fields as $k => $v) {
				if(!empty($v['validation'])) 
					$validate[$v['name']] = json_decode($v['validation'], true); 
			}
			$this->ProgramRegistration->modifyValidate($validate);
		}
	}	
	
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		} 
		if(!empty($this->data)) {
			$this->ProgramRegistration->create();
			$data['ProgramRegistration']['answers'] = json_encode($this->data['ProgramRegistration']);
			$data['ProgramRegistration']['program_id'] = $id;
			if($this->ProgramRegistration->save($data)) {
				$this->Session->setFlash(__('Saved', true), 'flash_success');
			}
			else {
				$this->Session->setFlash(__('Unable to save', true), 'flash_failure');
			}			
		}
		$program = $this->ProgramRegistration->Program->findById($id);
		$title_for_layout = $program['Program']['name'] . ' Registration Form' ;
		$this->set(compact('program', 'title_for_layout'));	
	}
}