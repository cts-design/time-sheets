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
	
	// TODO make these actions work with an index method and routes ?? 
	public function registration($id=null) {
		$this->loadProgram($id);
	}

	public function orientation($id=null) {
		$this->loadProgram($id);
	}

	public function ecourse() {
		//ecouse logic here
	}

	public function esign($id=null) {
		$this->loadProgram($id);
	}

	public function enrollment($id=null) {
		$this->loadProgram($id);
	}

	public function admin_index() {
		if ($this->RequestHandler->isAjax()) {
			$this->Program->Behaviors->disable('Disableable');
			$this->Program->contain('ProgramEmail', 'ProgramInstruction');

			$programType = $this->params['url']['program_type'];

			$programs = $this->Program->find('all', array(
				'conditions' => array(
					'Program.type' => $programType
				)
			));

			if ($programs) {
				$i = 0;
				foreach ($programs as $program) {
					$data['programs'][$i] = array(
						'id' => $program['Program']['id'],
						'name' => $program['Program']['name'],
						'type' => $program['Program']['type'],
						'disabled' => $program['Program']['disabled'],
						'show_in_dash' => $program['Program']['show_in_dash'],
						'in_test' => $program['Program']['in_test'],
						'program_response_count' => $program['Program']['program_response_count']
					);

					$i++;
				}
				$data['success'] = true;
			}
			else {
				$data['programs'] = array();
				$data['success'] = true;
			}
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}
		$title_for_layout = 'Programs';
		$roleId = $this->Auth->user('role_id');
		$this->set('roleId', $roleId);
		$this->set(compact('title_for_layout'));
	}

	public function admin_read() {
		$this->Program->Behaviors->detach('Disableable');
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
		$this->Program->Behaviors->detach('Disableable');
		$this->Program->recursive = -1;

		if (!$programType || !$id) {
			$this->Session->setFlash(__('Invalid Program', true), 'flash_failure');
			$this->redirect(array(
				'controller' => 'programs',
				'action' => 'index'
			));
		}

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
		$roleId = $this->Auth->user('role_id');
		$this->set('roleId', $roleId);
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
				'ProgramStep' => array(
					'order' => 'lft ASC'
				),
				'ProgramInstruction')));
		if($program['Program']['disabled']) {
			$this->Session->setFlash(__('This program is disabled', true), 'flash_failure');
			$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
		}
		if($program['Program']['type'] != $this->params['action']) {
			$this->Session->setFlash(__('This program id does not match the program type specified in the url.', true), 'flash_failure');
			$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
		}
		if($program['Program']['form_esign_required'] && !$this->Auth->user('signature')) {
			$esignId = $this->Program->field('id', array('Program.type' => 'esign'));
			$this->Session->setFlash(__('This program requires that you be enrolled in the e-sign program first', true), 'flash_failure');
			$this->redirect(array('controller' => 'programs', 'action' => 'esign', $esignId));
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
				if($programResponse['ProgramResponse']['status'] === 'not_approved' && $programResponse['ProgramResponse']['not_approved_comment']) {
					$instructions[0] .=
						'<div class="not-approved-comment"><b>Admin Comment:&nbsp;</b>' .
						$programResponse['ProgramResponse']['not_approved_comment'] . '</div>';
				}
			}
		}
		$data['title_for_layout'] = $program['Program']['name'] . ' Dashboard';
		$data['program'] = $program;
		$data['programResponse'] = $programResponse;
		
		$programSteps = null;
		$modules = Set::extract('/ProgramStep[type=/^$/i]/id', $program);
		foreach ($modules as $key => $value) {
			$programSteps[$value] = Set::extract('/ProgramStep[parent_id=' . $value . ']/id', $program);
		}

		$data['programSteps'] = $programSteps;
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

	public function admin_purge_test_data() {
		if ($this->RequestHandler->isAjax()) {
			$programId = $this->params['form']['program_id'];

			$programResponses = $this->Program->ProgramResponse->deleteAll(
				array(
					'ProgramResponse.program_id' => $programId
				)
			);

			$data['program_id'] = $programId;

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

	public function admin_create_enrollment() {
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

				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_update_enrollment() {
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

	public function admin_create_esign() {
		if ($this->RequestHandler->isAjax()) {
			$programData = json_decode($this->params['form']['programs'], true);
			$programDoc  = json_decode($this->params['form']['program_document'], true);

			unset($programData['id'], $programData['created'], $programData['modified']);

			$this->data['Program'] = $programData;

			$this->Program->create();
			$program = $this->Program->save($this->data);

			if ($this->Program->save($program)) {
				$programId = $this->Program->id;
				$data['programs'] = $program['Program'];
				$data['programs']['id'] = $programId;

				$this->createEsignProgramStep($programId, $programDoc);

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

		switch ($_FILES['media']['type']) {
			case 'application/pdf':
				$path = $storagePath;
				$ext = '.pdf';
				break;

			case 'video/x-flv':
				$path = $storagePath;
				$ext = '.flv';
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

	private function createEsignProgramStep($programId, $programDoc) {
		$this->loadModel('WatchedFilingCat');
		$this->Program->ProgramStep->create();

		$parentStep = array(
			'ProgramStep' => array(
				'program_id' => $programId
			)
		);

		$parent = $this->Program->ProgramStep->save($parentStep);

		$downloadStep = array(
			'ProgramStep' => array(
				'program_id' => $programId,
				'parent_id' => $this->Program->ProgramStep->id,
				'type' => 'form_download',
				'name' => 'Esign Download'
			)
		);

		$this->Program->ProgramStep->create();
		$download = $this->Program->ProgramStep->save($downloadStep);

		$this->log($download, 'debug');

		if ($download) {
			$this->log('in download', 'debug');
			if (isset($programDoc['cat_3'])) {
				$watchedCat = $programDoc['cat_3'];
			} else if (isset($programDoc['cat_2'])) {
				$watchedCat = $programDoc['cat_2'];
			} else {
				$watchedCat = $programDoc['cat_1'];
			}

			unset($programDoc['cat_1'], $programDoc['cat_2'], $programDoc['cat_3']);

			$watchedFilingCat['WatchedFilingCat'] = array(
				'program_id' => $programId,
				'cat_id' => $watchedCat,
				'name' => 'esign'
			);

			$this->WatchedFilingCat->create();
			$this->WatchedFilingCat->save($watchedFilingCat);

			$programDocument['ProgramDocument'] = $programDoc;
			$programDocument['ProgramDocument']['program_id'] = $programId;
			$programDocument['ProgramDocument']['program_step_id'] = $this->Program->ProgramStep->id;

			$this->Program->ProgramDocument->create();
			$this->Program->ProgramDocument->save($programDocument);
		}

		return true;
	}
}

