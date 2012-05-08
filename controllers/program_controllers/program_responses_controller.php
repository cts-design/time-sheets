<?php
App::import('Vendor', 'wkhtmltopdf/wkhtmltopdf');

class ProgramResponsesController extends AppController {

	var $name = 'ProgramResponses';

	var $components = array('Notifications');

	function beforeFilter() {
		parent::beforeFilter();
		$this->ProgramResponse->Program->ProgramStep->ProgramFormField->recursive = 2;
		if(!empty($this->params['pass'][0]) && ($this->params['action'] == 'form' || $this->params['action'] == 'edit_form')){
			$query = $this->ProgramResponse->Program->ProgramStep->ProgramFormField->findAllByProgramStepId($this->params['pass'][0]);
			if($query){
				$fields = Set::classicExtract($query, '{n}.ProgramFormField');
				foreach($fields as $k => $v) {
					if(!empty($v['validation'])) {
						$validate[$v['name']] = json_decode($v['validation'], true);
					}
				}
				// :TODO make this work with the real esign
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
					'admin_generate_form');
			}
	}

	function form($stepId = null) {
		if(!$stepId) {
			$this->Session->setFlash(__('Invalid step id.', 'flash_failure'));
			$this->redirect($this->referer());
		}
		$step = $this->ProgramResponse->Program->ProgramStep->findById($stepId);
		if($step) {
			$data['program'] = $step['Program'];
			$data['formFields'] = $step['ProgramFormField'];
			$data['instructions'] = $step['ProgramInstruction']['text'];
			$data['title_for_layout'] = $step['ProgramStep']['name'];
		}
		if(!empty($this->data)) {
			$programResponse =
				$this->ProgramResponse->getProgramResponse($step['Program']['id'], $this->Auth->user('id'));
			$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
			$this->data['ProgramResponseActivity'][0]['answers'] = json_encode($this->data['ProgramResponseActivity'][0]);
			$this->data['ProgramResponseActivity'][0]['program_step_id'] = $step['ProgramStep']['id'];
			$this->data['ProgramResponseActivity'][0]['type'] = 'form';
			$this->data['ProgramResponseActivity'][0]['status'] = 'complete';
			switch($programResponse['Program']['type']) {
				case 'registration':
					if ($programResponse['Program']['approval_required']) {
						$this->data['ProgramResponse']['status'] = 'pending_approval';
						$emailType = 'pending_approval';
					}
					else {
						$this->data['ProgramResponse']['status'] = 'complete';
						$emailType = 'complete';
					}
					$redirect = array('controller' => 'programs', 'action' => 'registration', $programResponse['Program']['id']);
					break;
			}

			if($this->ProgramResponse->saveAll($this->data)) {
				$snapshot['steps'][0] = array(
					'answers' => json_decode($this->data['ProgramResponseActivity'][0]['answers'], true),
					'name' => $step['ProgramStep']['name']);
				$snapshot['programName'] = $programResponse['Program']['name'];
				$snapshot['responseId'] = $programResponse['ProgramResponse']['id'];
				$snapshot['toc'] = false;
				$snapshot['user'] = $this->Auth->user('name_last4');
				$snapshot['userId'] = $this->Auth->user('id');
				$snapshot['ProgramDocument'] = $step['ProgramDocument'][0];
				if(isset($emailType)) {
					$this->ProgramResponse->Program->ProgramEmail->recursive = -1;
					$responseStatusEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', array(
						'conditions' => array(
							'ProgramEmail.program_id' => $step['Program']['id'],
							'ProgramEmail.type' => $emailType)));
					if($responseStatusEmail) {
						$this->Notifications->sendProgramEmail($responseStatusEmail['ProgramEmail']);
					}
				}
				$options = array('priority' => 5000, 'tube' => 'pdf_snapshot');
				$delayedTaskId = ClassRegistry::init('Queue.Job')->put($snapshot, $options);
				// :TODO save $delayedTaskId to the the user activity record? 
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Completed ' . $step['ProgramStep']['name'] . ' ' . $programResponse['Program']['name']);
			
				if(! empty($step['ProgramEmail'])) {
					$this->Notifications->sendProgramEmail($step['ProgramEmail']);
				}
				$this->Session->setFlash(__('Saved', true), 'flash_success');
				$this->redirect($redirect);
			}
		}
		$this->set($data);
		$this->render('form');
	}

	function send_test_email() {
		$payload = array(
			'Email' => array(
				'from' => 'test@test.com',
				'to' => $this->Auth->user('email'),
				'subject' => 'This is a test email',
				'body' => 'This is the body'));
		$options = array('priority' => 5000, 'tube' => 'program_email');
		$delayedTaskId = ClassRegistry::init('Queue.job')->put($payload, $options);
	}
	function generate_test_data() {
	
		$data['steps'][0] = array(
			'answers' =>  array('This question' => 'This answer', 'That question' => 'That Answer'),
			'name' => 'Program Step Name');
		$data['programName'] = 'Wia';
		$data['toc'] = false;
		$data['user'] = $this->Auth->user('name_last4');

		$payload = array('data' => $data); 
		$options = array('priority' => 5000, 'tube' => 'pdf_snapshot');
		$delayedTaskId = ClassRegistry::init('Queue.Job')->put($payload, $options);
	}

	function edit_form($stepId = null) {
		if(!$stepId) {
			$this->Session->setFlash(__('Invalid step id.', 'flash_failure'));
			$this->redirect($this->referer());
		}
		// :TODO add logic to check for valid response and valid step
		$step = $this->ProgramResponse->Program->ProgramStep->findById($stepId);
		if($step) {
			$data['program'] = $step['Program'];
			$data['formFields'] = $step['ProgramFormField'];
			$data['instructions'] = $step['ProgramInstruction']['text'];
			$data['title_for_layout'] = $step['ProgramStep']['name'];
		}
		$programResponse =
			$this->ProgramResponse->getProgramResponse($step['Program']['id'], $this->Auth->user('id'));
		$responseActivity = Set::extract('/ProgramResponseActivity[program_step_id=' . $stepId .']', $programResponse);

		if(!empty($this->data)) {
			$this->data['ProgramResponse']['id'] = $programResponse['ProgramResponse']['id'];
			$this->data['ProgramResponseActivity'][0]['id'] = $responseActivity[0]['ProgramResponseActivity']['id'];
			$this->data['ProgramResponseActivity'][0]['answers'] = json_encode($this->data['ProgramResponseActivity'][0]);
			$this->data['ProgramResponseActivity'][0]['program_step_id'] = $step['ProgramStep']['id'];
			$this->data['ProgramResponseActivity'][0]['type'] = 'form';
			$this->data['ProgramResponseActivity'][0]['status'] = 'complete';
			switch($programResponse['Program']['type']) {
				case 'registration':
					if ($programResponse['Program']['approval_required']) {
						$this->data['ProgramResponse']['status'] = 'pending_approval';
						$emailType = 'pending_approval';
					}
					else {
						$this->data['ProgramResponse']['status'] = 'complete';
						$emailType = 'complete';
					}
					$redirect = array('controller' => 'programs', 'action' => 'registration', $programResponse['Program']['id']);
					break;
			}

			if($this->ProgramResponse->saveAll($this->data)) {
				$snapshot['steps'][0] = array(
					'answers' => json_decode($this->data['ProgramResponseActivity'][0]['answers'], true),
					'name' => $step['ProgramStep']['name']);
				$snapshot['programName'] = $programResponse['Program']['name'];
				$snapshot['responseId'] = $programResponse['ProgramResponse']['id'];
				$snapshot['toc'] = false;
				$snapshot['user'] = $this->Auth->user('name_last4');
				$snapshot['userId'] = $this->Auth->user('id');
				$snapshot['ProgramDocument'] = $step['ProgramDocument'][0];
				if(isset($emailType)) {
					$this->ProgramResponse->Program->ProgramEmail->recursive = -1;
					$responseStatusEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', array(
						'conditions' => array(
							'ProgramEmail.program_id' => $step['Program']['id'],
							'ProgramEmail.type' => $emailType)));
					if($responseStatusEmail) {
						$this->Notifications->sendProgramEmail($responseStatusEmail['ProgramEmail']);
					}
				}
				$options = array('priority' => 5000, 'tube' => 'pdf_snapshot');
				$delayedTaskId = ClassRegistry::init('Queue.Job')->put($snapshot, $options);
				// :TODO save $delayedTaskId to the the user activity record? 
				$this->Transaction->createUserTransaction('Programs', null, null,
					'Completed ' . $step['ProgramStep']['name'] . ' ' . $programResponse['Program']['name']); // @TODO should this be completed or edited? 
			
				if(! empty($step['ProgramEmail'])) {
					$this->Notifications->sendProgramEmail($step['ProgramEmail']);
				}
				$this->Session->setFlash(__('Saved', true), 'flash_success');
				$this->redirect($redirect);
			}
		}	
		if(empty($this->data['ProgramResponseActivity'])) {
			$this->data['ProgramResponseActivity'][0] = json_decode($responseActivity[0]['ProgramResponseActivity']['answers'], true);
		}
		$this->set($data);
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
		$docId = Set::extract('/ProgramResponseDoc[cert=1]/doc_id', $programResponse);
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
				if(!empty($this->params['url']['tab'])) {
					switch($this->params['url']['tab']) {
						case 'open':
							$conditions['ProgramResponse.complete'] = 0;
							$conditions['ProgramResponse.needs_approval'] = 0;
							$conditions['ProgramResponse.expires_on >'] = date('Y-m-d H:i:s');
							$conditions['ProgramResponse.not_approved'] = 0;
							break;
						case 'closed':
							$conditions['ProgramResponse.complete'] = 1;
							$conditions['ProgramResponse.needs_approval'] = 0;
							$conditions['ProgramResponse.not_approved'] = 0;
							break;
						case 'expired':
							$conditions['ProgramResponse.complete'] = 0;
							$conditions['ProgramResponse.not_approved'] = 0;
							$conditions['ProgramResponse.expires_on <'] = date('Y-m-d H:i:s');
							break;
						case 'pending_approval':
							$conditions['ProgramResponse.complete'] = 0;
							$conditions['ProgramResponse.expires_on >'] = date('Y-m-d H:i:s');
							$conditions['ProgramResponse.needs_approval'] = 1;
							$conditions['ProgramResponse.not_approved'] = 0;
							break;
						case 'not_approved':
							$conditions['ProgramResponse.complete'] = 0;
							$conditions['ProgramResponse.not_approved'] = 1;
							break;
					}
				}

				$data['totalCount'] = $this->ProgramResponse->find('count', array('conditions' => $conditions));

				$this->paginate = array('conditions' => $conditions);
				$responses =  $this->Paginate('ProgramResponse');
				if($responses) {
					$i = 0;
					foreach($responses as $response) {
						if($response['ProgramResponse']['complete'] == 1) {
							$status = 'Closed';
						}
						else {
							$status = 'Open';
						}

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
							'status' => $status
						);
						if($this->params['url']['tab'] == 'closed'){
							$data['responses'][$i]['actions'] =
								'<a href="/admin/program_responses/view/'.
									$response['ProgramResponse']['id'].'">View</a>';
						}
						elseif($this->params['url']['tab'] == 'expired'){
							$data['responses'][$i]['actions'] =
								'<a href="/admin/program_responses/view/'.
									$response['ProgramResponse']['id'].'">View</a> | ' .
									'<a href="/admin/program_responses/toggle_expired/' .
									$response['ProgramResponse']['id'] . '/unexpire'.'" class="expire">Mark Un-Expired</a>';
						}
						elseif($this->params['url']['tab'] == 'not_approved') {
							if($response['ProgramResponse']['allow_new_response'] || !$response['ProgramResponse']['answers']) {
								$data['responses'][$i]['actions'] =
									'<a href="/admin/program_responses/view/'.
										$response['ProgramResponse']['id'].'">View</a>';
							}
							else {
								$data['responses'][$i]['actions'] =
									'<a href="/admin/program_responses/view/'.
										$response['ProgramResponse']['id'].'">View</a> | ' .
									'<a href="/admin/program_responses/reset_form/'.
										$response['ProgramResponse']['id'].'" class="reset">Reset Form</a> | ' .
										'<a href="/admin/program_responses/allow_new_response/' .
										$response['ProgramResponse']['id'] . '" class="allow-new">Allow New Response</a>';
							}

						}
						else {
							$data['responses'][$i]['actions'] =
								'<a href="/admin/program_responses/view/'.
									$response['ProgramResponse']['id'].'">View</a> | ' .
									'<a href="/admin/program_responses/toggle_expired/' .
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
			if($program['Program']['approval_required'] == 1){
				$approvalPermission = $this->Acl->check(array(
					'model' => 'User',
					'foreign_key' => $this->Auth->user('id')), 'ProgramResponses/admin_approve', '*');

			}
			else {
				$approvalPermission = null;
			}
			$programName = $program['Program']['name'];
			$this->set(compact('approvalPermission', 'programName'));
		}
	}

	function admin_view($id, $type=null) {
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
				$yesNo = array('No', 'Yes');
				$data['answers'] = json_decode($programResponse['ProgramResponse']['answers'], true);
				$data['viewedMedia'] = $yesNo[$programResponse['ProgramResponse']['viewed_media']];
				if($programResponse['ProgramResponse']['answers'] != null) {
					$data['completedForm'] = 'Yes';
				}
				else $data['completedForm'] = 'No';
				$this->set($data);
				$this->render('/elements/program_responses/answers');
			}
			if($type == 'documents') {
				if(!empty($programResponse['ProgramResponseDoc'])) {
					$this->loadModel('DocumentFilingCategory');
					$filingCatList = $this->DocumentFilingCategory->find('list');
					$docs = Set::extract('/ProgramResponseDoc[paper_form<1]',  $programResponse);
					$filedForms = Set::extract('/ProgramResponseDoc[paper_form=1]',  $programResponse);
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
				$forms = $this->ProgramResponse->
					Program->ProgramPaperForm->findAllByProgramId($programResponse['Program']['id']);
				if($forms) {
					$i = 0;
					foreach($forms as $form) {
						if(isset($filedForms)) {
							foreach($filedForms as $filedForm) {
								if($filedForm['ProgramResponseDoc']['cat_id'] == $form['ProgramPaperForm']['cat_3']) {
									$data['forms'][$i]['link'] =
										'<a class="generate" href="/admin/program_responses/generate_form/'.
										$form['ProgramPaperForm']['id'] . '/' .
										$programResponse['ProgramResponse']['id'] .'/'.
										$filedForm['ProgramResponseDoc']['doc_id'] . '">Re-Generate</a>';
									$data['forms'][$i]['view'] = '<a href="/admin/filed_documents/view/' .
										$filedForm['ProgramResponseDoc']['doc_id'].'" target="_blank">View Doc</a>';
									$data['forms'][$i]['doc_id'] = $filedForm['ProgramResponseDoc']['doc_id'];
									$data['forms'][$i]['filed_on'] = $filedForm['ProgramResponseDoc']['created'];
								}
							}
						}

						if(!isset($data['forms'][$i]['link'])) {
							$data['forms'][$i]['link'] = '<a class="generate" href="/admin/program_responses/generate_form/'.
								$form['ProgramPaperForm']['id'] . '/' .
								$programResponse['ProgramResponse']['id'] .'">Generate</a>';
						}
						$data['forms'][$i]['name'] = $form['ProgramPaperForm']['name'];
						$data['forms'][$i]['cat_3'] = $form['ProgramPaperForm']['cat_3'];
						$data['forms'][$i]['programResponseId'] = $programResponse['ProgramResponse']['id'];
						$data['forms'][$i]['id'] = $form['ProgramPaperForm']['id'];
						$i++;
					}
				}
				$this->set($data);
				$this->render('/elements/program_responses/documents');
			}
		}

		if($programResponse['Program']['approval_required'] &&
			$programResponse['ProgramResponse']['needs_approval'] == 1
			&& $programResponse['ProgramResponse']['not_approved'] == 0) {
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
						$forms = $this->ProgramResponse->
							Program->ProgramPaperForm->findAllByProgramId($programResponse['Program']['id']);
						$catIds = Set::extract('/ProgramResponseDoc[paper_form=1]/cat_id', $programResponse);
						$formCatIds = Set::extract('/ProgramPaperForm/cat_3', $forms);
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
				$this->data['ProgramResponse']['needs_approval'] = 0;
				$this->data['ProgramResponse']['complete'] = 1;
				if($this->ProgramResponse->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Program response was approved successfully.';
					$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first',
						array('conditions' => array(
							'ProgramEmail.program_id' => $programResponse['Program']['id'],
							'ProgramEmail.type' => 'final'
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
				$this->data['ProgramResponse']['not_approved'] = 1;
				if(isset($this->params['form']['reset_form']) == 'on') {
					$this->data['ProgramResponse']['answers'] = null;
				}
				if($this->ProgramResponse->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Program response marked not approved.';
					$programResponse = $this->ProgramResponse->read(null, $this->params['form']['id']);

					$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first',
						array('conditions' => array(
							'ProgramEmail.program_id' => $programResponse['Program']['id'],
							'ProgramEmail.type' => 'not_approved'
					)));
					$user['User'] = $programResponse['User'];
					if($programEmail) {
						if(!empty($this->params['form']['email_comment'])) {
							$programEmail['ProgramEmail']['body'] .= "\r\n\r\n\r\n" .
							'Comment: ' . $this->params['form']['email_comment'];
						}
						$this->Notifications->sendProgramEmail($programEmail, $user);
					}
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Marked ' . $programResponse['Program']['name'] .
						' response not approved for ' . ucwords($user['User']['name_last4']));
					if(isset($this->params['form']['reset_form']) == 'on') {
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
			$allProgramResponses = null;
			if($toggle == 'expire') {
				$this->data['ProgramResponse']['expires_on'] =
					date('Y-m-d H:i:s', strtotime('-' . ($programResponse['Program']['response_expires_in']+1) . ' days'));
			}
			elseif($toggle == 'unexpire') {
				$this->data['ProgramResponse']['expires_on'] = date('Y-m-d H:i:s',
					strtotime('+' . ($programResponse['Program']['response_expires_in']) . ' days'));
				$allProgramResponses = $this->ProgramResponse->find('all', array(
					'conditions' => array(
						'ProgramResponse.user_id' => $programResponse['ProgramResponse']['user_id'],
						'ProgramResponse.program_id' => $programResponse['ProgramResponse']['program_id'],
						'ProgramResponse.expires_on >= ' => date('Y-m-d H:i:s'))));
			}
			if($allProgramResponses) {
				$data['success'] = false;
				$data['message'] = 'Customer already has an non-expired response for this program.';
			}
			else {
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
}
