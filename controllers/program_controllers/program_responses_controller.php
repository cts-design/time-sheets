<?php
App::import('Vendor', 'wkhtmltopdf/wkhtmltopdf');

class ProgramResponsesController extends AppController {

	var $name = 'ProgramResponses';

	var $components = array('Notifications');

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
				// TODO: make this work with the real esign
				if($query[0]['ProgramStep']['Program']['form_esign_required']) {
					$validate['form_esignature'] = array(
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
        
		$programDocuments = Set::extract('/ProgramDocument[program_step_id='.$this->currentStep[0]['id'].']', $program);

		if(!empty($this->data)) {
            $this->data['ProgramResponse']['id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponse']['next_step_id'] = null;
			$this->data['ProgramResponseActivity'][0]['answers'] = json_encode($this->data['ProgramResponseActivity'][0]);
			$this->data['ProgramResponseActivity'][0]['status'] = 'complete';
			$this->data['ProgramResponseActivity'][0]['program_response_id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponseActivity'][0]['program_step_id'] = $this->currentStep[0]['id'];
			$this->data['ProgramResponseActivity'][0]['type'] = 'form';
			if(isset($nextStep)) {
				$this->data['ProgramResponse']['next_step_id'] = $this->nextStep[0]['id'];
				$redirect = array('action' => $this->nextStep[0]['type'], $programId, $this->nextStep[0]['id']);
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
				if(!empty($programDocuments)) {
					$program['currentStep'] = $this->currentStep[0];
					$user = $this->Auth->user();
					$program['User'] = $user['User'];
					$this->ProgramResponse->Program->ProgramDocument->queueProgramDocs($programDocuments, $program, $this->data);
				}
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Completed ' .  $this->currentStep[0]['name'] . ' for program ' . $program['Program']['name']);

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
		// TODO: determine if esign will be on the program level or on the step level
		$data['esignRequired'] = false;
		if($program['Program']['form_esign_required']) {
			$data['esignRequired'] = true;
			$data['esignInstructions'] = Set::extract('/ProgramInstruction[type=esign]/text', $program);
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
		$programDocuments = Set::extract('/ProgramDocument[program_step_id='.$this->currentStep[0]['id'].']', $program);
		$responseActivity = Set::extract('/ProgramResponseActivity[program_step_id=' . $stepId .']', $program['ProgramResponse'][0]);
		if(!empty($this->data)) {
            $this->data['ProgramResponse']['id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponseActivity'][0]['answers'] = json_encode($this->data['ProgramResponseActivity'][0]);
			$this->data['ProgramResponseActivity'][0]['id'] = $this->data['ProgramResponseActivity']['id']; 
			unset($this->data['ProgramResponseActivity']['id']);
			$this->data['ProgramResponseActivity'][0]['status'] = 'complete';
			$this->data['ProgramResponseActivity'][0]['program_step_id'] = $this->currentStep[0]['id'];
			$this->data['ProgramResponseActivity'][0]['type'] = 'form';
			if(isset($nextStep)) {
				$this->data['ProgramResponse']['next_step_id'] = $this->nextStep[0]['id'];
				$redirect = array('action' => $this->nextStep[0]['type'], $programId, $this->nextStep[0]['id']);
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
				if(!empty($programDocuments)) {
					$program['currentStep'] = $this->currentStep[0];
					$user = $this->Auth->user();
					$program['User'] = $user['User'];
					$this->ProgramResponse->Program->ProgramDocument->queueProgramDocs($programDocuments, $program, $this->data);
				}
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Completed ' .  $this->currentStep[0]['name'] . ' for program ' . $program['Program']['name']);
				$this->Session->setFlash(__('Saved', true), 'flash_success');
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
        $instructions = Set::extract('/ProgramInstruction[program_step_id='.$stepId.']/text', $program);
		$data['formFields'] = $this->currentStep[0]['ProgramFormField'];
		// TODO: determine if esign will be on the program level or on the step level
		$data['esignRequired'] = false;
		if($program['Program']['form_esign_required']) {
			$data['esignRequired'] = true;
			$data['esignInstructions'] = Set::extract('/ProgramInstruction[type=esign]/text', $program);
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
        $program = $this->ProgramResponse->Program->getProgramAndResponse($programId, $this->Auth->user('id'));
		$this->whatsNext($program, $stepId); 
        if(!empty($this->data)) {
            $this->data['ProgramResponse']['id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponse']['next_step_id'] = null;
			$this->data['ProgramResponseActivity'][0]['status'] = 'complete';
			$this->data['ProgramResponseActivity'][0]['program_response_id'] = $program['ProgramResponse'][0]['id'];
			$this->data['ProgramResponseActivity'][0]['program_step_id'] = $this->currentStep[0]['id'];
			$this->data['ProgramResponseActivity'][0]['type'] = 'media';
			if(isset($this->nextStep)) {
				$this->data['ProgramResponse']['next_step_id'] = $this->nextStep[0]['id'];
				$redirect = array('action' => $this->nextStep[0]['type'], $programId, $this->nextStep[0]['id']);
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
                $this->Transaction->createUserTransaction('Programs', null, null,
                    'Completed' . $this->currentStep[0]['name']);
                $this->Session->setFlash(__('Saved', true), 'flash_success');
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
            $data['media'] = '/program_responses/load_media/' . $this->currentStep[0]['id'];
        }
        if($instructions) {
            $data['instructions'] = $instructions[0];
        }
        $data['title_for_layout'] = $this->currentStep[0]['name'];
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
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		$program = $this->ProgramResponse->Program->findById($id);
		if(!empty($this->data)) {
			$programResponse =
				$this->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
			$this->data['ProgramResponse']['answers'] = json_encode($this->data['ProgramResponse']);
			$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
			$this->data['ProgramResponse']['program_id'] = $id;
			$this->data['ProgramResponse']['not_approved'] = 0;
			if(!strpos($program['Program']['type'], 'docs', 0) && $program['Program']['approval_required'] == 0) {
				$this->data['ProgramResponse']['complete'] = 1;
			}
			elseif(!strpos($program['Program']['type'], 'docs', 0) && $program['Program']['approval_required'] == 1) {
				$this->data['ProgramResponse']['needs_approval'] = 1;
			}
			if($this->ProgramResponse->save($this->data)) {
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Completed form for ' . $programResponse['Program']['name']);
				$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', array(
					'conditions' => array(
						'ProgramEmail.program_id' => $id,
						'ProgramEmail.type' => 'form'
					)));
				$this->Notifications->sendProgramEmail($programEmail);
				$this->Session->setFlash(__('Saved', true), 'flash_success');
				if(strpos($program['Program']['type'], 'docs', 0)) {
					if($programResponse['ProgramResponse']['uploaded_docs']) {
						$this->redirect(array('action' => 'provided_docs', $id, 'uploaded_docs'));
					}
					if($programResponse['ProgramResponse']['dropping_off_docs']) {
						$this->redirect(array('action' => 'provided_docs', $id, 'dropping_off_docs'));
					}
					else {
						$this->redirect(array('action' => 'required_docs', $id));
					}
				}
				elseif($program['Program']['approval_required']) {
					$this->redirect(array('action' => 'pending_approval', $id));
				}
				else {
					$this->redirect(array('action' => 'response_complete', $id, true));
				}
			}
			else {
				$this->Session->setFlash(__('Unable to save', true), 'flash_failure');
			}
		}
		$instructions = Set::extract('/ProgramInstruction[type=form]/text', $program);
		if($instructions) {
			$data['instructions'] = $instructions[0];
		}
		if($program['Program']['form_type'] === 'quiz') {
			$data['title_for_layout'] = $program['Program']['name'] . ' Quiz Form';
		}
		else {
			$data['title_for_layout'] = $program['Program']['name'] . ' Registration Form' ;
		}
		$data['program'] = $program;
		if($program['Program']['view_media_again']) {
			$type = explode('_', $program['Program']['type']);
			$data['viewMediaAgainLink'] = '/programs/view_media/'.$program['Program']['id'].'/'.$type[0];
		}
		else {
			$data['viewMediaAgainLink'] = null;
		}
		$this->set($data);
	}

	function required_docs($id = null, $reset = null) {
		if(!$id){
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		$programResponse = $this->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
		if($reset == 1) {
			$this->ProgramResponse->id = $programResponse['ProgramResponse']['id'];
			$this->ProgramResponse->saveField('uploaded_docs', 0);
			$this->ProgramResponse->saveField('dropping_off_docs', 0);

		}
		if(!empty($this->data)) {
			$this->loadModel('QueuedDocument');
			$this->data['QueuedDocument']['req_program_doc'] = 1;
			$this->QueuedDocument->set($this->data);
			if($this->QueuedDocument->validates()) {
				if($this->QueuedDocument->uploadDocument($this->data, 'Program Upload', $this->Auth->user('id'))) {
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Uploaded document for ' . $programResponse['Program']['name']);
					$this->Session->setFlash(__('Document uploaded successfully.', true), 'flash_success');
					$this->redirect(array('action' => 'required_docs', $id));
				}
				else {
					$this->Session->setFlash(__('Unable to upload document, please try again', true), 'flash_failure');
					$this->redirect(array('action' => 'required_docs', $id));
				}
			}
			else {
				$this->Session->setFlash(__('Unable to upload document, please try again', true), 'flash_failure');
				$this->validationErrors['QueuedDocument'] = $this->QueuedDocument->invalidFields();
			}

		}
		$program = $this->ProgramResponse->Program->findById($id);
		$instructions = Set::extract('/ProgramInstruction[type=document]/text', $program);
		if($instructions) {
			$data['instructions'] = $instructions[0];
		}
		$data['title_for_layout'] = 'Required Documentation';
		$data['queueCategoryId'] = $program['Program']['queue_category_id'];
		$this->set($data);
	}

	function response_complete($id=null, $autoApprove=false) {
		$programResponse = $this->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
		if($autoApprove) {
			$form = $this->ProgramResponse->Program->ProgramPaperForm->find('first', array(
				'conditions' => array(
					'ProgramPaperForm.program_id' => $programResponse['Program']['id'],
					'ProgramPaperForm.cert' => 1)));
			$generated = $this->_generateForm($form['ProgramPaperForm']['id'], $programResponse['ProgramResponse']['id']);
			$this->Transaction->createUserTransaction('Programs', null, null,
				'Completed program ' . $programResponse['Program']['name']);
			$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first',
				array('conditions' => array(
					'ProgramEmail.program_id' => $programResponse['Program']['id'],
					'ProgramEmail.type' => 'final'
			)));
			$this->Notifications->sendProgramEmail($programEmail);
		}
		if(!$programResponse) {
			$this->Session->setFlash(__('An error has occured.', true), 'flash_failure');
		}
		$instructions = $this->ProgramResponse->Program->ProgramInstruction->getInstructions(
			$id, 'complete');
		$title_for_layout = 'Program Certificate';
		$this->set(compact('title_for_layout', 'programResponse', 'instructions'));
	}

	function view_cert($id=null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		$programResponse = $this->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
		$docId = Set::extract('/ProgramResponseDoc[type=certificate]/doc_id', $programResponse);
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

	function provided_docs($id, $type) {
		$programResponse = $this->ProgramResponse->getProgramResponse($id, $this->Auth->user('id'));
		$this->ProgramResponse->Program->ProgramInstruction->recursive = -1;
		if($programResponse['ProgramResponse']['uploaded_docs'] == 0 &&
			$programResponse['ProgramResponse']['dropping_off_docs'] == 0) {
				if($type == 'uploaded_docs') {
					$this->ProgramResponse->id = $programResponse['ProgramResponse']['id'];
					$this->ProgramResponse->saveField('uploaded_docs', 1);
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Selected I am done uploading documents for ' . $programResponse['Program']['name']);
				}
				elseif($type == 'dropping_off_docs') {
					$this->ProgramResponse->id = $programResponse['ProgramResponse']['id'];
					$this->ProgramResponse->saveField('dropping_off_docs', 1);
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Selected dropping off documents for ' . $programResponse['Program']['name']);
				}
			}

		$data['instructions'] = $this->ProgramResponse->Program->ProgramInstruction->getInstructions(
			$id, $type);
		$data['title_for_layout'] = 'Program Response Documents';
		$this->set($data);
	}

	function pending_approval($programId) {
		$data['instructions'] = $this->ProgramResponse->Program->ProgramInstruction->getInstructions(
			$programId, 'pending_approval');
		$data['title_for_layout'] = 'Program Response Pending Approval';
		$this->set($data);
	}

	function not_approved($programId) {
		$data['title_for_layout'] = 'Program Response Not Approved';
		$data['instructions'] = $this->ProgramResponse->Program->ProgramInstruction->getInstructions(
			$programId, 'not_approved');
		$this->set($data);
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
					$conditions['ProgramResponse.status'] = $this->params['url']['status'];
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

	function admin_view($id, $type=null) {
		$this->ProgramResponse->contain(array(
			'Program' => array('ProgramStep'), 
			'ProgramResponseActivity', 
			'ProgramResponseDoc', 
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
				$formActivities = Set::extract('/ProgramResponseActivity[type=form]', $programResponse);
				// TODO make sure that this is going to work for multiple sets of form answers
				if(!empty($formActivities)) {
					$i = 0;
					foreach($formActivities as $formActivity) {
						$data['answers'][$i] = json_decode($formActivity['ProgramResponseActivity']['answers'], true);	
						$data['stepName'] = 
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
					$generatedDocs = Set::extract('/ProgramResponseDoc[type=system_generated]',  $programResponse);
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
					Program->ProgramDocument->findAllByProgramId($programResponse['Program']['id']);
				if($programDocs) {
					$i = 0;
					foreach($programDocs as $programDoc) {
						if(isset($generatedDocs)) {
							foreach($generatedDocs as $generatedDoc) {
								if($programDoc['ProgramDocument']['cat_3']) {
									$cat = $programDoc['ProgramDocument']['cat_3'];
								}
								elseif($programDoc['ProgramDocument']['cat_2']) {
									$cat = $programDoc['ProgramDocument']['cat_2'];
								}
								else {
									$cat = $programDoc['ProgramDocument']['cat_1'];
								}
								if($generatedDoc['ProgramResponseDoc']['cat_id'] === $cat) {
									$data['generatedDocs'][$i]['view'] = '<a href="/admin/filed_documents/view/' .
										$generatedDoc['ProgramResponseDoc']['doc_id'].'" target="_blank">View Doc</a>';
									$data['generatedDocs'][$i]['doc_id'] = $generatedDoc['ProgramResponseDoc']['doc_id'];
									$data['generatedDocs'][$i]['filed_on'] = $generatedDoc['ProgramResponseDoc']['created'];
								}
							}
						}
						$data['generatedDocs'][$i]['name'] = $programDoc['ProgramDocument']['name'];
						$data['generatedDocs'][$i]['programResponseId'] = $programResponse['ProgramResponse']['id'];
						$data['generatedDocs'][$i]['id'] = $programDoc['ProgramDocument']['id'];
						$i++;
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
		$programName = $programResponse['Program']['name'];
		$title_for_layout = 'Program Response';
		$this->set(compact('title_for_layout', 'approval', 'programName'));
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
				if(strpos($programResponse['Program']['type'], 'docs')) {
					if(!empty($programResponse['ProgramResponseDoc'])) {
						$programDocs = $this->ProgramResponse->
							Program->ProgramPaperForm->findAllByProgramId($programResponse['Program']['id']);
						$catIds = Set::extract('/ProgramResponseDoc[type=system_generated]/cat_id', $programResponse);
						$formCatIds = Set::extract('/ProgramDocument/cat_3', $programDocs);
						if(!empty($formCatIds)) {
							$result = array_diff($formCatIds, $catIds);
							if(!empty($result)) {
								$data['success'] = false;
								$data['message'] = 'You must generate all program forms before approving response.';
								$this->set(compact('data'));
								return $this->render(null, null, '/elements/ajaxreturn');
							}
						}
					}
					else {
						$data['success'] = false;
						$data['message'] = 'All required documents must be filed to customer before approving response.';
						$this->set(compact('data'));
						return $this->render(null, null, '/elements/ajaxreturn');
					}
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
					$this->Notifications->sendProgramEmail($programEmail, $user);
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
				$this->data['ProgramResponse']['id'] = $this->params['form']['id'];
				$this->data['ProgramResponse']['status'] = 'not_approved';
				if(isset($this->params['form']['reset_form'])) {
					$this->data['ProgramResponseActivity'][0]['id'] = $this->params['form']['reset_form'];
					$this->data['ProgramResponseActivity'][0]['status'] = 'allow_edit';
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
					$this->log($programEmail, 'debug');
					$user['User'] = $programResponse['User'];
					if($programEmail) {
						if(!empty($this->params['form']['email_comment'])) {
							$programEmail['ProgramEmail']['body'] .= "\r\n\r\n\r\n" .
							'Comment: ' . $this->params['form']['email_comment'];
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

	function _generateForm($formId, $programResponseId, $docId=null) {

			$programResponse = $this->ProgramResponse->findById($programResponseId);

			foreach($programResponse['User'] as $k => $v) {
				if(!preg_match('[\@]', $v)) {
					$programResponse['User'][$k] = ucwords($v);
				}
			}

			$data = $programResponse['User'];

			$programPaperForm = $this->ProgramResponse->Program->ProgramPaperForm->findById($formId);

			if($programResponse['ProgramResponse']['answers']) {
				$answers = json_decode($programResponse['ProgramResponse']['answers'], true);

				foreach($answers as $k => $v) {
					if(!preg_match('[\@]', $v)) {
						$data[$k] = ucwords($v);
					}
				}
			}

			$data['masked_ssn'] = '***-**-' . substr($data['ssn'], -4);
			$data['confirmation_id'] = $programResponse['ProgramResponse']['confirmation_id'];
			$data['dob'] = date('m/d/Y', strtotime($data['dob']));
			$data['admin'] = $this->Auth->user('firstname') . ' ' . $this->Auth->user('lastname');
			$data['todays_date'] = date('m/d/Y');
			$data['form_completed'] = date('m/d/Y', strtotime($programResponse['ProgramResponse']['created']));
			$data['program_name'] = $programResponse['Program']['name'];

			if($programPaperForm) {
				$pdf = $this->_createPDF($data, $programPaperForm['ProgramPaperForm']['template']);
				if($pdf) {
					$this->loadModel('FiledDocument');
					if(!$docId) {
						$this->FiledDocument->User->QueuedDocument->create();
						$this->FiledDocument->User->QueuedDocument->save();
						$docId = $this->FiledDocument->User->QueuedDocument->getLastInsertId();
						// delete the empty record so it does not show up in the queue
						$this->FiledDocument->User->QueuedDocument->delete($docId, false);
						$genType = 'Generated';
					}
					else {
						$this->data['ProgramResponseDoc']['id'] =
						$this->ProgramResponse->ProgramResponseDoc->field('id', array(
								'ProgramResponseDoc.doc_id' => $docId,
								'ProgramResponseDoc.program_response_id' => $programResponseId));
						$genType = 'Regenerated';
					}

					$this->data['FiledDocument']['id'] = $docId;
					$this->data['FiledDocument']['created'] = date('Y-m-d H:i:s');
					$this->data['FiledDocument']['filename'] = $pdf;
					if($this->Auth->user('role_id')!= 1) {
						$this->data['FiledDocument']['admin_id'] = $this->Auth->user('id');
						$this->data['FiledDocument']['filed_location_id'] = $this->Auth->user('location_id');
						$this->data['FiledDocument']['last_activity_admin_id'] = $this->Auth->user('id');
					}
					$this->data['FiledDocument']['user_id'] = $data['id'];
					$this->data['FiledDocument']['cat_1'] = $programPaperForm['ProgramPaperForm']['cat_1'];
					$this->data['FiledDocument']['cat_2'] = $programPaperForm['ProgramPaperForm']['cat_2'];
					$this->data['FiledDocument']['cat_3'] = $programPaperForm['ProgramPaperForm']['cat_3'];
					$this->data['FiledDocument']['entry_method'] = 'Program Generated';
					$this->data['FiledDocument']['filed'] = date('Y-m-d H:i:s');
					$this->data['ProgramResponseDoc']['created'] = date('Y-m-d H:i:s');
					$this->data['ProgramResponseDoc']['cat_id'] = $programPaperForm['ProgramPaperForm']['cat_3'];
					$this->data['ProgramResponseDoc']['program_response_id'] =	$programResponseId;
					$this->data['ProgramResponseDoc']['doc_id'] = $docId;
					$this->data['ProgramResponseDoc']['paper_form'] = 1;
					if($programPaperForm['ProgramPaperForm']['cert']) {
						$this->data['ProgramResponseDoc']['cert'] = 1;
					}
					if($this->FiledDocument->save($this->data['FiledDocument']) &&
					$this->ProgramResponse->ProgramResponseDoc->save($this->data['ProgramResponseDoc'])) {
						return array($programPaperForm, $programResponse, $genType);
					}
					else {
						$path = Configure::read('Document.storage.uploadPath');
						$path .= substr($pdf, 0, 4) . DS;
						$path .= substr($pdf, 4, 2) . DS;
						$file = $path . $pdf;
						unlink($file);
						return false;
					}
				}
				else {
					return false;
				}
			}
	}

	function _createFDF($file,$info){
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

	function _createPDF($data, $template){

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
		$fdfData = $this->_createFDF($pdfTemplate,$data);

		// write the file out
		if($fp=fopen($fdfDir.DS.$fdfFile,'w')){
			fwrite($fp,$fdfData,strlen($fdfData));
		}
		fclose($fp);

		$pdftkCommandString = DS . 'usr' . DS . 'bin' . DS . 'pdftk ' . APP . 'storage' . DS . 'program_forms' . DS .
			$template . ' fill_form ' . TMP . 'fdf' . DS . $fdfFile . ' output ' . $path . DS . $pdfFile . ' flatten';

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
		$path = Configure::read('Document.storage.uploadPath');
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
		if(isset($steps['previous'])) {
			$this->Session->setFlash(__('Steps must be completed in order.', true), 'flash_failure');
			$previousStep = $steps['previous'];
			$this->redirect(array('action' => $previousStep[0]['type'], $program['Program']['id'], $previousStep[0]['id']));
		}
		elseif(isset($steps['next'])) {
			$this->nextStep = $steps['next'];
		}
	}
}
