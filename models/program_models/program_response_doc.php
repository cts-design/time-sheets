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
		// :FIXME make this work with cat_1 cat_2 or cat_3
		if(isset($this->data['FiledDocument']['cat_3'])) {
			$watchedCat = $Program->WatchedFilingCat->findByCatId($this->data['FiledDocument']['cat_3']);
			$return['cat_id'] = $this->data['FiledDocument']['cat_3'];			
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
			$this->data['ProgramResponseDoc']['rejected_reason'] = $rejectedReason;				
			$this->data['ProgramResponseDoc']['cat_id'] = $this->data['FiledDocument']['cat_3'];			
			$this->data['ProgramResponseDoc']['doc_id'] = $this->data['FiledDocument']['id'];
			$this->data['ProgramResponseDoc']['program_response_id'] = $programResponse['ProgramResponse']['id'];
			if($this->save($this->data)) {					
				$docFiledEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', array(
					'conditions' => array(
						'ProgramEmail.program_id' => $watchedCat['Program']['id'],
						'ProgramEmail.cat_id' => $return['cat_id'])));				
				if($docFiledEmail['ProgramEmail']['type'] == 'rejected') {				
					$docFiledEmail['ProgramEmail']['body'] = $docFiledEmail['ProgramEmail']['body'] . 
					 "\r\n\r\n\r\n\r\n" . 'Comment: ' . $rejectedReason;
				}
				if($docFiledEmail) {
					$return['docFiledEmail'] = $docFiledEmail;	
				}
				$this->updateResponse($Program, $programResponse)	;
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
				'ProgramResponseDoc.paper_form' => 0,
				'ProgramResponseDoc.cert' => 0
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
		if(empty($result)){
			$this->ProgramResponse->id = $programResponse['ProgramResponse']['id'];					
			if($programResponse['Program']['approval_required'] == 1 && $programResponse['ProgramResponse']['complete'] == 0) {
				$this->ProgramResponse->saveField('needs_approval', 1);
			}
			else{
				$this->ProgramResponse->saveField('complete', 1);
				$finalEmail = $Program->ProgramEmail->find('first', array(
					'conditions' => array(
						'ProgramEmail.program_id' => $programResponse['Program']['id'],
						'ProgramEmail.type' => 'final')));
				if($finalEmail) {
					$return['finalEmail'] = $finalEmail;
				}						
			} 
		}
		if(!empty($result)) {
			$this->ProgramResponse->id = $programResponse['ProgramResponse']['id'];					
			if($programResponse['Program']['approval_required'] == 1 && !$programResponse['ProgramResponse']['complete']) {
				$this->ProgramResponse->saveField('needs_approval', 0);
			}					
		}					
	}	
}
