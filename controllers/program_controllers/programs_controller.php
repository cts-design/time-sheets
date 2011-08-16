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
		// check if auth is required for the program, if not give access to index and view_media
		if($this->params['action'] == 'index' || $this->params['action'] == 'view_media'  
			|| $this->params['action'] == 'load_media'	&& isset($this->params['pass'][0])) {
				$program = $this->Program->findById($this->params['pass'][0]);
				if($program['Program']['auth_required'] == 0) {
					$this->Auth->allow('index', 'view_media', 'load_media');
				}
		}
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
				if($program['Program']['auth_required'] == 0) {
					$this->redirect(array(
						'controller' => 'programs', 
						'action' => 'view_media', $id, 'pdf'));
				}   			
				break;
			case "pdf_form":
				break;
			case "pdf_docs":
				break;
			case "pdf_form_docs":
				break;
			case "uri":
				if(!$program['Program']['auth_required']) {
					$this->redirect(array(
						'controller' => 'programs', 
						'action' => 'view_media', $id, 'uri'));
				} 				
				break;
			case "uri_form":
				break;					
			case "uri_docs":
				break;				
			case "uri_form_docs":
				break;								
			case "video":
				if(!$program['Program']['auth_required']) {
					$this->redirect(array(
						'controller' => 'programs', 
						'action' => 'view_media', $id, 'video'));
				}
				elseif($programResponse) {
					if(!$programResponse['ProgramResponse']['viewed_media']) {
						$this->Session->write('step2', 'complete');
						$this->redirect(array(
							'controller' => 'programs', 
							'action' => 'view_media', $id, 'video'));
					}
				}
				$data['redirect'] = '/programs/view_media/' . $program['Program']['id'] . '/' . 'video';  		
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
						$this->redirect(array(
							'controller' => 'programs', 
							'action' => 'view_media', $id, 'video'));
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
				$data['redirect'] = '/programs/view_media/' . $program['Program']['id']  . '/' . 'video';
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
			$this->Transaction->createUserTransaction('Programs', null, null,
				'Initiated program' . $program['Program']['name']);				
				$this->redirect($this->data['Program']['redirect']);
			}
		}
	}

	function view_media($id=null, $element=null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid program id.', true), 'flash_failure');
			$this->redirect('/');
		}
		if(!$element && empty($this->data)) {
			$this->Session->setFlash(__('Invalid media element.', true), 'flash_failure');
			$this->redirect('/');
		}
		$program = $this->Program->findById($id);
		if(!empty($this->data)) {
		$programResponse = $this->Program->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.user_id' => $this->Auth->user('id'),
			'ProgramResponse.program_id' => $id,
			'ProgramResponse.expires_on >= ' => date('Y-m-d H:i:s') 
		)));
			$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
			$this->data['ProgramResponse']['user_id'] =	$this->Auth->user('id');
			if($this->Program->ProgramResponse->save($this->data, true)) {
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Completed media for ' . $program['Program']['name']);	
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
		$data['acknowledgeMedia'] = true;
		if($program['Program']['auth_required'] == 0) {
			$data['acknowledgeMedia'] = false;
		}
		$instructions = Set::extract('/ProgramInstruction[type=media]/text', $program);		
		$data['element'] = '/programs/' . $element; 
		if(strstr($program['Program']['type'], 'uri')) {
			$data['media'] = $program['Program']['media'];
		}
		else {
			$data['media'] = '/programs/load_media/' . $program['Program']['id'];
		}
		if($instructions) {
			$data['instructions'] = $instructions[0];	
		}
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
		if($path) {
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
    }
				
	function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$programs = $this->Program->find('all');
			if($programs) {
				$i = 0;
				foreach($programs as $program){
					$data['programs'][$i] = array(
						'id' => $program['Program']['id'],
						'name' => $program['Program']['name']);
					if($program['Program']['auth_required']) {
						$data['programs'][$i]['actions'] = '<a href="/admin/program_responses/index/'.
							$program['Program']['id'].'">View Responses</a> | 
							<a class="edit" href="/admin/program_instructions/index/'.
							$program['Program']['id'].'">Edit Instructions</a>';						
					}
					else {
						$data['programs'][$i]['actions'] = '<a class="edit" href="/admin/program_instructions/'.
						'index/' . $program['Program']['id'].'">Edit Instructions</a>';
					}	
					$i++;			
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
	

		
}