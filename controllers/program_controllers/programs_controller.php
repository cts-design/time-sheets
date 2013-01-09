<?php

class ProgramsController extends AppController {

	public $name = 'Programs';
	public $components = array('Email');
	public $transactionIds = array();

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
					$mainEmail = Set::extract('/ProgramEmail[type=main]', $program);
					if(!empty($mainEmail)) {
						$this->Notifications->sendProgramEmail($mainEmail[0]['ProgramEmail']);
					}
				}
			}
		}
		if($programResponse) {
			if($program['Program']['rolling_expiration']) {
				$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
				$this->data['ProgramResponse']['expires_on'] =
					date('Y-m-d H:i:s', strtotime('+' . $program['Program']['response_expires_in'] . ' days'));
				$this->Program->ProgramResponse->save($this->data);
			}
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
				} else if ($programResponse['ProgramResponse']['status'] === 'complete') {
					$results = array();
					$pattern = '/\{\{\s*(\w+)\s*\}\}/';

					preg_match_all('/\{\{\s*(\w+)\s*\}\}/', $instructions[0], $results);

					if (!empty($results[1])) {
						$formFieldName = $results[1];
						$formFieldAnswers = json_decode($programResponse['ProgramResponseActivity'][0]['answers'], true);
						$replacement = $formFieldAnswers[$formFieldName[0]];

						$instructions[0] = preg_replace($pattern, $replacement, $instructions[0]);
					}
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
		if(!empty($instructions)) {
			$data['instructions'] = $instructions[0];
		}
		$this->set($data);
	}

	public function admin_update() {
		if ($this->RequestHandler->isAjax()) {
			$programData = json_decode($this->params['form']['programs'], true);

			$this->Program->id = $programData['id'];
			unset($programData['id']);

			if (array_key_exists('paper_forms', $programData)) {
				if ($programData['paper_forms'] === null) {
					$programData['paper_forms'] = 0;
				}
			}

			if (array_key_exists('upload_docs', $programData)) {
				if ($programData['upload_docs'] === null) {
					$programData['upload_docs'] = 0;
				}
			}

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

	public function admin_duplicate() {
		$this->Program->Behaviors->detach('Disableable');
		$programId = $this->params['form']['program_id'];

		// retreive the program to be duplicated
		$this->Program->id = $programId;
		$this->Program->read();

		$duplicate = $this->Program->data;

		// remove the original id, created, and modified date
		unset(
			$duplicate['Program']['id'],
			$duplicate['Program']['program_response_count'],
			$duplicate['Program']['created'],
			$duplicate['Program']['modified']
		);

		// append copy to the duplicates name
		$duplicate['Program']['name'] = $duplicate['Program']['name'] . ' Copy';
		$duplicate['Program']['in_test'] = 1;
		$duplicate['Program']['disabled'] = 0;

		// create a new record for our duplicate
		$this->Program->create();

		if ($this->Program->save($duplicate)) {
			// add the program id to our transactionIds
			// in case we have to rollback further into the duplication
			$this->transactionIds['Program'][] = $newId = $this->Program->id;
			if ($this->duplicateProgramStep($newId, $programId)) {
				if ($this->duplicateProgramInstruction($newId, $programId)) {
					if ($this->duplicateProgramEmail($newId, $programId)) {
						if ($this->duplicateProgramDocument($newId, $programId)) {
							$success = true;
						} else {
							$this->duplicateTransactionCleanup();
							$success = false;
						}
					} else {
						$this->duplicateTransactionCleanup();
						$success = false;
					}
				} else {
					$this->duplicateTransactionCleanup();
					$success = false;
				}
			} else {
				$this->duplicateTransactionCleanup();
				$success = false;
			}
		}

		if (!$success) {
			$data['message'] = 'Unable to duplicate the program. Please try again.';
		}

		$data['success'] = $success;

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
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

	public function admin_update_esign() {
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
		$this->loadModel('DocumentFilingCategory');
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

		if ($download) {
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

			$parentFilingCat = $this->DocumentFilingCategory->getparentnode($watchedCat);

			$rejectedFilingCat = $this->DocumentFilingCategory->find('first', array(
				'conditions' => array(
					'DocumentFilingCategory.parent_id' => $parentFilingCat['DocumentFilingCategory']['id'],
					'DocumentFilingCategory.name' => 'Rejected'
				)
			));

			$rejectedWatchedFilingCat['WatchedFilingCat'] = array(
				'program_id' => $programId,
				'cat_id' => $rejectedFilingCat['DocumentFilingCategory']['id'],
				'name' => 'rejected'
			);

			$this->WatchedFilingCat->create();
			$this->WatchedFilingCat->save($rejectedWatchedFilingCat);

			$programDocument['ProgramDocument'] = $programDoc;
			$programDocument['ProgramDocument']['program_id'] = $programId;
			$programDocument['ProgramDocument']['program_step_id'] = $this->Program->ProgramStep->id;

			$this->Program->ProgramDocument->create();
			$this->Program->ProgramDocument->save($programDocument);
		}

		return true;
	}

	private function duplicateProgramStep($newProgramId, $oldProgramId, $stepId = null, $oldParentId = null, $newParentId = null) {
		$conditions = array(
			'ProgramStep.program_id' => $oldProgramId,
			'ProgramStep.parent_id'  => $oldParentId
		);

		if ($stepId) {
			$conditions['ProgramStep.id'] = $stepId;
		}

		$programStep = $this->Program->ProgramStep->find('all', array(
			'conditions' => $conditions
		));

		if (!$programStep) return false; // base case

		foreach ($programStep as $step) {
			$oldParentId = $step['ProgramStep']['id'];

			// duplicate program step
			$this->Program->ProgramStep->create();
			$newProgramStep = $step;

			// set new program id and remove fields to be reset
			$newProgramStep['ProgramStep']['program_id'] = $newProgramId;
			$newProgramStep['ProgramStep']['parent_id'] = $newParentId;

			unset(
				$newProgramStep['ProgramStep']['id'],
				$newProgramStep['ProgramStep']['lft'],
				$newProgramStep['ProgramStep']['rght'],
				$newProgramStep['ProgramStep']['created'],
				$newProgramStep['ProgramStep']['modified']
			);

			if ($this->Program->ProgramStep->save($newProgramStep)) {
				$this->transactionIds['ProgramStep'][] = $newParent = $this->Program->ProgramStep->id;

				// if there's a step type, that means it's not a module so let's look for associated
				// records that we'll need to replace their program_id's and new program_step_id's
				if ($this->issetAndNotEmpty($step['ProgramStep']['type'])) {
					if (!empty($step['ProgramInstruction']) && $this->issetAndNotEmpty($step['ProgramInstruction']['id'])) {
						$newRecord = $this->Program->ProgramInstruction->read(null, $step['ProgramInstruction']['id']);

						unset(
							$newRecord['ProgramInstruction']['id'],
							$newRecord['ProgramInstruction']['modified'],
							$newRecord['ProgramInstruction']['created']
						);

						$newRecord['ProgramInstruction']['program_id'] = $newProgramId;
						$newRecord['ProgramInstruction']['program_step_id'] = $newParent;

						$this->Program->ProgramInstruction->create();
						if ($this->Program->ProgramInstruction->save($newRecord)) {
							$this->transactionIds['ProgramInstruction'][] = $this->Program->ProgramInstruction->id;
						}
					}

					if (!empty($step['ProgramEmail']) && $this->issetAndNotEmpty($step['ProgramEmail']['id'])) {
						$newRecord = $this->Program->ProgramEmail->read(null, $step['ProgramEmail']['id']);

						unset(
							$newRecord['ProgramEmail']['id'],
							$newRecord['ProgramEmail']['modified'],
							$newRecord['ProgramEmail']['created']
						);

						$newRecord['ProgramEmail']['program_id'] = $newProgramId;
						$newRecord['ProgramEmail']['program_step_id'] = $newParent;

						$this->Program->ProgramEmail->create();
						if ($this->Program->ProgramEmail->save($newRecord)) {
							$this->transactionIds['ProgramEmail'][] = $this->Program->ProgramEmail->id;
						}
					}

					if (!empty($step['ProgramDocument']) && $this->issetAndNotEmpty($step['ProgramDocument'][0]['id'])) {
						$newRecord = $this->Program->ProgramDocument->read(null, $step['ProgramDocument'][0]['id']);

						unset(
							$newRecord['ProgramDocument']['id'],
							$newRecord['ProgramDocument']['modified'],
							$newRecord['ProgramDocument']['created']
						);

						$newRecord['ProgramDocument']['program_id'] = $newProgramId;
						$newRecord['ProgramDocument']['program_step_id'] = $newParent;

						$this->Program->ProgramDocument->create();
						if ($this->Program->ProgramDocument->save($newRecord)) {
							$this->transactionIds['ProgramDocument'][] = $this->Program->ProgramDocument->id;
						}
					}

					if (!empty($step['ProgramFormField']) && $this->issetAndNotEmpty($step['ProgramFormField'][0]['id'])) {
						for ($i = 0; $i < count($step['ProgramFormField']); $i++) {
							$newRecord = $this->Program->ProgramStep->ProgramFormField->read(null, $step['ProgramFormField'][$i]['id']);

							unset(
								$newRecord['ProgramFormField']['id'],
								$newRecord['ProgramFormField']['modified'],
								$newRecord['ProgramFormField']['created']
							);

							$newRecord['ProgramFormField']['program_id'] = $newProgramId;
							$newRecord['ProgramFormField']['program_step_id'] = $newParent;

							$this->Program->ProgramStep->ProgramFormField->create();
							if ($this->Program->ProgramStep->ProgramFormField->save($newRecord)) {
								$this->transactionIds['ProgramFormField'][] = $this->Program->ProgramStep->ProgramFormField->id;
							}
						}
					}
				}

				// check for children
				$children = $this->Program->ProgramStep->children($step['ProgramStep']['id']);
				foreach ($children as $child) {
					$this->duplicateProgramStep($newProgramId, $oldProgramId, $child['ProgramStep']['id'], $oldParentId, $newParent);
				}
			}
		}

		return true;
	}

	public function duplicateProgramInstruction($newProgramId, $oldProgramId) {
		$this->Program->ProgramInstruction->recursive = -1;
		$instructions = $this->Program->ProgramInstruction->find('all', array(
			'conditions' => array(
				'ProgramInstruction.program_id' => $oldProgramId,
				'ProgramInstruction.program_step_id' => null
			)
		));

		if ($instructions) {
			foreach ($instructions as $instruction) {
				$newRecord = $instruction;

				unset(
					$newRecord['ProgramInstruction']['id'],
					$newRecord['ProgramInstruction']['created'],
					$newRecord['ProgramInstruction']['modified']
				);

				$newRecord['ProgramInstruction']['program_id'] = $newProgramId;
				$this->Program->ProgramInstruction->create();

				if ($this->Program->ProgramInstruction->save($newRecord)) {
					$this->transactionIds['ProgramInstruction'][] = $this->Program->ProgramInstruction->id;
				} else {
					return false;
				}
			}
		}

		return true;
	}

	public function duplicateProgramEmail($newProgramId, $oldProgramId) {
		$this->Program->ProgramEmail->recursive = -1;
		$emails = $this->Program->ProgramEmail->find('all', array(
			'conditions' => array(
				'ProgramEmail.program_id' => $oldProgramId,
				'ProgramEmail.program_step_id' => null
			)
		));

		if ($emails) {
			foreach ($emails as $email) {
				$newRecord = $email;

				unset(
					$newRecord['ProgramEmail']['id'],
					$newRecord['ProgramEmail']['created'],
					$newRecord['ProgramEmail']['modified']
				);

				$newRecord['ProgramEmail']['program_id'] = $newProgramId;
				$this->Program->ProgramEmail->create();

				if ($this->Program->ProgramEmail->save($newRecord)) {
					$this->transactionIds['ProgramEmail'][] = $this->Program->ProgramEmail->id;
				} else {
					return false;
				}
			}
		}

		return true;
	}

	public function duplicateProgramDocument($newProgramId, $oldProgramId) {
		$this->Program->ProgramDocument->recursive = -1;
		$documents = $this->Program->ProgramDocument->find('all', array(
			'conditions' => array(
				'ProgramDocument.program_id' => $oldProgramId,
				'ProgramDocument.program_step_id' => 0
			)
		));

		if ($documents) {
			foreach ($documents as $document) {
				$newRecord = $document;

				unset(
					$newRecord['ProgramDocument']['id'],
					$newRecord['ProgramDocument']['created'],
					$newRecord['ProgramDocument']['modified']
				);

				$newRecord['ProgramDocument']['program_id'] = $newProgramId;
				$this->Program->ProgramDocument->create();

				if ($this->Program->ProgramDocument->save($newRecord)) {
					$this->transactionIds['ProgramDocument'][] = $this->Program->ProgramDocument->id;
				} else {
					return false;
				}
			}
		}

		return true;
	}

	private function duplicateTransactionCleanup() {
		foreach ($this->transactionIds as $key => $value) {
			// $key is the model name itself, $value is the array of ids
			switch ($key) {
			case 'Program':
				$Model =& $this->Program;
				break;
			case 'ProgramStep':
				$Model =& $this->Program->ProgramStep;
				break;
			case 'ProgramInstruction':
				$Model =& $this->Program->ProgramInstruction;
				break;
			case 'ProgramFormField':
				$Model =& $this->Program->ProgramStep->ProgramFormField;
				break;
			case 'ProgramEmail':
				$Model =& $this->Program->ProgramEmail;
				break;
			case 'ProgramDocument':
				$Model =& $this->Program->ProgramDocument;
				break;
			}

			// $v will be the id of the record saved
			foreach ($value as $k => $v) {
				$Model->delete($v);
			}
		}
	}

	private function issetAndNotEmpty($key) {
		return isset($key) && !empty($key);
	}
}

