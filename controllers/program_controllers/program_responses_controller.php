<?php 

class ProgramResponsesController extends AppController {
	
	var $name = 'ProgramResponses';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->ProgramResponse->Program->ProgramField->recursive = -1;
		if(!empty($this->params['pass'][0])) {
			$query = $this->ProgramResponse->Program->ProgramField->findAllByProgramId($this->params['pass'][0]);			
			$fields = Set::classicExtract($query, '{n}.ProgramField');
			foreach($fields as $k => $v) {
				if(!empty($v['validation'])) 
					$validate[$v['name']] = json_decode($v['validation'], true); 
			}
			$this->ProgramResponse->modifyValidate($validate);
		}
	}	
	
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		} 
		if(!empty($this->data)) {
			$this->ProgramResponse->create();
			$this->data['ProgramResponse']['answers'] = json_encode($this->data['ProgramResponse']);
			$this->data['ProgramResponse']['program_id'] = $id;
			if($this->ProgramResponse->save($this->data)) {
				$this->Session->setFlash(__('Saved', true), 'flash_success');
			}
			else {
				$this->Session->setFlash(__('Unable to save', true), 'flash_failure');
			}			
		}
		$program = $this->ProgramResponse->Program->findById($id);
		$title_for_layout = $program['Program']['name'] . ' Registration Form' ;
		$this->set(compact('program', 'title_for_layout'));	
	}
}