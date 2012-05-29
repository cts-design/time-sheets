<?php

class ProgramsController extends AppController {

	public $name = 'Programs';
	public $components = array('Email');

	public function beforeFilter() {
		parent::beforeFilter();
		if($this->Auth->user() && ($this->Auth->user('email') == null
			|| preg_match('(none|nobody|noreply)', $this->Auth->user('email')))) {
				$this->Session->setFlash(__('Please complete your profile to continue.', true), 'flash_success');
				$this->redirect(array('controller' => 'users', 'action' => 'edit', $this->Auth->user('id')));
		}
	}

	public function registration($id=null) {
		$this->loadProgram($id);
	}

	public function orientation($id=null) {
		$this->loadProgram($id);
	}

	public function ecourse() {
		//ecouse logic here
	}

	public function esign() {
		// code...
	}

	public function enrollment() {
		// code...
	}

	public function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$this->Program->contain('ProgramEmail', 'ProgramInstruction');
			$programs = $this->Program->find('all');
			if($programs) {
				$i = 0;
				foreach($programs as $program){
					$data['programs'][$i] = array(
						'id' => $program['Program']['id'],
						'name' => $program['Program']['name']);
						$data['programs'][$i]['actions'] = '<a href="/admin/program_responses/index/'.
							$program['Program']['id'].'">View Responses</a> |
							<a class="edit" href="/admin/program_instructions/index/'.
							$program['Program']['id'].'">Edit Instructions</a>';
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

	private function loadProgram($id) {
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
		if($program['Program']['type'] != $this->params['action']) {
			$this->Session->setFlash(__('This program id does not match the program type specified in the url.', true), 'flash_failure');
			$this->redirect('/');
		}
		$programResponse = $this->Program->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
		if(!$programResponse) {
			if($program) {
				$this->data['ProgramResponse']['user_id'] = $this->Auth->user('id');
				$this->data['ProgramResponse']['program_id'] = $id;
				$string = sha1(date('ymdhisu'));
				$this->data['ProgramResponse']['confirmation_id'] =
					substr($string, 0, $program['Program']['confirmation_id_length']);
				$this->data['ProgramResponse']['expires_on'] =
					date('Y-m-d H:i:s', strtotime('+' . $program['Program']['response_expires_in'] . ' days'));
				if($this->Program->ProgramResponse->save($this->data)) {
					$this->Transaction->createUserTransaction('Programs', null, null,
					'Initiated program ' . $program['Program']['name']);
					$programResponse = $this->Program->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
				}
			}
		}
		if($programResponse) {
			$data['completedSteps'] = Set::extract('/ProgramResponseActivity[status=complete]/program_step_id', $programResponse);
			if($programResponse['ProgramResponse']['status'] === 'incomplete') {
				$instructions = Set::extract('/ProgramInstruction[type=main]/text', $program);
			}
			else {
				$instructions = Set::extract('/ProgramInstruction[type='.$programResponse['ProgramResponse']['status'].']/text', $program);
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
}

