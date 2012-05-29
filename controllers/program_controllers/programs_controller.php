<?php
// TODO - Remove chromephp
App::import('Vendor', 'chromephp/chromephp');

class ProgramsController extends AppController {

	public $name = 'Programs';
	public $components = array('Email');

	public function beforeFilter() {
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

	public function orientation($id=null) {
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

	function enrollment() {
		// code...
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

	public function admin_create_registration() {
		if ($this->RequestHandler->isAjax()) {
			$programData = json_decode($this->params['form']['programs'], true);
			unset($programData['id'], $programData['created'], $programData['modified']);

			$this->data['Program'] = $programData;

			$this->Program->create();
			$program = $this->Program->save($this->data);

			if ($this->Program->save($program)) {
				$programId = $this->Program->id;
				$data['programs'] = $program['Program'];
				$data['programs']['id'] = $programId;

				$this->createRegistrationProgramSteps($programId, $program['Program']['name']);

				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_update_registration() {
		if ($this->RequestHandler->isAjax()) {
			$programData = json_decode($this->params['form']['programs'], true);

			$this->Program->id = $programData['id'];
			unset($programData['id']);

			$this->Program->set($programData);

			if ($this->Program->save()) {
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	private function createRegistrationProgramSteps($programId, $programName) {
		$this->Program->ProgramStep->create();

		$parentStepData = array(
			'ProgramStep' => array(
				'program_id' => $programId
			)
		);

		$parentStep = $this->Program->ProgramStep->save($parentStepData);

		if ($parentStep) {
			$parentId = $this->Program->ProgramStep->id;
			$this->Program->ProgramStep->create();
			$formStepData = array(
				'ProgramStep' => array(
					'program_id' => $programId,
					'parent_id' => $parentId,
					'name' => "$programName registration form",
					'type' => 'form'
				)
			);

			$formStep = $this->Program->ProgramStep->save($formStepData);

			if ($formStep) {
				$data['Programs']['program_steps'] = $formStep['ProgramStep'];
			}
		}

		//$this->createRegistrationProgramDocuments($programId);

		return true;
	}
}
