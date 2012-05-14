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
		if(isset($this->params['pass'][0]) &&
			in_array($this->params['action'], array('index', 'view_media', 'load_media'))) {
				$program = $this->Program->findById($this->params['pass'][0]);
				if($program['Program']['auth_required'] == 0) {
					$this->Auth->allow('index', 'view_media', 'load_media');
				}
			}
		if($this->Auth->user() && ($this->Auth->user('email') == null 
			|| preg_match('(none|nobody|noreply)', $this->Auth->user('email')))) {
				$this->Session->setFlash(__('Please complete your profile to continue.', true), 'flash_success');
				$this->redirect(array('controller' => 'users', 'action' => 'edit', $this->Auth->user('id')));
			}
	}

	function registration($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		$program = $this->Program->find('first', array(
			'conditions' => array('Program.id' => $id),
			'contain' => array(
				'ProgramStep' => array('conditions' => array('ProgramStep.parent_id IS NOT NULL')),
				'ProgramInstruction')));
		if($program['Program']['disabled']) {
			$this->Session->setFlash(__('This program is disabled', true), 'flash_failure');
			$this->redirect('/');
		}
		$programResponse = $this->Program->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
		if(!$programResponse) {
			if($program) {
				$this->data['ProgramResponse']['user_id'] = $this->Auth->user('id');
				$this->data['ProgramResponse']['next_step_id'] = $program['ProgramStep'][0]['id'];
				$this->data['ProgramResponse']['program_id'] = $id;
				if($program['Program']['confirmation_id_length']) {
					$string = sha1(date('ymdhisu'));
					$this->data['ProgramResponse']['confirmation_id'] =
						substr($string, 0, $program['Program']['confirmation_id_length']);
				}
				if($program['Program']['response_expires_in']) {
					$this->data['ProgramResponse']['expires_on'] =
						date('Y-m-d H:i:s', strtotime('+' . $program['Program']['response_expires_in'] . ' days'));
				}
				if($this->Program->ProgramResponse->save($this->data)){
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Initiated program ' . $program['Program']['name']);
					$programResponse = $this->Program->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
				}
			}
		}
		if($programResponse) {
			switch ($programResponse['ProgramResponse']['status']) {
			case 'incomplete':
				$instructions = Set::extract('/ProgramInstruction[type=main]/text', $program);
				break;
			case 'pending_approval':
				$instructions = Set::extract('/ProgramInstruction[type=pending_approval]/text', $program);
				break;
			case 'expired':
				$instructions = Set::extract('/ProgramInstruction[type=expired]/text', $program);
				break;
			case 'not_approved':
				$instructions = Set::extract('/ProgramInstruction[type=not_approved]/text', $program);
				break;
			case 'complete':
				$instructions = Set::extract('/ProgramInstruction[type=complete]/text', $program);
				break;
			default:
				$instructions = Set::extract('/ProgramInstruction[type=main]/text', $program);
				break;
			}
		}
		$data['title_for_layout'] = $program['Program']['name'] . ' Dashboard';
		$data['program'] = $program;
		$data['programResponse'] = $programResponse;
		if(isset($instructions)) {
			$data['instructions'] = $instructions[0];
		}
		$this->set($data);
	}

	function ecourse() {
		//ecouse logic here
	}

	function orientation() {
		// code...
	}

	function esign() {
		// code...
	}

	function enrollment() {
		// code...
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
			$programResponse =
				$this->Program->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
			$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
			$this->data['ProgramResponse']['user_id'] = $this->Auth->user('id');
			if($this->Session->read('step2') == 'complete') {
				$this->data['ProgramResponse']['complete'] = 1;
			}
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
						'action' => 'response_complete', $id, true));
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
		if(strstr($program['Program']['type'], 'uri') || strstr($program['Program']['type'], 'presenter') ) {
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
					if(!empty($program['ProgramEmail'])) {
						$data['programs'][$i]['actions'] .=
							' | <a class="edit" href="/admin/program_emails/index/'.
							$program['Program']['id'].'">Edit Emails</a>';
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
