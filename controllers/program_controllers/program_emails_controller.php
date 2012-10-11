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

	public function admin_create() {
			$formData = json_decode($this->params['form']['program_emails'], true);
			foreach ($formData as $key => $value) {
				unset($formData[$key]['id'], $formData[$key]['created'], $formData[$key]['modified']);
			}

			$count = count($formData);

			if ($count > 1) {
				$this->data['ProgramEmail'] = $formData;
				$formField = $this->ProgramEmail->saveAll($this->data['ProgramEmail']);
			} else {
				$this->data['ProgramEmail'] = $formData[0];
				$formField = $this->ProgramEmail->save($this->data);
			}

			$this->log($formField, 'debug');

			if ($formField) {
				if ($count > 1) {
					$this->ProgramEmail->recursive = -1;
					$newEmails = $this->ProgramEmail->find('all', array(
						'order' => 'ProgramEmail.id DESC',
						'limit' => 7
					));

					foreach ($newEmails as $k => $v) {
						$data['program_emails'][] = $v['ProgramEmail'];
					}
				} else {
					$data['program_emails'] = $formField['ProgramEmail'];
				}

				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_read() {
		$programId = $this->params['url']['program_id'];

		$this->ProgramEmail->recursive = -1;
		$emails = $this->ProgramEmail->find('all', array(
			'conditions' => array(
				'ProgramEmail.program_id' => $programId
			)
		));

		if ($emails) {
			$data['success'] = true;
			foreach ($emails as $key => $value) {
				$data['program_emails'][] = $value['ProgramEmail'];
			}
		} else {
			$data['success'] = false;
		}
	
		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_update() {
		$email = json_decode($this->params['form']['program_emails'], true);
		$count = count($email);
		$success = false;

		if ($count > 1) {
			foreach ($email as $k => $v) {
				$this->ProgramEmail->id = $v['id'];
				$this->ProgramEmail->set($v);
				$this->ProgramEmail->save();
			}
			$success = true;
		} else {
			$this->ProgramEmail->id = $email['id'];
			$this->ProgramEmail->set($email);

			if ($this->ProgramEmail->save()) {
				$success = true;
			}
		}

		$data['success'] = $success;

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	function admin_edit() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				if($this->ProgramEmail->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Email saved sucessfully.';
					$this->ProgramEmail->Behaviors->disable('Disableable');
					$this->data = $this->ProgramEmail->read(null, $this->data['ProgramEmail']['id']);
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Edited ' . $this->data['ProgramEmail']['name'] . 
						' email for ' . $this->data['Program']['name']);
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
			 	$this->ProgramEmail->Behaviors->disable('Disableable');
				$this->data = $this->ProgramEmail->read(null, $id);				
				$data['success'] = true;
				if($disabled == 1) {
					$data['message'] = 'Email disabled successfully.';
					$message = 'Disabled ' . $this->data['ProgramEmail']['name'] . 
					' email for ' . $this->data['Program']['name'];
					
				}
				else {
					$data['message'] = 'Email enabled successfully.';
					$message = 'Enabled ' . $this->data['ProgramEmail']['name'] . 
					' email for ' . $this->data['Program']['name'];					
				}
				$this->Transaction->createUserTransaction('Programs', null, null, $message);
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
