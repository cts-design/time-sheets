<?php
// TODO - Remove chromephp
App::import('Vendor', 'chromephp/chromephp');

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
		if ($this->RequestHandler->isAjax()) {
			$this->Program->contain('ProgramEmail', 'ProgramInstruction');
			$programs = $this->Program->find('all');

			if ($programs) {
				$i = 0;
				foreach ($programs as $program) {
					$data['programs'][$i] = array(
						'id' => $program['Program']['id'],
						'name' => $program['Program']['name'],
						'type' => $program['Program']['type'],
						'disabled' => $program['Program']['disabled']
					);

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

	public function admin_read() {
		FireCake::log($this->params);
		$programId = $this->params['url']['program_id'];

		$program = $this->Program->find('first', array(
			'conditions' => array(
				'Program.id' => $programId
			)
		));

		if ($program) {
			$data['success'] = true;
			$data['programs'] = $program['Program'];
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}

	public function admin_edit($programType, $id) {
		if (!$programType || !$id) {
			$this->Session->setFlash(__('Invalid Program', true), 'flash_failure');
			$this->redirect(array(
				'controller' => 'programs',
				'action' => 'index'
			));
		}

		$this->Program->recursive = -1;
		$program = $this->Program->find('first', array(
			'conditions' => array(
				'id'   => $id,
				'type' => $programType
			)
		));

		if (!$program) {
			$this->Session->setFlash(__('Invalid Program', true), 'flash_failure');
			$this->redirect(array(
				'controller' => 'programs',
				'action' => 'index'
			));
		} else {
			$programName = $program['Program']['name'];
		}

		$title_for_layout = 'Edit Program';
		$this->set(compact('title_for_layout', 'id', 'programName', 'programType'));
	}

	private function loadProgram($id) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		$program = $this->Program->find('first', array(
			'conditions' => array('Program.id' => $id),
			'contain' => array(
				'ProgramStep' => array('conditions' => array('ProgramStep.parent_id IS NOT NULL'),
									   'order' => array('lft' => 'ASC')),
				'ProgramInstruction')));
		if($program['Program']['disabled']) {
			$this->Session->setFlash(__('This program is disabled', true), 'flash_failure');
			$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
		}
		if($program['Program']['type'] != $this->params['action']) {
			$this->Session->setFlash(__('This program id does not match the program type specified in the url.', true), 'flash_failure');
			$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
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

	public function admin_update() {
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

	public function admin_create_orientation() {
		if ($this->RequestHandler->isAjax()) {
			$programData = json_decode($this->params['form']['programs'], true);
            $programMedia = json_decode($this->params['form']['media'], true);
			unset($programData['id'], $programData['created'], $programData['modified']);

			$this->data['Program'] = $programData;

			$this->Program->create();
			$program = $this->Program->save($this->data);

			if ($this->Program->save($program)) {
				$programId = $this->Program->id;
				$data['programs'] = $program['Program'];
				$data['programs']['id'] = $programId;

				$this->createOrientationProgramSteps($programId, $program, $programMedia);

				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_upload_media() {
		$this->layout = 'ajax';
		$storagePath = substr(APP, 0, -1) . Configure::read('Program.media.path');
		$publicPath = WWW_ROOT . 'files/public/programs/';

		switch ($_FILES['media']['type']) {
			case 'application/pdf':
				$path = $storagePath;
				$ext = '.pdf';
				break;

			case 'video/x-flv':
				$path = $storagePath;
				$ext = '.flv';
				break;

			case 'application/x-shockwave-flash':
				$path = $publicPath;
				$ext = '.swf';
				break;

			default:
				break;
		}

		$filename = date('YmdHis') . $ext;

		if (!is_dir($path)) {
			mkdir($path);
		}

		if (!file_exists($path . $filename)) {
			$url = $path . $filename;
			if (!move_uploaded_file($_FILES['media']['tmp_name'], $url)) {
				$data['success'] = false;
			} else {
				$data['success'] = true;
				$data['url'] = $filename;
			}
		}

		$this->set(compact('data'));
		return $this->render(null, null, '/elements/ajaxreturn');
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

		return true;
	}

	private function createOrientationProgramSteps($programId, $program, $programMedia) {
		FireCake::log($programId);
		FireCake::log($program);
		FireCake::log($programMedia);
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
					array(
						'program_id' => $programId,
						'parent_id' => $parentId,
						'name' => "{$program['Program']['name']} Orientation " . Inflector::humanize($programMedia['type']),
						'type' => 'media',
						'media_location' => $programMedia['location'],
						'redoable' => 1,
						'media_type' => $programMedia['type']
					),
					array(
						'program_id' => $programId,
						'parent_id' => $parentId,
						'name' => "{$program['Program']['name']} Orientation Quiz",
						'type' => 'form'
					)
				)
			);

			$formStep = $this->Program->ProgramStep->saveAll($formStepData['ProgramStep']);

			if ($formStep) {
				$data['Programs']['program_steps'] = $formStep['ProgramStep'];
			}
		}

		return true;
	}
}

