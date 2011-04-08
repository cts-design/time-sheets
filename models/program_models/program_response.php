<?php

class ProgramResponse extends AppModel {
	
	var $name = 'ProgramResponse';
	
	var $hasMany = array('ProgramResponseDoc');
	
	var $belongsTo = array('Program');
	
	var $validate = array();
	
	function processResponseDoc($data, $user) {
		$this->data = $data;	
		$watchedCat = $this->Program->WatchedFilingCat->findByCatId($this->data['FiledDocument']['cat_3']);
		$return['cat_id'] = $this->data['FiledDocument']['cat_3'];
		if($watchedCat) {	
			$userResponse = $this->find('first', array('conditions' => array(
				'ProgramResponse.user_id' => $user['User']['id'],
				'ProgramResponse.program_id' => $watchedCat['Program']['id']
				)));
			$return['program_id'] = $watchedCat['Program']['id'];				
			$this->data['ProgramResponseDoc']['cat_id'] = $this->data['FiledDocument']['cat_3'];
			$this->data['ProgramResponseDoc']['doc_id'] = $this->data['FiledDocument']['id'];
			$this->data['ProgramResponseDoc']['program_response_id'] = $userResponse['ProgramResponse']['id'];
			$allWatchedCats = $this->Program->WatchedFilingCat->find('all', array('conditions' => array(
				'WatchedFilingCat.program_id' => $watchedCat['Program']['id'],
				'DocumentFilingCategory.name !=' => 'rejected')));
			$watchedCats = Set::classicExtract($allWatchedCats, '{n}.WatchedFilingCat.cat_id');
			$filedResponseDocCats = $this->getFiledResponseDocCats($watchedCat['Program']['id'], $user['User']['id']);	
			if(!in_array($this->data['FiledDocument']['cat_3'], $filedResponseDocCats))	{
				if($this->ProgramResponseDoc->save($this->data['ProgramResponseDoc'])) {								
					$docFiledEmail = $this->Program->ProgramEmail->find('first', array(
						'conditions' => array(
							'ProgramEmail.program_id' => $watchedCat['Program']['id'],
							'ProgramEmail.cat_id' => $this->data['FiledDocument']['cat_3'])));				
					if($docFiledEmail['ProgramEmail']['type'] == 'rejected') {				
						$docFiledEmail['ProgramEmail']['body'] = $docFiledEmail['ProgramEmail']['body'] . 
						 "\r\n" . $this->data['FiledDocument']['description'];
					}
					if($docFiledEmail) {
						$return['docFiledEmail'] = $docFiledEmail;	
					}														
					$filedResponseDocCats = $this->getFiledResponseDocCats($watchedCat['Program']['id'], $user['User']['id']);	
					if(Set::contains($watchedCats, $filedResponseDocCats)){
						$this->id = $userResponse['ProgramResponse']['id'];					
						if($watchedCat['Program']['approval_required'] == 1) {
							$this->saveField('needs_approval', 1);
						}
						else{
							$this->saveField('complete', 1);
							$finalEmail = $this->Program->ProgramEmail->find('first', array(
								'conditions' => array(
									'ProgramEmail.program_id' => $watchedCat['Program']['id'],
									'ProgramEmail.type' => 'final')));													
							if($watchedCat['Program']['cert_type'] != 'none'){
								$response = $this->find('first', array('conditions' => array(
									'ProgramResponse.program_id' => $watchedCat['Program']['id'],
									'ProgramResponse.user_id' => $user['User']['id'])));
								$finalEmail['ProgramEmail']['body'] = $finalEmail['ProgramEmail']['body'] .
								"\r\n" . $response['ProgramResponse']['cert_link'];																				
							}
							if($finalEmail) {
								$return['finalEmail'] = $finalEmail;
							}						
						} 
					}	
				}				
			}		
		}
		return $return;		
	}
	
	function getFiledResponseDocCats($programId, $userId) {
		$filedResponseDocs = $this->ProgramResponseDoc->find('all', array('conditions' => array(
			'ProgramResponse.program_id' => $programId,
			'ProgramResponse.user_id' => $userId)));
		$filedResponseDocCats = Set::classicExtract($filedResponseDocs, '{n}.ProgramResponseDoc.cat_id');
		return 	$filedResponseDocCats;	
	}
	
}