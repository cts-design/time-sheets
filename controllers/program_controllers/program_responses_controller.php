<?php 

class ProgramResponsesController extends AppController {
	
	var $name = 'ProgramResponses';
	
	var	$components = array('Email');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->ProgramResponse->Program->ProgramField->recursive = -1;
		if(!empty($this->params['pass'][0]) && $this->params['action'] == 'index') {
			$query = $this->ProgramResponse->Program->ProgramField->findAllByProgramId($this->params['pass'][0]); 
			$fields = Set::classicExtract($query, '{n}.ProgramField');
			foreach($fields as $k => $v) {
				if(!empty($v['validation'])) 
					$validate[$v['name']] = json_decode($v['validation'], true); 
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
			$this->data['ProgramResponse']['id'] = $response['ProgramResponse']['id'];
			$this->data['ProgramResponse']['answers'] = json_encode($this->data['ProgramResponse']);
			$this->data['ProgramResponse']['program_id'] = $id;
			if($this->ProgramResponse->save($this->data)) {
				$this->_emailCustomer($id, 'form');
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
				$this->_emailCustomer($id, 'docUpload');
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
	
	function _emailCustomer($id = null, $type) {
		if($id) {
			$email = $this->ProgramResponse->Program->ProgramEmail->find('first', array('conditions' => array(
			'ProgramEmail.program_id' => $id,
			'ProgramEmail.type' => $type )));
		}
		if($email) {
			$this->Email->to = $this->Auth->user('firstname') . ' ' . $this->Auth->user('lastname') .' <'. $this->Auth->user('email'). '>';
			$this->Email->from = Configure::read('System.email');
			$this->Email->subject = $email['ProgramEmail']['subject'];
			return $this->Email->send($email['ProgramEmail']['body']);			
		}
		return false;
	}
}