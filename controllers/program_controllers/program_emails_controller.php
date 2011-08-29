<?php
class ProgramEmailsController extends AppController {

	var $name = 'ProgramEmails';

	function admin_index($id=null) {
		if(!$id){
			$this->Session->setFlash(__('Invalid program id.', true), 'flash_failure');
			$this->redirect(array(
				'controller' => 'programs', 
				'action' => 'index', 
				'admin' => true));
		}
		if($this->RequestHandler->isAjax()) {
			$this->ProgramEmail->recursive = -1;
			if($id) {
				$this->ProgramEmail->Behaviors->disable('Disableable');
				$emails = $this->ProgramEmail->find('all', array(
					'conditions' => array(
						'ProgramEmail.program_id' => $id
					)));			
				if($emails) {
					$i = 0;
					foreach ($emails as $email) {
						$data['emails'][$i] = $email['ProgramEmail'];
						if($email['ProgramEmail']['disabled'] == 0) {
							$data['emails'][$i]['disabled'] = 'No';	
						}
						else {
							$data['emails'][$i]['disabled'] = 'Yes';	
						}
						$i++;	
					}
				}					
			}
			else {
				$data['message'] = 'Invalid program id.';
				$data['success'] = false;
			}
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}
		$title_for_layout = 'Program Emails';
		$this->set(compact('title_for_layout'));
	}

	function admin_edit() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				if($this->ProgramEmail->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Email saved sucessfully.';	
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to save email.';					
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to save email.';				
			}
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');				
		}
	}
	
	function admin_toggle_disabled($id, $disabled) {
		if($this->RequestHandler->isAjax()) {
			if($this->ProgramEmail->toggleDisabled($id, $disabled)) {
				$data['success'] = true;
				if($disabled == 1) {
					$data['message'] = 'Email disabled successfully.';
				}
				else {
					$data['message'] = 'Email enabled successfully.';
				} 
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unbale to save, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
}