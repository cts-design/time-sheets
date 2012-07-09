<?php
class ProgramResponseDoc extends AppModel {
	var $name = 'ProgramResponseDoc';

	var $belongsTo = array(
		'ProgramResponse' => array(
			'className' => 'ProgramResponse',
			'foreignKey' => 'program_response_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FiledDocument' => array(
			'className' => 'FiledDocument',
			'foreignKey' => 'doc_id'
		)
	);
	
	function processResponseDoc($data, $user) {	
		$Program = ClassRegistry::init('Program');
		$this->data = $data;
		$watchedCat = null;
		$return = false;
		if(isset($this->data['FiledDocument']['cat_3'])) {
			$watchedCat = $Program->WatchedFilingCat->findByCatId($this->data['FiledDocument']['cat_3']);
			$return['cat_id'] = $this->data['FiledDocument']['cat_3'];			
		}
		elseif(isset($this->data['FiledDocument']['cat_2'])) {
			$watchedCat = $Program->WatchedFilingCat->findByCatId($this->data['FiledDocument']['cat_2']);
			$return['cat_id'] = $this->data['FiledDocument']['cat_2'];			
		}
		elseif(isset($this->data['FiledDocument']['cat_1'])) {
			$watchedCat = $Program->WatchedFilingCat->findByCatId($this->data['FiledDocument']['cat_1']);
			$return['cat_id'] = $this->data['FiledDocument']['cat_1'];			
		}
		$rejectedReason = null;
		if(isset($this->data['FiledDocument']['description'])) {
			$rejectedReason = $this->data['FiledDocument']['description'];
		}
		$programResponseDocId = $this->field('id', array('doc_id' => $this->data['FiledDocument']['id']));
		if($programResponseDocId) {
			$this->data['ProgramResponseDoc']['id'] = $programResponseDocId;
		}
		if(!$watchedCat) {
			$programResponseDoc = $this->findByDocId($this->data['FiledDocument']['id']);
			if($programResponseDoc) {
				$programResponse = $this->ProgramResponse->getProgramResponse($programResponseDoc['ProgramResponse']['program_id'], $user['User']['id']);
				$this->delete($programResponseDoc['ProgramResponseDoc']['id']);
				$this->updateResponse($Program, $programResponse);								
			}
		}		
		if($watchedCat) {	
			$programResponse = $this->ProgramResponse->getProgramResponse($watchedCat['Program']['id'], $user['User']['id']);	
			$return['program_id'] = $watchedCat['Program']['id'];
			if($watchedCat['WatchedFilingCat']['name'] === 'esign') {
				$this->ProgramResponse->User->id = $user['User']['id'];
				$this->ProgramResponse->User->saveField('signature', 1);
				$this->ProgramResponse->User->saveField('signature_created', date('Y-m-d H:i:s'));
			}
			$this->data['ProgramResponseDoc']['rejected_reason'] = $rejectedReason;				
			$this->data['ProgramResponseDoc']['cat_id'] = $return['cat_id'];
			$this->data['ProgramResponseDoc']['doc_id'] = $this->data['FiledDocument']['id'];
			$this->data['ProgramResponseDoc']['program_response_id'] = $programResponse['ProgramResponse']['id'];
			$this->data['ProgramResponseDoc']['type'] = 'customer_provided';
			if($this->save($this->data)) {					
				$docFiledEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', array(
					'conditions' => array(
						'ProgramEmail.program_email_id' => $watchedCat['WatchedFilingCat']['program_email_id'])));				
				if($docFiledEmail['ProgramEmail']['type'] == 'rejected') {				
					$docFiledEmail['ProgramEmail']['body'] = $docFiledEmail['ProgramEmail']['body'] . 
					 "\r\n\r\n\r\n\r\n" . 'Comment: ' . $rejectedReason;
				}
				if($docFiledEmail) {
					$return['docFiledEmail'] = $docFiledEmail;	
				}
				$this->updateResponse($Program, $programResponse);
			}				
		}
		return $return;		
	}

	function getFiledResponseDocCats($programId, $responseId) {
		$DocumentFilingCat = ClassRegistry::init('DocumentFilingCategory');
		$filingCats = $DocumentFilingCat->find('list');
		$filedResponseDocs = $this->find('all', array(
			'conditions' => array(
				'ProgramResponse.id' => $responseId,
				'ProgramResponseDoc.deleted' => 0,
				'ProgramResponseDoc.type' => 'customer_provided'
			),
			'fields' => array(
				'DISTINCT ProgramResponseDoc.cat_id' 
			)));
		$filedResponseDocCats = Set::classicExtract($filedResponseDocs, '{n}.ProgramResponseDoc.cat_id');
		foreach($filedResponseDocCats as $k => $v) {
			if(in_array($filingCats[$v], array('Rejected', 'rejected'))) {
				unset($filedResponseDocCats[$k]);
			}
		}
		return 	$filedResponseDocCats;	
	}

	private function updateResponse($Program, $programResponse) {
		$allWatchedCats = $Program->WatchedFilingCat->find('all', array('conditions' => array(
			'WatchedFilingCat.program_id' => $programResponse['Program']['id'],
			'DocumentFilingCategory.name !=' => 'rejected',
			'DocumentFilingCategory.name !=' => 'Rejected')));
		$watchedCats = Set::classicExtract($allWatchedCats, '{n}.WatchedFilingCat.cat_id');
		$filedResponseDocCats = 
		$this->getFiledResponseDocCats(
			$programResponse['Program']['id'], $programResponse['ProgramResponse']['id']);																
		$result = array_diff($watchedCats, $filedResponseDocCats);
		$this->ProgramResponse->id = $programResponse['ProgramResponse']['id'];					
		if(empty($result)){
			if($programResponse['ProgramResponse']['status'] === 'incomplete' ||
				$programResponse['ProgramResponse']['status'] === 'pending_document_review') {
					$this->ProgramResponse->saveField('status', 'pending_approval');
			}
		}
		if(!empty($result)) {
			if($programResponse['ProgramResponse']['status'] === 'pending_approval') {
				$this->ProgramResponse->saveField('status', 'incomplete');
			}					
		}					
	}	
}
