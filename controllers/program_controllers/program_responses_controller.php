<?php 

class ProgramResponsesController extends AppController {
	
	var $name = 'ProgramResponses';
	
	var	$components = array('Notifications');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->ProgramResponse->Program->ProgramField->recursive = 0;
		if(!empty($this->params['pass'][0]) && $this->params['action'] == 'index') {
			$query = $this->ProgramResponse->Program->ProgramField->findAllByProgramId($this->params['pass'][0]); 
			$fields = Set::classicExtract($query, '{n}.ProgramField');
			foreach($fields as $k => $v) {
				if(!empty($v['validation'])) 
					$validate[$v['name']] = json_decode($v['validation'], true); 
				}
			if($query[0]['Program']['form_esign_required']) {
				$validate['form_esignature'] = array(
					'rule' => 'notempty',
					'message' => 'You must put you last name in the box.');
			}			
			$this->ProgramResponse->modifyValidate($validate);
		}
	}	
	
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		} 
		if(!empty($this->data)) {
			$response = $this->ProgramResponse->findByUserId($this->Auth->user('id'));		
			$this->data['ProgramResponse']['form_completed'] = date('m/d/y');		
			$this->data['ProgramResponse']['answers'] = json_encode($this->data['ProgramResponse']);
			$this->data['ProgramResponse']['id'] = $response['ProgramResponse']['id'];
			$this->data['ProgramResponse']['program_id'] = $id;
			if($this->ProgramResponse->save($this->data)) {
				$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', array('conditions' => array(
					'ProgramEmail.program_id' => $id,
					'ProgramEmail.type' => 'form'
				)));
				$this->Notifications->sendProgramEmail($programEmail);
				$this->Session->setFlash(__('Saved', true), 'flash_success');
				$program = $this->ProgramResponse->Program->findById($id);
				if(strpos($program['Program']['type'], 'docs', 0)) {
					$this->redirect(array('action' => 'required_docs', $id));
				}
				else{
					$this->redirect(array('action' => 'submission_received'));
				}
			}
			else {
				$this->Session->setFlash(__('Unable to save', true), 'flash_failure');
			}			
		}
		$program = $this->ProgramResponse->Program->findById($id);
		$instructions = $program['Program']['form_instructions'];
		$title_for_layout = $program['Program']['name'] . ' Registration Form' ;
		$this->set(compact('program', 'title_for_layout', 'instructions'));	
	}
		
	function required_docs($id = null) {
		if(!$id){
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		if(!empty($this->data)) {
			$this->loadModel('QueuedDocument');
			$this->data['QueuedDocument']['req_program_doc'] = 1;	
			if($this->QueuedDocument->uploadDocument($this->data, 'Program Upload', $this->Auth->user('id'))) {
				$this->Session->setFlash(__('Document uploaded successfully.', true), 'flash_success');
				$this->redirect(array('action' => 'doc_upload_success', $id));
			}
			else {
				$this->Session->setFlash(__('Unable to upload document, please try again', true), 'flash_failure');
				$this->redirect(array('action' => 'required_docs', $id));
			}				
		}
		$program = $this->ProgramResponse->Program->findById($id);
		$data['instructions'] = $program['Program']['doc_instructions'];
		$data['title_for_layout'] = 'Required Documentation';
		$data['queueCategoryId'] = $program['Program']['queue_category_id'];
		$this->set($data);
	}
	
	function doc_upload_success() {
		$title_for_layout = 'Document Upload Success';
		$this->set(compact('title_for_layout'));
	}
	
	function submission_received() {
		
	}
	
	function admin_index($id = null) {
		if($id){
			if($this->RequestHandler->isAjax()){
				$conditions = array('ProgramResponse.program_id' => $id);
				if(!empty($this->params['url']['filter'])) {
					switch($this->params['url']['filter']) {
						case 'open':
							$conditions['ProgramResponse.complete'] = 0; 
							break;
						case 'closed':
							$conditions['ProgramResponse.complete'] = 1;
							break;
						case 'expired':
							$conditions['ProgramResponse.expired'] = 1;
							break;							
						case 'unapproved':
							$conditions['ProgramResponse.needs_approval'] = 1;
							break;		 
					}
				}
				if(!empty($conditions)) {
					$data['totalCount'] = $this->ProgramResponse->find('count', array('conditions' => $conditions));	
				}
				else{
					$data['totalCount'] = $this->ProgramResponse->find('count');	
				}
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
							'User-lastname' => $response['User']['lastname'] . ', ' . 
								$response['User']['firstname'] . ' - ' . substr($response['User']['ssn'], -4),
							'created' => $response['ProgramResponse']['created'],
							'modified' => $response['ProgramResponse']['modified'],
							'status' => $status
						);
						if($this->params['url']['filter'] == 'closed'){
							$data['responses'][$i]['actions'] = 
								'<a href="/admin/program_responses/view/'. 
									$response['ProgramResponse']['id'].'">View</a>';							
						}
						elseif($this->params['url']['filter'] == 'expired'){
							$data['responses'][$i]['actions'] = 
								'<a href="/admin/program_responses/view/'. 
									$response['ProgramResponse']['id'].'">View</a> | <a>Mark Un-Expired</a>';							
						}
						elseif($this->params['url']['filter'] == 'unapproved'){
							$data['responses'][$i]['actions'] = 
								'<a href="/admin/program_responses/view/'. 
									$response['ProgramResponse']['id'].'">View</a> | <a>Approve</a>';							
						}							
						else {
							$data['responses'][$i]['actions'] = 
								'<a href="/admin/program_responses/view/'. 
									$response['ProgramResponse']['id'].'">View</a> | <a>Mark Expired</a>';
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
			$this->ProgramResponse->Program->recursive = -1;
			$program = $this->ProgramResponse->Program->findById($id);
			if($program['Program']['approval_required'] == 1){
				$approvalPermission = $this->Acl->check(array(
					'model' => 'User', 
					'foreign_key' => $this->Auth->user('id')), 'ProgramResponses/admin_approve', '*');
				$this->set(compact('approvalPermission'));
			}			
		}	
	}

	function admin_view($id, $type=null) {
		$title_for_layout = 'Program Response';
		if($this->RequestHandler->isAjax()){
			$programResponse = $this->ProgramResponse->findById($id);
			if($type == 'user') {
				FireCake::log($programResponse);
				$user = $programResponse['User'];
				$this->set(compact('user'));
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
			
		}
	}

	function admin_approve() {
		
	}
	
}