<?php

class ProgramsController extends AppController {
	
	var $name = 'Programs';
	var $components = array('Email');
	
	function beforeFilter() {
		parent::beforeFilter();
		$validate = array('viewed_media' => array(
			'rule' => array('comparison', '>', 0), 
			'message' => 'You must check the box to continue the online process. 
				If you do not completely understand the information please review the instructions
				at the top of this page.'));	
		$this->Program->ProgramResponse->modifyValidate($validate);
	}
			
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		$program = $this->Program->findById($id);
		
		$programResponse = $this->Program->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.user_id' => $this->Auth->user('id'),
			'ProgramResponse.program_id' => $id,
			'ProgramResponse.expires_on >= ' => date('Y-m-d H:i:s') 
		)));
		if($program['Program']['disabled'] == 1){
			$this->Session->setFlash(__('This program is disabled', true), 'flash_failure');
			$this->redirect('/');
		}
		switch($program['Program']['type']){
			case "docs": 
				break;
			case "form":
				break;
			case "form_docs": 
				break;
			case "pdf":
				break;
			case "pdf_form":
				break;
			case "pdf_docs":
				break;
			case "pdf_form_docs":
				break;
			case "uri":
				break;
			case "uri_form":
				break;					
			case "uri_docs":
				break;				
			case "uri_form_docs":
				break;								
			case "video": 
				$element = '/programs/video';
				break;
			case "video_form":
				$element = '/programs/video';
				break;
			case "video_docs":
				$element = '/programs/video';
				break;				
			case "video_form_docs":
				if($programResponse) {
					if($programResponse['ProgramResponse']['viewed_media'] == 0) {
						$this->redirect(array('controller' => 'programs', 'action' => 'view_media', $id));
					}					
					if($programResponse['ProgramResponse']['viewed_media'] == 1 && 
					$programResponse['ProgramResponse']['answers'] == null &&
					$programResponse['ProgramResponse']['complete'] != 1) {
						$this->redirect(array('controller' => 'program_responses', 'action' => 'index', $id));
					}
					if($programResponse['ProgramResponse']['viewed_media'] == 1 && 
					$programResponse['ProgramResponse']['answers'] != null &&
					$programResponse['ProgramResponse']['uploaded_docs'] != 1 &&
					$programResponse['ProgramResponse']['dropping_off_docs'] != 1 &&
					$programResponse['ProgramResponse']['complete'] != 1) {
							$this->redirect(array(
								'controller' => 'program_responses', 
								'action' => 'required_docs', $id));	
					}
					if($programResponse['ProgramResponse']['viewed_media'] == 1 && 
					$programResponse['ProgramResponse']['answers'] != null &&
					$programResponse['ProgramResponse']['dropping_off_docs'] != 1 &&
					$programResponse['ProgramResponse']['uploaded_docs'] == 1 &&
					$programResponse['ProgramResponse']['complete'] != 1
					) {
						$this->redirect(array(
							'controller' => 'program_responses', 
							'action' => 'provided_docs', $id, 'uploaded_docs'));	
					}
					if($programResponse['ProgramResponse']['viewed_media'] == 1 && 
					$programResponse['ProgramResponse']['answers'] != null &&
					$programResponse['ProgramResponse']['dropping_off_docs'] == 1 &&
					$programResponse['ProgramResponse']['uploaded_docs'] != 1 &&
					$programResponse['ProgramResponse']['complete'] != 1
					) {
						$this->redirect(array(
							'controller' => 'program_responses', 
							'action' => 'provided_docs', $id, 'dropping_off_docs'));	
					}
					if($programResponse['ProgramResponse']['complete']) {
						$this->redirect(array(
							'controller' => 'program_responses', 
							'action' => 'response_complete', $id));
					}		
				}
				$data['redirect'] = '/programs/view_media/' . $program['Program']['id'];
				$this->Session->write('step2', 'form');
				break;					
		}

		$data['title_for_layout'] = $program['Program']['name'];
		$data['program'] = $program;
		$instructions = Set::extract('/ProgramInstruction[type=main]/text', $program);
		if($instructions) {
			$data['instructions'] = $instructions[0];
		}
		
		$this->set($data);
	}

	function get_started() {
		if(!empty($this->data)) {
			$this->data['ProgramResponse']['user_id'] = $this->Auth->user('id');
			$program = $this->Program->findById($this->data['ProgramResponse']['program_id']);
			if($program) {
				$string = sha1(date('ymdhisu'));
				$this->data['ProgramResponse']['conformation_id'] = 
					substr($string, 0, $program['Program']['conformation_id_length']);				
			}
			$this->data['ProgramResponse']['expires_on'] = 
				date('Y-m-d H:i:s', strtotime('+' . $program['Program']['response_expires_in'] . ' days'));
			if($this->Program->ProgramResponse->save($this->data)){
				$this->redirect($this->data['Program']['redirect']);
			}
		}
	}

	function view_media($id=null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		if(!empty($this->data)) {
		$programResponse = $this->Program->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.user_id' => $this->Auth->user('id'),
			'ProgramResponse.program_id' => $id,
			'ProgramResponse.expires_on >= ' => date('Y-m-d H:i:s') 
		)));
			$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
			$this->data['ProgramResponse']['user_id'] =	$this->Auth->user('id');
			if($this->Program->ProgramResponse->save($this->data, true)) {							
				$email = $this->Program->ProgramEmail->find('first', array('conditions' => array(
					'ProgramEmail.program_id' => $id,
					'ProgramEmail.type' => 'media')));
				if($email) {
					$this->Email->to = $this->Auth->user('firstname') . ' ' . $this->Auth->user('lastname') .' <'. $this->Auth->user('email'). '>';
					$this->Email->from = Configure::read('System.email');
					$this->Email->subject = $email['ProgramEmail']['subject'];
					$this->Email->send($email['ProgramEmail']['body']);								
				}								
				$this->Session->setFlash(__('Saved', true), 'flash_success');
				switch($this->Session->read('step2')) {
					case "form":
						$this->redirect(array(
							'controller' => 'program_responses',
							'action' => 'index', $id));
						break;
					case "docs":
						$this->redirect(array(
							'controller' => 'program_responses',
							'action' => 'required_docs', $id));
						break;
					case "complete":
						$this->redirect(array(
							'controller' => 'program_responses',
							'action' => 'submission_recieved', $id));		
						break;
				}
			}
			else {
				$this->Session->setFlash(__('You must check the I acknowledge box.', true), 'flash_failure');		
			}
		}		
		$program = $this->Program->findById($id);
		$instructions = Set::extract('/ProgramInstruction[type=media]/text', $program);		
		$data['element'] = '/programs/video'; 
		$data['media'] = '/programs/load_media/' . $program['Program']['id'];
		$data['instructions'] = $instructions[0];
		$data['title_for_layout'] = $program['Program']['name'];
		$this->set($data);		
	}

	function load_media($id=null) {
		if(!$id){
			$this->Session->setFlash(__('Invalid id', true), 'flash_failure');
			$this->redirect($this->referer());	
		} 
		$this->view = 'Media';
		$this->Program->id = $id;
		$path = $this->Program->field('media');		
		$explode = explode('.', $path);
		$params = array(
		    'id' => $path,
		    'name' => $explode[0],
		    'extension' => $explode[1],
		    'path' => Configure::read('Program.media.path')
		);
		$this->set($params);
		return $params;
    }
				
	function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$programs = $this->Program->find('all');
			if($programs) {
				foreach($programs as $program){
					$data['programs'][] = array(
						'id' => $program['Program']['id'],
						'name' => $program['Program']['name'],
						'actions' => '<a href="/admin/program_responses/index/'.
							$program['Program']['id'].'">View Responses</a> | 
							<a class="edit" href="/admin/programs/instructions_index/'.
							$program['Program']['id'].'">Edit Instructions</a>');
				}
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
				$data['message'] = 'No programs were found.';
			} 		
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');	
		}	
		$title_for_layout = 'Programs';		
		$this->set(compact('title_for_layout'));
	}
	
	function admin_instructions_index() {
		$title_for_layout = 'Program Instructions';
		$this->set(compact('title_for_layout'));
	}
	
	function admin_edit_instructions($id) {
		if(!$id && empty($this->data)){
		    $this->Session->setFlash(__('Invalid instructions', true), 'flash_failure');
		    $this->redirect(array('action' => 'instructions_index', 'admin' => true));
		}
		if(!empty($this->data)) {
			if($this->Program->ProgramInstruction->save($this->data)) {
			    $this->Session->setFlash(__('Instructions updated successfully', true), 'flash_success');
			    $this->redirect(array('action' => 'instructions_index', 'admin' => true));				
			}
			else {
			    $this->Session->setFlash(__('Instructions updated successfully', true), 'flash_failure');
			    $this->redirect(array(
			    	'action' => 'edit_instructions', 
			    	$this->data['ProgramInstruction']['id'], 
			    	'admin' => true));					
			}
		}
		if(empty($this->data)) {
			$this->data = $this->Program->ProgramInstruction->read(null, $id);
		}
		$this->set('title_for_layout', 'Edit ' . ucfirst($this->data['ProgramInstruction']['type']) . ' Program Instructions');
	}
		
}