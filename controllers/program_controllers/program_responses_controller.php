<?php
App::import('Vendor', 'wkhtmltopdf/wkhtmltopdf');

class ProgramResponsesController extends AppController {

	public $name = 'ProgramResponses';

	public $components = array('Notifications');

	public $helpers = array('Excel');

	private $currentStep = null;
	private $nextStep = null;

	function beforeFilter() {
		parent::beforeFilter();
		$this->ProgramResponse->Program->ProgramStep->ProgramFormField->recursive = 2;
		if($this->params['action'] === 'media') {
			$mediaValidate = array('viewed_media' => array(
            'rule' => array('comparison', '>', 0),
            'message' => 'You must check the box to continue the online process.
                If you do not completely understand the information please review the instructions
                at the top of this page.'));
			$this->ProgramResponse->modifyValidate($mediaValidate);
		}
		if(!empty($this->params['pass'][1]) && ($this->params['action'] === 'form' || $this->params['action'] === 'edit_form')){
			$query = $this->ProgramResponse->Program->ProgramStep->ProgramFormField->findAllByProgramStepId($this->params['pass'][1]);
			if($query){
				$fields = Set::classicExtract($query, '{n}.ProgramFormField');
				foreach($fields as $k => $v) {
					if(!empty($v['validation'])) {
						$validate[$v['name']] = json_decode($v['validation'], true);
					}
				}
				if($query[0]['ProgramStep']['Program']['user_acceptance_required']) {
					$validate['user_acceptance'] = array(
						'rule' => 'notempty',
						'message' => 'You must put your first & last name in the box.');
				}
				if($query[0]['ProgramStep']['Program']['form_esign_required']) {
					$validate['esign'] = array(
						'rule' => 'notempty',
						'message' => 'You must put your first & last name in the box.');
				}
				if(isset($validate)) {
					$this->ProgramResponse->ProgramResponseActivity->modifyValidate($validate);
				}
			}
		}
		// check if the logged in user has permission to approve responses
		// if they do we allow them access to other actions relating to approval

		$this->Auth->allow('admin_regenerate_docs');
		if($this->Acl->check(array(
			'model' => 'User',
			'foreign_key' => $this->Auth->user('id')), 'ProgramResponses/admin_approve', '*')) {
				$this->Auth->allow(
					'admin_not_approved',
					'admin_reset_form',
					'admin_allow_new_response',
					'admin_generate_form',
					'admin_get_form_activities');
			}
	}
	
	function form($programId=null, $stepId=null) {
        $program = $this->ProgramResponse->Program->getProgramAndResponse($programId, $this->Auth->user('id'));
		$this->whatsNext($program, $stepId);
		if(!empty($this->data)) {
            $this->data['ProgramResponse']['id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponse']['next_step_id'] = null;

			$this->data['ProgramResponseActivity'][0]['answers'] = json_encode($this->data['ProgramResponseActivity'][0]);
			$this->data['ProgramResponseActivity'][0]['status'] = 'complete';
			$this->data['ProgramResponseActivity'][0]['program_response_id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponseActivity'][0]['program_step_id'] = $this->currentStep[0]['id'];
			$this->data['ProgramResponseActivity'][0]['type'] = 'form';

			if(isset($this->nextStep)) {
				if($this->nextStep[0]['type'] === 'required_docs' || !$this->nextStep[0]['type'])
				{
					$redirect = array('controller' => 'programs', 'action' => $program['Program']['type'], $programId);
				}
				else if ($this->nextStep[0]['type'] === 'custom_form')
				{
					$redirect = array('controller' => 'program_responses', 'action' => 'form', $programId, $this->nextStep[0]['id']);
				}
				else {
					$redirect = array('action' => $this->nextStep[0]['type'], $programId, $this->nextStep[0]['id']);
				}

				$status = 'incomplete';
			}
			else {
				if($program['Program']['approval_required']) {
					$status = 'pending_approval';
				}
				else {
					$status = 'complete';
				}
				$this->data['ProgramResponse']['status'] = $status;
			}


			// TODO: make sure validation works
			if($this->ProgramResponse->saveAll($this->data)) {
				$program = $this->ProgramResponse->Program->getProgramAndResponse($programId, $this->Auth->user('id'));
				$this->programDocuments($program);	
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Completed ' .  $this->currentStep[0]['name'] . ' for program ' . $program['Program']['name']);

				$this->Notifications->sendProgramResponseStatusAlert($this->Auth->user(), $program, $status);

				$stepEmail = Set::extract('/ProgramEmail[program_step_id='.$stepId.']', $program);
				if(!empty($stepEmail)) {
					$this->Notifications->sendProgramEmail($stepEmail[0]['ProgramEmail']);
				}
				if(isset($status)) {
					$statusEmail = Set::extract('/ProgramEmail[type='.$status.']', $program);

					if ($status === 'complete') {
						$results = array();
						$pattern = '/\{\{\s*(\w+)\s*\}\}/';

						preg_match_all('/\{\{\s*(\w+)\s*\}\}/', $statusEmail[0]['ProgramEmail']['body'], $results);

						if (!empty($results[1])) {
							$formFieldName = $results[1];
							$formFieldAnswers = json_decode($program['ProgramResponse'][0]['ProgramResponseActivity'][0]['answers'], true);
							$replacement = $formFieldAnswers[$formFieldName[0]];

							$statusEmail[0]['ProgramEmail']['body'] = preg_replace($pattern, $replacement, $statusEmail[0]['ProgramEmail']['body']);
						}
					}

					if(!empty($statusEmail)) {
						$this->Notifications->sendProgramEmail($statusEmail[0]['ProgramEmail']);
					}
				}
				$this->Session->setFlash(__('Saved', true), 'flash_success');
				if(isset($redirect)) {
					$this->redirect($redirect);
				}
				else {
					$this->redirect(array('controller' => 'programs', 'action' => $program['Program']['type'], $programId));
				}
			}
		}
        $instructions = Set::extract('/ProgramInstruction[program_step_id='.$stepId.']/text', $program);
		$data['formFields'] = $this->currentStep[0]['ProgramFormField'];
		$data['element'] = 'form';
		if($this->currentStep[0]['type'] === 'custom_form') {
			$stepMeta = json_decode($this->currentStep[0]['meta'], true);
			$data['columns'] = $stepMeta['columns'];
			$data['formFields'] = array('ProgramFormField' => $this->currentStep[0]['ProgramFormField']);
			$data['element'] = 'custom_form_' . $this->currentStep[0]['id'];
		}
		
		// TODO: determine if esign will be on the program level or on the step level
		$data['esignRequired'] = false;
		if($program['Program']['form_esign_required']) {
			$data['esignRequired'] = true;
			$data['esignInstructions'] = Set::extract('/ProgramInstruction[type=esign]/text', $program);
		}
		$data['acceptanceRequired'] = false;
		if($program['Program']['user_acceptance_required']) {
			$data['acceptanceRequired'] = true;
			$data['acceptanceInstructions'] = Set::extract('/ProgramInstruction[type=acceptance]/text', $program);
		}
        if($instructions) {
            $data['instructions'] = $instructions[0];
        }
        $data['title_for_layout'] = $this->currentStep[0]['name'];

		$this->set($data);
		$this->render('form');
	}

	function edit_form($programId=null, $stepId=null) {
        $program = $this->ProgramResponse->Program->getProgramAndResponse($programId, $this->Auth->user('id'));
		$this->whatsNext($program, $stepId);
		$responseActivity = Set::extract('/ProgramResponseActivity[program_step_id=' . $stepId .']', $program['ProgramResponse'][0]);
		if(!empty($this->data)) {
            $this->data['ProgramResponse']['id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponseActivity'][0]['answers'] = json_encode($this->data['ProgramResponseActivity'][0]);
			$this->data['ProgramResponseActivity'][0]['id'] = $this->data['ProgramResponseActivity']['id'];
			unset($this->data['ProgramResponseActivity']['id']);
			$this->data['ProgramResponseActivity'][0]['status'] = 'complete';
			$this->data['ProgramResponseActivity'][0]['program_step_id'] = $this->currentStep[0]['id'];
			$this->data['ProgramResponseActivity'][0]['type'] = 'form';
			if(isset($this->nextStep)) {
				$this->data['ProgramResponse']['next_step_id'] = $this->nextStep[0]['id'];
				if($this->nextStep[0]['type'] === 'required_docs' || !$this->nextStep[0]['type']) {
					$redirect = array('controller' => 'programs', 'action' => $program['Program']['type'], $programId);
				}
				else {
					$redirect = array('action' => $this->nextStep[0]['type'], $programId, $this->nextStep[0]['id']);
				}
			}
			else {
				if($program['Program']['approval_required']) {
					$status = 'pending_approval';
				}
				else {
					$status = 'complete';
				}
				$this->data['ProgramResponse']['status'] = $status;
			}
			// TODO: make sure validation works
			if($this->ProgramResponse->saveAll($this->data)) {
				$program = $this->ProgramResponse->Program->getProgramAndResponse($programId, $this->Auth->user('id'));
				$this->programDocuments($program);	
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Completed ' .  $this->currentStep[0]['name'] . ' for program ' . $program['Program']['name']);
				$this->Session->setFlash(__('Saved', true), 'flash_success');

				$this->Notifications->sendProgramResponseStatusAlert($this->Auth->user(), $program, $status);

				$stepEmail = Set::extract('/ProgramEmail[program_step_id='.$stepId.']', $program);
				if(!empty($stepEmail)) {
					$this->Notifications->sendProgramEmail($stepEmail[0]['ProgramEmail']);
				}
				if(isset($status)) {
					$statusEmail = Set::extract('/ProgramEmail[type='.$status.']', $program);
					if(!empty($statusEmail)) {
						$this->Notifications->sendProgramEmail($statusEmail[0]['ProgramEmail']);
					}
				}
				if(isset($redirect)) {
					$this->redirect($redirect);
				}
				else {
					$this->redirect(array('controller' => 'programs', 'action' => $program['Program']['type'], $programId));
				}
			}
		}
		$data['element'] = 'form';
		if($this->currentStep[0]['ProgramStep']['type'] === 'custom_form') {
			$data['element'] = 'custom_form_' . $this->currentStep[0]['ProgramStep']['id'];
		}
        $instructions = Set::extract('/ProgramInstruction[program_step_id='.$stepId.']/text', $program);
		$data['formFields'] = $this->currentStep[0]['ProgramFormField'];
		// TODO: determine if esign will be on the program level or on the step level
		$data['esignRequired'] = false;
		if($program['Program']['form_esign_required']) {
			$data['esignRequired'] = true;
			$data['esignInstructions'] = Set::extract('/ProgramInstruction[type=esign]/text', $program);
		}
		$data['acceptanceRequired'] = false;
		if($program['Program']['user_acceptance_required']) {
			$data['acceptanceRequired'] = true;
			$data['acceptanceInstructions'] = Set::extract('/ProgramInstruction[type=acceptance]/text', $program);
		}
        if($instructions) {
            $data['instructions'] = $instructions[0];
        }
        $data['title_for_layout'] = $this->currentStep[0]['name'];
		if(empty($this->data['ProgramResponseActivity'])) {
			$this->data['ProgramResponseActivity'][0] = json_decode($responseActivity[0]['ProgramResponseActivity']['answers'], true);
			$this->data['ProgramResponseActivity']['id'] = $responseActivity[0]['ProgramResponseActivity']['id'];
		}
		$this->set($data);
	}

	function media($programId=null, $stepId=null) {
        $this->loadModel('ProgramStep');
        $program 		= $this->ProgramResponse->Program->getProgramAndResponse($programId, $this->Auth->user('id'));
        $current_step 	= $this->ProgramStep->findById($stepId);
        $children_media	= FALSE;
        $parent_media	= FALSE;

        if($current_step['ProgramStep']['type'] == 'media') //means this is a child
        {
        	$children_media = $this->ProgramStep->find('all', array(
				'conditions' => array(
					'ProgramStep.program_id' => $programId,
					'ProgramStep.parent_id' => $stepId,
					'ProgramStep.type' => 'alt_media'
				)
			));
        	$parent_media = $this->ProgramStep->findById($stepId);
			$this->whatsNext($program, $current_step['ProgramStep']['id']);
        }
        else
        {
        	$children_media = $this->ProgramStep->find('all', array(
				'conditions' => array(
					'ProgramStep.program_id' => $programId,
					'ProgramStep.parent_id' => $current_step['ProgramStep']['parent_id'],
					'ProgramStep.type' => 'alt_media'
				)
			));
			$parent_media = $this->ProgramStep->findById($current_step['ProgramStep']['parent_id']);
        	$this->whatsNext($program, $current_step['ProgramStep']['parent_id']); //Get's the step like usual
        }

        $this->set(compact('children_media', 'parent_media'));

        if(!empty($this->data)) {
            $this->data['ProgramResponse']['id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponse']['next_step_id'] = null;
			$this->data['ProgramResponseActivity'][0]['status'] = 'complete';
			$this->data['ProgramResponseActivity'][0]['program_response_id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponseActivity'][0]['program_step_id'] = $this->currentStep[0]['id'];
			$this->data['ProgramResponseActivity'][0]['type'] = 'media';

			if($program['Program']['approval_required'])
			{
				$status = 'pending_approval';
			}
			else
			{
				$status = 'complete';
			}
			if(isset($this->nextStep)) {
				$this->data['ProgramResponse']['next_step_id'] = $this->nextStep[0]['id'];
				if($this->nextStep[0]['type'] === 'required_docs' || !$this->nextStep[0]['type']) {
					$redirect = array('controller' => 'programs', 'action' => $program['Program']['type'], $programId);
				}
				else {
					$redirect = array('action' => $this->nextStep[0]['type'], $programId, $this->nextStep[0]['id']);
				}
			}
			else {
				$this->data['ProgramResponse']['status'] = $status;
			}
			// TODO: make sure validation works
            if($this->ProgramResponse->saveAll($this->data)) {
                $this->Transaction->createUserTransaction('Programs', null, null,
                    'Completed' . $this->currentStep[0]['name']);
                $this->Session->setFlash(__('Saved', true), 'flash_success');
				$stepEmail = Set::extract('/ProgramEmail[program_step_id='.$stepId.']', $program);

				$this->Notifications->sendProgramResponseStatusAlert($this->Auth->user(), $program, $status);

				if(!empty($stepEmail)) {
					$this->Notifications->sendProgramEmail($stepEmail[0]['ProgramEmail']);
				}
				if(isset($status)) {
					$statusEmail = Set::extract('/ProgramEmail[type='.$status.']', $program);
					if(!empty($statusEmail)) {
						$this->Notifications->sendProgramEmail($statusEmail[0]['ProgramEmail']);
					}
				}
				if(isset($redirect)) {
					$this->redirect($redirect);
				}
				else {
					$this->redirect(array('controller' => 'programs', 'action' => $program['Program']['type'], $programId));
				}
            }
            else {
                $this->Session->setFlash(__('You must check the I acknowledge box.', true), 'flash_failure');
            }
        }
        $data['acknowledgeMedia'] = true;
		// TODO: get instructions realated to current step
        $instructions = Set::extract('/ProgramInstruction[program_step_id='.$stepId.']/text', $program);
        $data['element'] = '/programs/' . $this->currentStep[0]['media_type'];
        if(strstr($this->currentStep[0]['media_type'], 'uri') || strstr($this->currentStep[0]['media_type'], 'presenter') ) {
            $data['media'] = $this->currentStep[0]['location'];
        }
        else {
            //$data['media'] = '/program_responses/load_media/' . $this->currentStep[0]['id'];
            $data['media'] = '/program_responses/load_media/' . $current_step['ProgramStep']['id'];
        }
        if($instructions) {
            $data['instructions'] = $instructions[0];
        }
        $data['title_for_layout'] = $this->currentStep[0]['name'];
		$data['media_acknowledgement_text'] = $program['Program']['media_acknowledgement_text'];
        $this->set($data);
    }

    function load_media($id=null) {
        if(!$id){
            $this->Session->setFlash(__('Invalid id', true), 'flash_failure');
            $this->redirect($this->referer());
        }
        $this->view = 'Media';
        $this->ProgramResponse->Program->ProgramStep->id = $id;
        $path = $this->ProgramResponse->Program->ProgramStep->field('media_location');

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
	
	function upload_docs($programId=null, $stepId=null) {
		$this->loadModel('Kiosk');

		$kiosk = $this->Kiosk->isKiosk();
		$this->set('is_kiosk', $kiosk);
		if($kiosk)
		{
			$this->set('locationId', $kiosk['Location']['id']);
			$this->set('queueCatId', null);
			$this->set('selfScanCatId', null);
		}

		if(!$programId){
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		}
        $program = $this->ProgramResponse->Program->getProgramAndResponse($programId, $this->Auth->user('id'));
		$this->whatsNext($program, $stepId);
		
		if(!empty($this->data)) {
			$this->loadModel('QueuedDocument');
			$this->data['QueuedDocument']['req_program_doc'] = 1;
			$this->QueuedDocument->set($this->data);

			if($this->QueuedDocument->validates()) {
				if($this->QueuedDocument->uploadDocument($this->data, 'Program Upload', $this->Auth->user('id'))) {
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Uploaded document for ' . $program['Program']['name']);
					$this->Session->setFlash(__('Document uploaded successfully.', true), 'flash_success');

					$success = "Thank you for uploading your document. If you have other documents to upload please do so now. If you are finished uploading documents, please click \"I am finished uploading my documents\"";
					$this->Session->setFlash(__($success, true), null, null, 'upload_success');
					$this->redirect(array('action' => 'upload_docs', $programId, $stepId));
				}
				else {
					$this->Session->setFlash(__('Unable to upload document, please try again', true), 'flash_failure');

					$invalid_fields = $this->QueuedDocument->invalidFields();

					$this->Session->setFlash( $invalid_fields['submittedfile'], null, null, 'upload_error' );
					$this->redirect(array('action' => 'upload_docs', $programId, $stepId));
				}
			}
			else {
				$this->Session->setFlash(__('Unable to upload document, please try again', true), 'flash_failure');

				$invalid_fields = $this->QueuedDocument->invalidFields();

				$this->Session->setFlash( $invalid_fields['submittedfile'], null, null, 'upload_error' );
				$this->validationErrors['QueuedDocument'] = $this->QueuedDocument->invalidFields();
			}

		}
		$instructions = Set::extract('/ProgramInstruction[type=upload_documents]/text', $program);
		if($instructions) {
			$data['instructions'] = $instructions[0];
		}
		$data['title_for_layout'] = $program['Program']['name'] . ' Upload Required Documentation';
		$data['queueCategoryId'] = $program['Program']['queue_category_id'];
		$this->set($data);
	}

	public function upload_docs_confirm()
	{
		//This view exists for after the scanned document is successfull
	}

	public function drop_off_docs($programId) {
		if(!$programId){
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		}
        $program = $this->ProgramResponse->Program->getProgramAndResponse($programId, $this->Auth->user('id'));
		$instructions = Set::extract('/ProgramInstruction[type=drop_off_documents]/text', $program);
		if($instructions) {
			$data['instructions'] = $instructions[0];
		}
		$data['title_for_layout'] = $program['Program']['name'] . '  Drop Off Required Documentation';
		$this->set($data);
	}

	function view_cert($id=null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		$programResponse = $this->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
		$docId = Set::extract('/ProgramResponseDoc[type=certificate]/doc_id', $programResponse);
		if(!$docId) {
			$this->Session->setFlash(__('Document has not been generated just yet. Please try again in a few minutes.', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		$this->view = 'Media';
		$this->loadModel('FiledDocument');
		if($docId) {
			$doc = $this->FiledDocument->read(null, $docId[0]);
			$params = array(
				'id' => $doc['FiledDocument']['filename'],
				'name' => str_replace('.pdf', '', $doc['FiledDocument']['filename']),
				'extension' => 'pdf',
				'cache' => true,
				'path' => Configure::read('Document.storage.path') .
				date('Y', strtotime($doc['FiledDocument']['created'])) . '/' .
				date('m', strtotime($doc['FiledDocument']['created'])) . '/'
			);
			$this->Transaction->createUserTransaction('Programs', null, null,
				'Viewed certificate for ' . $programResponse['Program']['name']);
			$this->set($params);
			return $params;
		}
	}

	function provided_docs($programId, $stepId, $type) {
		$programResponse = $this->ProgramResponse->getProgramResponse($programId, $this->Auth->user('id'));
		$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
		if($programResponse['ProgramResponse']['status'] === 'incomplete') {
			$this->data['ProgramResponse']['status'] = 'pending_document_review';
		}
		if($this->ProgramResponse->save($this->data)) {
			if($type === 'uploaded_docs') {
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Selected I am done uploading documents for ' . $programResponse['Program']['name']);
			}
			elseif($type === 'drop_off_docs') {
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Selected drop off documents for ' . $programResponse['Program']['name']);
			}
			$this->Session->setFlash(__('Required documentation step complete', true), 'flash_success');
			$this->redirect(array('controller' => 'programs', 'action' => 'enrollment', $programResponse['Program']['id']));
		}
		else {
			$this->Session->setFlash(__('Unable to complete required documentation step, please try again', true), 'flash_failure');
			$this->redirect($this->referer());
		}
	}

	function download_esign_form($programId) {
		$programResponse = $this->ProgramResponse->getProgramResponse($programId, $this->Auth->user('id'));
		$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
		if($programResponse['ProgramResponse']['status'] === 'incomplete') {
			$this->data['ProgramResponse']['status'] = 'pending_document_review';
		}
		$program = $this->ProgramResponse->Program->findById($programId);
		$this->loadModel('BarCodeDefinition');
		$barCodeDefintion = $this->BarCodeDefinition->findById($program['Program']['bar_code_definition_id']);
		$str_len = strlen($this->Auth->user('id'));
		$str_diff = (8-$str_len);
		$un_id = str_repeat('0',$str_diff) . $this->Auth->user('id');
		$barCode = '*'. $barCodeDefintion['BarCodeDefinition']['number'] . '-' . $un_id . '*';
		$decoded = $barCodeDefintion['BarCodeDefinition']['number'] . '-' . $un_id;
		$data = array();
		$data['User'] = $this->Session->read('Auth.User');
		$data['barcode'] = $barCode;
		$data['decoded'] = $decoded;
		$pdf = $this->generateForm($program['ProgramDocument'][0], $data);
			$params = array(
				'id' => $pdf,
				'name' => str_replace('.pdf', '', $pdf),
				'extension' => 'pdf',
				'cache' => true,
				'path' => Configure::read('Document.storage.path') .
					substr($pdf, 0, 4) . DS .
					substr($pdf, 4, 2) . DS
			);
			$this->ProgramResponse->save($this->data);
			$this->Transaction->createUserTransaction('Programs', null, null,
				'Downloaded e-signature enrollment form');
			$this->view = 'Media';
			$this->set($params);
			return $params;
	}

	function admin_index($id = null) {
		if($id){
			$this->ProgramResponse->Program->recursive = -1;
			$program = $this->ProgramResponse->Program->findById($id);
			if($this->RequestHandler->isAjax()){
				$conditions = array('ProgramResponse.program_id' => $id);
				if(!empty($this->params['url']['fromDate']) && !empty($this->params['url']['toDate'])) {
					$from = date('Y-m-d H:i:m', strtotime($this->params['url']['fromDate'] . '12:00 AM'));
					$to = date('Y-m-d H:i:m', strtotime($this->params['url']['toDate'] . '11:59 PM'));
					$conditions['ProgramResponse.created BETWEEN ? AND ?'] = array($from, $to);
				}
				if(!empty($this->params['url']['id'])) {
					$conditions['ProgramResponse.id'] = $this->params['url']['id'];
				}
				if(!empty($this->params['url']['searchType']) && !empty($this->params['url']['search'])) {
					switch($this->params['url']['searchType']) {
						case 'firstname' :
							$conditions['User.firstname LIKE'] = '%' .
								$this->params['url']['search'] . '%';
							break;
						case 'lastname' :
							$conditions['User.lastname LIKE'] = '%' .
								$this->params['url']['search'] . '%';
							break;
						case 'last4' :
							$conditions['RIGHT (User.ssn , 4) LIKE'] = '%' .
								$this->params['url']['search'] . '%';
							break;
						case 'fullssn' :
							$conditions['User.ssn LIKE'] = '%' . $this->params['url']['search'] . '%';
							break;
					}
				}
				if(!empty($this->params['url']['status'])) {
					if($this->params['url']['status'] === 'incomplete') {
						$conditions['ProgramResponse.status'] = array('incomplete', 'pending_document_review');
					}
					else {
						$conditions['ProgramResponse.status'] = $this->params['url']['status'];
					}
				}

				$data['totalCount'] = $this->ProgramResponse->find('count', array('conditions' => $conditions));

				$this->paginate = array('conditions' => $conditions);
				$responses =  $this->Paginate('ProgramResponse');
				if($responses) {
					$i = 0;
					foreach($responses as $response) {
						$data['responses'][$i] = array(
							'id' => $response['ProgramResponse']['id'],
							'User-lastname' => trim(ucwords($response['User']['lastname'] . ', ' .
								$response['User']['firstname']) . ' - ' .
								substr($response['User']['ssn'], -4), ' , -'),
							'confirmation_id' => $response['ProgramResponse']['confirmation_id'],
							'created' => $response['ProgramResponse']['created'],
							'modified' => $response['ProgramResponse']['modified'],
							'expires_on' => $response['ProgramResponse']['expires_on'],
							'notes' => $response['ProgramResponse']['notes'],
							'status' => $response['ProgramResponse']['status']
						);
						$statuses = array('complete', 'not_approved', 'pending_approval');
						if( in_array($this->params['url']['status'], $statuses)){
							$data['responses'][$i]['actions'] =
								'<a href="/admin/program_responses/view/'.
									$response['ProgramResponse']['id'].'">View</a>';
						}
						elseif($this->params['url']['status'] == 'expired'){
							$data['responses'][$i]['actions'] =
								'<a href="/admin/program_responses/view/'.
									$response['ProgramResponse']['id'].'">View</a> | ' .
									'<a href="/admin/program_responses/toggle_expired/' .
									$response['ProgramResponse']['id'] . '/unexpire'.'" class="expire">Mark Un-Expired</a>';
						}
						else {
							$data['responses'][$i]['actions'] =
								'<a href="/admin/program_responses/view/'.
									$response['ProgramResponse']['id'].'">View</a>';
							$data['responses'][$i]['actions'] .= ' | <a href="/admin/program_responses/toggle_expired/' .
								$response['ProgramResponse']['id'] . '/expire'.'" class="expire">Mark Expired</a>';
						}
						$i++;
					}
				}
				else {
					$data['responses'] = array();
					$data['message'] = 'No results at this time.';
				}
				$data['success'] = true;
				$this->set('data', $data);
				$this->render('/elements/ajaxreturn');
			}
			if($program['Program']['approval_required']){
				$approvalPermission = $this->Acl->check(array(
					'model' => 'User',
					'foreign_key' => $this->Auth->user('id')), 'ProgramResponses/admin_approve', '*');

			}
			else {
				$approvalPermission = null;
			}
			$programName = $program['Program']['name'];
			$this->set(compact('approvalPermission', 'programName', 'programType'));
		}
	
	}

	public function admin_report() {			
		$id = $this->params['url']['progId']; 	
		$title = 'Program Response Report ' . date('m/d/Y');
		$this->ProgramResponse->Program->recursive = -1;
		$program = $this->ProgramResponse->Program->findById($id);
		$conditions = array('ProgramResponse.program_id' => $id);
		if(!empty($this->params['url']['fromDate']) && !empty($this->params['url']['toDate'])) {
			$from = date('Y-m-d H:i:m', strtotime($this->params['url']['fromDate'] . '12:00 AM'));
			$to = date('Y-m-d H:i:m', strtotime($this->params['url']['toDate'] . '11:59 PM'));
			$conditions['ProgramResponse.created BETWEEN ? AND ?'] = array($from, $to);
			$title = 'Program Response Report from ' . $this->params['url']['fromDate'] . ' to ' . $this->params['url']['toDate'];
		}
		if(!empty($this->params['url']['id'])) {
			$conditions['ProgramResponse.id'] = $this->params['url']['id'];
		}
		if(!empty($this->params['url']['searchType']) && !empty($this->params['url']['search'])) {
			switch($this->params['url']['searchType']) {
				case 'firstname' :
					$conditions['User.firstname LIKE'] = '%' .
					$this->params['url']['search'] . '%';
					break;
				case 'lastname' :
					$conditions['User.lastname LIKE'] = '%' .
					$this->params['url']['search'] . '%';
					break;
				case 'last4' :
					$conditions['RIGHT (User.ssn , 4) LIKE'] = '%' .
					$this->params['url']['search'] . '%';
					break;
				case 'fullssn' :
					$conditions['User.ssn LIKE'] = '%' . $this->params['url']['search'] . '%';
					break;
			}
		}
		if(!empty($this->params['url']['status'])) {
			if($this->params['url']['status'] === 'incomplete') {
				$conditions['ProgramResponse.status'] = array('incomplete', 'pending_document_review');
			}
			else {
				$conditions['ProgramResponse.status'] = $this->params['url']['status'];
			}
		}
		$responses = $this->ProgramResponse->find('all', array('conditions' => $conditions));
		if(count($responses) > 3000) {
			$this->Session->setFlash('Cannot run excel report with more than 3000 records. Please filter your data further and try again.', 'flash_failure');
			$this->redirect($this->referer());
		}
		if($responses) {
			foreach($responses as $k => $v) {
				$report[$k]['Id'] = $v['ProgramResponse']['id'];
				$report[$k]['First Name'] = $v['User']['firstname'];
				$report[$k]['Middle Initial'] = $v['User']['middle_initial'];
				$report[$k]['Last Name'] = $v['User']['lastname'];
				$report[$k]['Sur Name'] = $v['User']['surname'];
				$report[$k]['Last 4 SSN'] = substr($v['User']['ssn'], -4);
				$report[$k]['Confirmation Id'] = $v['ProgramResponse']['confirmation_id'];
				$report[$k]['Address'] = $v['User']['address_1'];
				$report[$k]['City'] = $v['User']['city'];
				$report[$k]['County'] = $v['User']['county'];
				$report[$k]['State'] = $v['User']['state'];
				$report[$k]['Zip'] = $v['User']['zip'];
				$report[$k]['Phone'] = $v['User']['phone'];
				$report[$k]['Gender'] = $v['User']['gender'];
				$report[$k]['Dob'] = $v['User']['dob'];
				$report[$k]['Email'] = $v['User']['email'];
				$report[$k]['Language'] = $v['User']['language'];
				$report[$k]['Ethnicity'] = $v['User']['ethnicity'];
				$report[$k]['Race'] = $v['User']['race'];
                                if ( (!$v['User']['veteran']) || ($v['User']['veteran'] == '0') ) {
                                          $vet_response = "No";
                                        } else {
                                          $vet_response = "Yes";
                                        }
                                $report[$k]['Veteran'] = $vet_response;	
				
				$report[$k]['Status'] = ucfirst($v['ProgramResponse']['status']);
				$report[$k]['Created'] = date('m/d/Y g:i a', strtotime($v['ProgramResponse']['created']));
				$report[$k]['Modified'] = date('m/d/Y g:i a', strtotime($v['ProgramResponse']['modified']));
				$report[$k]['Expires On'] = date('m/d/Y g:i a', strtotime($v['ProgramResponse']['expires_on']));

				if(!empty($v['ProgramResponseActivity']) && isset($this->params['url']['includeData'])) {
					$i = 0;
					$activities = Set::sort($v['ProgramResponseActivity'], '{n}.id', 'asc');
					foreach($activities as $activity) {
						if(!empty($activity['answers'])) {
							$answers = json_decode($activity['answers'], true);
							foreach($answers as $key => $value) {
								if(array_key_exists($key, $report[$k])) {
									$key .= '_' . $i;
									$report[$k][$key] = $value;
								}
								else {
									$report[$k][$key] = $value;
								}
							}
							$i++;
						}
					}
				}
			}			
		}
		$this->Transaction->createUserTransaction('Programs', null, null, 'Created a program response Excel report');		
		if(empty($report[0])) {
		    $this->Session->setFlash(__('There are no results to generate a report', true), 'flash_failure');
		    $this->redirect($this->referer());
		}
		$data = array(
		    'data' => $report,
		    'title' => $title
		);
		if (isset($this->params['requested'])) {
		 	return $data;
		} 		
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$this->set($data);
		$this->render('/elements/excelreport');		
	}
	
	function admin_view($id, $type=null) {
		$this->ProgramResponse->contain(array(
			'Program' => array('ProgramStep'),
			'ProgramResponseActivity',
			'ProgramResponseDoc' => array('order' => array('created DESC')),
			'User'));
		$programResponse = $this->ProgramResponse->findById($id);
		if($this->RequestHandler->isAjax()){
			if($type == 'user') {
				$user = $programResponse['User'];
				$this->set(compact('user'));
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Viewed program response for ' . $programResponse['Program']['name'] . ' for customer ' .
					ucwords($user['name_last4']));
				$this->render('/elements/program_responses/user_info');
			}
			if($type == 'answers') {
				if (!empty($programResponse['ProgramResponse']['not_approved_comment'])) {
					$data['notApprovedComment'] = $programResponse['ProgramResponse']['not_approved_comment'];
				}

				$formActivities = Set::extract('/ProgramResponseActivity[type=form]', $programResponse);
				if(!empty($formActivities)) {
					$i = 0;
					foreach($formActivities as $formActivity) {
						$data['answers'][$i] = json_decode($formActivity['ProgramResponseActivity']['answers'], true);
						$data['stepName'][$i] =
							Set::extract('/ProgramStep[id='.$formActivity['ProgramResponseActivity']['program_step_id'].']/name', $programResponse['Program']);
						$i++;
					}
					$this->set($data);
				}
				$this->render('/elements/program_responses/answers');
			}
			if($type == 'documents') {
				if(!empty($programResponse['ProgramResponseDoc'])) {
					$this->loadModel('DocumentFilingCategory');
					$filingCatList = $this->DocumentFilingCategory->find('list');
					$docs = Set::extract('/ProgramResponseDoc[type=customer_provided]',  $programResponse);
					$generatedDocs = Set::extract('/ProgramResponseDoc[type!=customer_provided]',  $programResponse);
					$i = 0;
					foreach($docs as $doc) {
						$data['docs'][$i]['id'] = $doc['ProgramResponseDoc']['doc_id'];
						if($doc['ProgramResponseDoc']['deleted']) {
							$data['docs'][$i]['name'] = 'Deleted';
							$data['docs'][$i]['deletedReason'] = $doc['ProgramResponseDoc']['deleted_reason'];
							$data['docs'][$i]['deletedDate'] = $doc['ProgramResponseDoc']['modified'];
							$data['docs'][$i]['link'] = '<a href="/admin/deleted_documents/view/'.
								$doc['ProgramResponseDoc']['doc_id'] . '" target="_blank">View Doc</a>';
						}
						else {
							$data['docs'][$i]['name'] = $filingCatList[$doc['ProgramResponseDoc']['cat_id']];
							$data['docs'][$i]['filedDate'] = $doc['ProgramResponseDoc']['created'];
							if($doc['ProgramResponseDoc']['rejected_reason'])	{
								$data['docs'][$i]['rejectedReason'] = $doc['ProgramResponseDoc']['rejected_reason'];
							}

							$data['docs'][$i]['link'] = '<a href="/admin/filed_documents/view/'.
								$doc['ProgramResponseDoc']['doc_id'] . '" target="_blank">View Doc</a> |
									<a href="/admin/filed_documents/edit/'.
								$doc['ProgramResponseDoc']['doc_id'] . '">Edit Doc</a>';
						}
						$i++;
					}
				}
				else $data['docs'] = 'No program response documents filed for this user.';
				$programDocs = $this->ProgramResponse->
					Program->ProgramDocument->find('list', $programResponse['Program']['id']);
				if($programDocs) {
					$data['generatedDocs'] = array();
					if(isset($generatedDocs)) {
						foreach($generatedDocs as $generatedDoc) {
							if(!array_key_exists($generatedDoc['doc_id'], $data['generatedDocs'])) {
								$data['generatedDocs'][$generatedDoc['ProgramResponseDoc']['doc_id']]['doc_id'] =
									$generatedDoc['ProgramResponseDoc']['doc_id'];
								$data['generatedDocs'][$generatedDoc['ProgramResponseDoc']['doc_id']]['name'] =
									$programDocs[$generatedDoc['ProgramResponseDoc']['program_doc_id']];
								$data['generatedDocs'][$generatedDoc['ProgramResponseDoc']['doc_id']]['filed_on'] =
									$generatedDoc['ProgramResponseDoc']['created'];
								$data['generatedDocs'][$generatedDoc['ProgramResponseDoc']['doc_id']]['link'] =
									'<a href="/admin/filed_documents/view/' .
									$generatedDoc['ProgramResponseDoc']['doc_id'] . '" target="_blank">View Doc</a>';
							}
						}
					}
				}
				$this->set($data);
				$this->render('/elements/program_responses/documents');
			}
		}

		if($programResponse['Program']['approval_required'] &&
			 $programResponse['ProgramResponse']['status'] === 'pending_approval') {
				$approval = true;
		}
		else {
			$approval = 'false';
		}
		$programId = $programResponse['Program']['id'];
		$programName = $programResponse['Program']['name'];
		$programStatus = $programResponse['ProgramResponse']['status'];
		$title_for_layout = 'Program Response';
		$this->set(compact('title_for_layout', 'approval', 'programName', 'programId', 'programStatus'));
	}

	function admin_regenerate_docs($programResponseId = null) {
		if($this->RequestHandler->isAjax()) {
			if(!$programResponseId) {
				$data['success'] = false;
				$data['message'] = 'Invalid program response id.';
			}
			else {
				$programResponse = $this->ProgramResponse->findById($programResponseId);
				$programDocuments = $this->ProgramResponse->Program->ProgramDocument->find('all', 
					array('conditions' => array(
						'ProgramDocument.program_id' => $programResponse['Program']['id'],
						'ProgramDocument.type' => array('pdf', 'certificate'))));
				if(!empty($programDocuments)) {
					$this->ProgramResponse->Program->ProgramDocument->queueProgramDocs($programDocuments, $programResponse);
				}
				else {
					$data['success'] = false;
					$data['message'] = 'An error occured, please try again.';
				}

			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_edit() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				if($this->ProgramResponse->save($this->data)) {
					$programResponse = $this->ProgramResponse->read(null, $this->data['ProgramResponse']['id']);
					$data['success'] = true;
					$data['message'] = 'Notes were saved successfully.';
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Updated ' . $programResponse['Program']['name'] . ' response notes for customer ' .
						ucwords($programResponse['User']['name_last4']));
				}
				else {
					$data['success'] = true;
					$data['message'] = 'Unable to update notes at this time';
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_approve($programResponseId=null) {
		if($this->RequestHandler->isAjax()) {
			if(!$programResponseId) {
				$data['success'] = false;
				$data['message'] = 'Invalid program response id.';
			}
			else {
				$programResponse = $this->ProgramResponse->findById($programResponseId);
				$programDocuments = $this->ProgramResponse->Program->ProgramDocument->find('all', 
					array('conditions' => array(
						'ProgramDocument.program_id' => $programResponse['Program']['id'],
						'ProgramDocument.type' => array('pdf', 'certificate'))));
				if(!empty($programDocuments)) {
					$this->ProgramResponse->Program->ProgramDocument->queueProgramDocs($programDocuments, $programResponse);
				}
				$this->data['ProgramResponse']['id'] = $programResponseId;
				$this->data['ProgramResponse']['status'] = 'complete';
				if($this->ProgramResponse->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Program response was approved successfully.';
					$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first',
						array('conditions' => array(
							'ProgramEmail.program_id' => $programResponse['Program']['id'],
							'ProgramEmail.type' => 'complete'
					)));
					$user['User'] = $programResponse['User'];
					$this->Notifications->sendProgramEmail($programEmail['ProgramEmail'], $user);

					$this->Notifications->sendProgramResponseStatusAlert($user, $programResponse, 'complete');

					$this->Transaction->createUserTransaction('Programs', null, null,
						'Approved program response for ' . $programResponse['Program']['name'] . ' for customer ' .
						ucwords($user['User']['name_last4']));
				}
				else {
					$data['success'] = false;
					$data['message'] = 'An error occured, please try again.';
				}

			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_not_approved() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->params['form']['id'])) {
				$programResponse = $this->ProgramResponse->findById($this->params['form']['id']);
				if($programResponse['Program']['type'] === 'esign') {
					$this->ProgramResponse->User->id = $programResponse['ProgramResponse']['user_id'];
					$this->ProgramResponse->User->saveField('signature', 0);
					$this->ProgramResponse->User->saveField('signature_created', NULL);
					$this->ProgramResponse->User->saveField('signature_modified', date('Y-m-d H:i:s'));
				}
				$this->data['ProgramResponse']['id'] = $this->params['form']['id'];
				$this->data['ProgramResponse']['status'] = 'not_approved';
				if(isset($this->params['form']['reset_form'])) {
					$this->data['ProgramResponseActivity'][0]['id'] = $this->params['form']['reset_form'];
					$this->data['ProgramResponseActivity'][0]['status'] = 'allow_edit';
				}
				if(!empty($this->params['form']['comment'])) {
					$this->data['ProgramResponse']['not_approved_comment'] = $this->params['form']['comment'];
				}
				if($this->ProgramResponse->saveAll($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Program response marked not approved.';
					$programResponse = $this->ProgramResponse->findById($this->params['form']['id']);

					$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first',
						array('conditions' => array(
							'ProgramEmail.program_id' => $programResponse['Program']['id'],
							'ProgramEmail.type' => 'not_approved'
					)));
					$user['User'] = $programResponse['User'];
					if($programEmail) {
						if(!empty($this->params['form']['comment'])) {
							$programEmail['ProgramEmail']['body'] .= "\r\n\r\n\r\n" .
							'Comment: ' . $this->params['form']['comment'];
						}
						$this->Notifications->sendProgramEmail($programEmail['ProgramEmail'], $user);
					}
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Marked ' . $programResponse['Program']['name'] .
						' response not approved for ' . ucwords($user['User']['name_last4']));
					if(isset($this->params['form']['reset_form'])) {
						// TODO transaction for each form that was marked allow edit? or list all form that were reset in one transaction
						$this->Transaction->createUserTransaction('Programs', null, null,
							'Reset program response form for ' . $programResponse['Program']['name'] . ' for customer ' .
							ucwords($programResponse['User']['name_last4']));
					}
				}
				else {
					$data['success'] = false;
					$data['message'] = 'An error occurred, please try again.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Invalid response id';
			}

			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_generate_form($formId, $programResponseId, $docId=null) {
		if($this->RequestHandler->isAjax()) {
			$programResponse = $this->ProgramResponse->findById($programResponseId);
			if(strpos($programResponse['Program']['type'], 'docs')) {
				$allWatchedCats = $this->ProgramResponse->Program->WatchedFilingCat->find('all', array('conditions' => array(
					'WatchedFilingCat.program_id' => $programResponse['Program']['id'],
					'DocumentFilingCategory.name !=' => 'rejected',
					'DocumentFilingCategory.name !=' => 'Rejected')));
				$watchedCats = Set::classicExtract($allWatchedCats, '{n}.WatchedFilingCat.cat_id');
				$filedResponseDocCats = $this->ProgramResponse->ProgramResponseDoc->getFiledResponseDocCats(
					$programResponse['Program']['id'], $programResponse['ProgramResponse']['id']);
				$result = array_diff($watchedCats, $filedResponseDocCats);
				$generated = false;
				if(empty($result)) {
					$generated = $this->_generateForm($formId, $programResponseId, $docId);
				}
				else {
					$data['success'] = false;
					$data['message'] = 'All required documents must be filed before generating forms.';
				}
			}
			else {
				$generated = $this->_generateForm($formId, $programResponseId, $docId);
			}
			if($generated) {
				$data['success'] = true;
				$data['message'] = 'Form generated and filed successfully.';
				$this->Transaction->createUserTransaction('Programs', null, null,
					$generated[2] . ' ' . $generated[0]['ProgramPaperForm']['name'] . ' for ' .
					$generated[1]['Program']['name'] . ' for customer ' .
					ucwords($generated[1]['User']['name_last4']));
			}
			elseif(empty($data['message'])) {
				$data['success'] = false;
				$data['message'] = 'Unable to file form at this time.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_toggle_expired($programResponseId, $toggle) {
		if($this->RequestHandler->isAjax()) {
			$programResponse = $this->ProgramResponse->findById($programResponseId);
			if($toggle == 'expire') {
				$this->data['ProgramResponse']['expires_on'] =
					date('Y-m-d H:i:s', strtotime('-' . ($programResponse['Program']['response_expires_in']+1) . ' days'));
				$this->data['ProgramResponse']['status'] = 'expired';
			}
			elseif($toggle == 'unexpire') {
				$this->data['ProgramResponse']['expires_on'] = date('Y-m-d H:i:s',
					strtotime('+' . ($programResponse['Program']['response_expires_in']) . ' days'));
				$this->data['ProgramResponse']['status'] = 'incomplete';
			}
			$this->data['ProgramResponse']['id'] = $programResponseId;
			if($this->ProgramResponse->save($this->data)) {
				$data['success'] = true;
				switch($toggle) {
					case 'unexpire':
						$data['message'] = 'Response marked un-expired successfully.';
						$this->Transaction->createUserTransaction('Programs', null, null,
							'Marked response un-expired for ' . $programResponse['Program']['name'] . ' for customer ' .
							ucwords($programResponse['User']['name_last4']));
						break;
					case 'expire':
						$data['message'] = 'Response marked expired successfully.';
						$this->Transaction->createUserTransaction('Programs', null, null,
							'Marked response expired for ' . $programResponse['Program']['name'] . ' for customer ' .
							ucwords($programResponse['User']['name_last4']));
						break;
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'An error has occured, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_reset_form($id=null) {
		if($this->RequestHandler->isAjax()) {
			if(!$id){
				$data['success'] = false;
				$data['message'] = 'Invalid program response id';
			}
			else {
				$this->data['ProgramResponse']['id'] = $id;
				$this->data['ProgramResponse']['answers'] = null;
				if($this->ProgramResponse->save($this->data)) {
					$programResponse = $this->ProgramResponse->read(null, $id);
					$data['success'] = true;
					$data['message'] = 'Customer program response form reset successfully.';
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Reset program response form for ' . $programResponse['Program']['name'] . ' for customer ' .
						ucwords($programResponse['User']['name_last4']));
				}
				else {
					$data['success'] = false;
					$data['message'] = 'An error occurred, please try again.';
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_allow_new_response($id=null) {
		if($this->RequestHandler->isAjax()) {
			if(!$id){
				$data['success'] = false;
				$data['message'] = 'Invalid program response id';
			}
			else {
				$this->data['ProgramResponse']['id'] = $id;
				$this->data['ProgramResponse']['allow_new_response'] = 1;
				if($this->ProgramResponse->save($this->data)) {
					$programResponse = $this->ProgramResponse->read(null, $id);
					$data['success'] = true;
					$data['message'] = 'Customer can now create a new response for this program.';
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Enabled allow new response for ' . $programResponse['Program']['name'] . ' for customer ' .
						ucwords($programResponse['User']['name_last4']));
				}
				else {
					$data['success'] = false;
					$data['message'] = 'An error occurred, please try again.';
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_get_form_activities($id) {
		if($this->RequestHandler->isAjax()) {
			$this->ProgramResponse->contain(array('ProgramResponseActivity' => array(
				'conditions' => array('ProgramResponseActivity.type' => 'form'),
				'fields' => array('id', 'program_step_id'))));
			$response = $this->ProgramResponse->findById($id);
			if($response) {
				$steps = $this->ProgramResponse->Program->ProgramStep->find('list', array(
					'conditions' => array(
						'ProgramStep.type' => 'form',
						'ProgramStep.program_id' => $response['ProgramResponse']['program_id'])));
				$activities = $response['ProgramResponseActivity'];
			}
			if(isset($activities)) {
				$i = 0;
				foreach($activities as $activity) {
					$data['activities'][$i]['id'] = $activity['id'];
					$data['activities'][$i]['name'] = $steps[$activity['program_step_id']];
					$i++;
				}
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
				$data['activities'] = array();
			}
			$this->set(compact('data'));
			$this->render(null,null,'/elements/ajaxreturn');
		}
	}

	public function admin_get_next_pending_approval_response($programId, $programResponseId) {
		if($this->RequestHandler->isAjax()) {
			$response = $this->ProgramResponse->find('first', array('conditions' => array(
				'ProgramResponse.program_id' => $programId,
				'ProgramResponse.status' => 'pending_approval',
				'ProgramResponse.id !=' => $programResponseId)));	
			if($response) {
				$data['response'][]['id'] = $response['ProgramResponse']['id'];
			}
			else {
				$data['response'] = array();
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	private	function generateForm($form, $data) {
			$data['email'] = $data['User']['email'];
			foreach($data['User'] as $k => $v) {
				if(!preg_match('[\@]', $v)) {
					$data[$k] = ucwords($v);
				}
			}
			unset($data['User']);
			$data['dob'] = date('m/d/Y', strtotime($data['dob']));
			$data['date'] = date('m/d/Y');
			if($form) {
				$pdf = $this->createPDF($data, $form['template']);
				if($pdf) {
					return $pdf;	
				}
				else {
					return false;
				}
			}
	}

	private function createFDF($file,$info){
		$data="%FDF-1.2\n%����\n1 0 obj\n<< \n/FDF << /Fields [ ";
		foreach($info as $field => $val){
			if(is_array($val)){
				$data.='<</T('.$field.')/V[';
				foreach($val as $opt)
					$data.='('.trim($opt).')';
				$data.=']>>';
			}else{
				$data.='<</T('.$field.')/V('.trim($val).')>>';
			}
		}
		$data.="] \n/F (".$file.") /ID [ <".md5(time()).">\n] >>".
			" \n>> \nendobj\ntrailer\n".
			"<<\n/Root 1 0 R \n\n>>\n%%EOF\n";
		return $data;
	}

	private function createPDF($data, $template){
		$path = $this->getPath();
		// build our fancy unique filename
		$fdfFile = date('YmdHis') . rand(0, pow(10, 7)) . '.fdf';
		// pdf	file named the same as the fdf file
		$pdfFile = str_replace('.fdf', '.pdf', $fdfFile);

		// the temp location to write the fdf file to
		$fdfDir = TMP . 'fdf';

		// need to know what file the data will go into
		$pdfTemplate = APP . 'storage' . DS . 'program_forms' . DS . $template;

		// generate the file content
		$fdfData = $this->createFDF($pdfTemplate,$data);

		// write the file out
		if($fp=fopen($fdfDir.DS.$fdfFile,'w')){
			fwrite($fp,$fdfData,strlen($fdfData));
		}
		fclose($fp);

		$pdftkCommandString = DS . 'usr' . DS . 'bin' . DS . 'pdftk ' . APP . 'storage' . DS . 'program_forms' . DS .
			$template . ' fill_form ' . TMP . 'fdf' . DS . $fdfFile . ' output ' . $path . $pdfFile . ' flatten';
		passthru($pdftkCommandString, $return);

		if($return == 0) {
			// delete fdf if pdf was created and filed successfully
			unlink($fdfDir . DS . $fdfFile);
			return $pdfFile;
		}
		else return false;
	}
	
	private function getPath() {
		// get the document relative path to the inital storage folder
		$path = substr(APP, 0, -1) . Configure::read('Document.storage.path');
		// check to see if the directory for the current year exists
		if(!file_exists($path . date('Y') . DS)){
			// if directory does not exist, create it
			mkdir($path . date('Y'), 0755);
		}
		// add the current year to our path string
		$path .= date('Y') . DS;
		// check to see if the directory for the current month exists
		if(!file_exists($path . date('m') . DS)){
			// if directory does not exist, create it
			mkdir($path . date('m'), 0755);
		}
		// add the current month to our path string
		$path .= date('m') . DS;
		return $path;
	}

	private function whatsNext($program, $stepId) {
		 if(!$stepId) {
            $this->Session->setFlash(__('Invalid program step id.', true), 'flash_failure');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => false));
        }
		if(!$program) {
			$this->Session->setFlash(__('Invalid program.', true), 'flash_falure');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => false));
		}
		$steps = $this->ProgramResponse->Program->ProgramStep->getSteps($program, $stepId);
		if(!isset($steps['current'])) {
			$this->Session->setFlash(__('Unable to determine current step.', true), 'flash_failure');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => false));
		}
		$this->currentStep = $steps['current'];
		if(isset($steps['previous']) && $this->currentStep[0]['type'] != 'alt_media') {
			$this->Session->setFlash(__('Steps must be completed in order.', true), 'flash_failure');
			$previousStep = $steps['previous'];
			$this->redirect(array('action' => $previousStep[0]['type'], $program['Program']['id'], $previousStep[0]['id']));
		}
		elseif(isset($steps['next'])) {
			$this->nextStep = $steps['next'];
		}
	}

	private function programDocuments($program) {
		$user = $this->Auth->user();
		$program['User'] = $user['User'];
		if($program['Program']['type'] === 'enrollment') {
			$programDocument = Set::extract('/ProgramDocument[type=multi_snapshot]', $program);
			if($programDocument) {
				$formSteps = Set::extract('/ProgramStep[type=form]/id', $program);
				$formStepNames = array();
				foreach($program['ProgramStep'] as $step) {
					if(in_array($step['id'], $formSteps)) {
						$formStepNames[$step['id']] = $step['name'];
					}
				}
				$completedFormSteps = array();
				$formStepAnswers = array();
				$i = 0;
				foreach($program['ProgramResponse'][0]['ProgramResponseActivity'] as $activity) {
					if($activity['type'] === 'form' && $activity['status'] === 'complete') {
						$completedFormSteps[] = $activity['program_step_id'];
						$formStepAnswers[$i]['name'] = $formStepNames[$activity['program_step_id']];
						$formStepAnswers[$i]['answers'] = $activity['answers'];
						$i++;
					}
				}
				$result = array_diff($formSteps, $completedFormSteps);
				if(empty($result)) {
					if(!empty($programDocument) || !empty($formStepAnswers)) {
						unset($program['ProgramDocument']);
						$program['ProgramDocument'] = $programDocument[0]['ProgramDocument'];
						$this->ProgramResponse->Program->ProgramDocument->queueMultiSnapshot($program, $formStepAnswers);
					}
				}	
			}
		}
		elseif($program['Program']['type'] === 'orientation' || $program['Program']['type'] === 'registration') {
			$programDocuments = Set::extract('/ProgramDocument[program_step_id='.$this->currentStep[0]['id'].']', $program);
			if(!empty($programDocuments)) {
				$program['currentStep'] = $this->currentStep[0];
				unset($program['ProgramDocument']);
				$program['ProgramDocument'] = $programDocuments;
				$this->ProgramResponse->Program->ProgramDocument->queueProgramDocs($programDocuments, $program);
			}	
		}	
	}
}
