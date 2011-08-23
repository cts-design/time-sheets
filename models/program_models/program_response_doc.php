<?php
class ProgramResponseDoc extends AppModel {
	var $name = 'ProgramResponseDoc';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ProgramResponse' => array(
			'className' => 'ProgramResponse',
			'foreignKey' => 'program_response_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	function processResponseDoc($data, $user) {	
		$Program = ClassRegistry::init('Program');
		$this->data = $data;	
		$watchedCat = $Program->WatchedFilingCat->findByCatId($this->data['FiledDocument']['cat_3']);
		$return['cat_id'] = $this->data['FiledDocument']['cat_3'];
		$programResponseDocId = $this->field('id', array('doc_id' => $this->data['FiledDocument']['id']));
		if($programResponseDocId) {
			$this->data['ProgramResponseDoc']['id'] = $programResponseDocId;
		}
		if($watchedCat) {	
		$programResponse = $this->ProgramResponse->find('first', array(
			'conditions' => array(
				'ProgramResponse.user_id' => $user['User']['id'],
				'ProgramResponse.program_id' => $watchedCat['Program']['id'],
				'ProgramResponse.expires_on >= ' => date('Y-m-d H:i:s')
			),
			'order' => array('ProgramResponse.id DESC')));	
			$return['program_id'] = $watchedCat['Program']['id'];				
			$this->data['ProgramResponseDoc']['cat_id'] = $this->data['FiledDocument']['cat_3'];
			$this->data['ProgramResponseDoc']['doc_id'] = $this->data['FiledDocument']['id'];
			$this->data['ProgramResponseDoc']['program_response_id'] = $programResponse['ProgramResponse']['id'];
			$allWatchedCats = $Program->WatchedFilingCat->find('all', array('conditions' => array(
				'WatchedFilingCat.program_id' => $watchedCat['Program']['id'],
				'DocumentFilingCategory.name !=' => 'rejected',
				'DocumentFilingCategory.name !=' => 'Rejected')));
			$watchedCats = Set::classicExtract($allWatchedCats, '{n}.WatchedFilingCat.cat_id');
			$filedResponseDocCats = $this->getFiledResponseDocCats($watchedCat['Program']['id'], $user['User']['id']);	
			if($this->save($this->data['ProgramResponseDoc'])) {								
				$docFiledEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', array(
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
				$result = array_diff($watchedCats, $filedResponseDocCats);
				if(empty($result)){
					$this->ProgramResponse->id = $programResponse['ProgramResponse']['id'];					
					if($watchedCat['Program']['approval_required'] == 1 && $programResponse['ProgramResponse']['complete'] == 0) {
						$this->ProgramResponse->saveField('needs_approval', 1);
					}
					else{
						$this->ProgramResponse->saveField('complete', 1);
						$finalEmail = $Program->ProgramEmail->find('first', array(
							'conditions' => array(
								'ProgramEmail.program_id' => $watchedCat['Program']['id'],
								'ProgramEmail.type' => 'final')));													
						if($watchedCat['Program']['cert_type'] != 'none'){
							$finalEmail['ProgramEmail']['body'] = $finalEmail['ProgramEmail']['body'] .
							"\r\n" . $programResponse['ProgramResponse']['cert_link'];																				
						}
						if($finalEmail) {
							$return['finalEmail'] = $finalEmail;
						}						
					} 
				}
				if(!empty($result)) {
					$this->ProgramResponse->id = $programResponse['ProgramResponse']['id'];					
					if($watchedCat['Program']['approval_required'] == 1 && !$programResponse['ProgramResponse']['complete']) {
						$this->ProgramResponse->saveField('needs_approval', 0);
					}					
				}	
			}				
		}		
		return $return;		
	}

	function getFiledResponseDocCats($programId, $userId) {
		$filedResponseDocs = $this->find('all', array(
			'conditions' => array(
				'ProgramResponse.program_id' => $programId,
				'ProgramResponse.user_id' => $userId,
				'ProgramResponseDoc.deleted' => 0,
				'ProgramResponseDoc.paper_form' => 0,
				'ProgramResponseDoc.cert' => 0
			),
			'fields' => array(
				'DISTINCT ProgramResponseDoc.cat_id' 
			)));
		$filedResponseDocCats = Set::classicExtract($filedResponseDocs, '{n}.ProgramResponseDoc.cat_id');
		return 	$filedResponseDocCats;	
	}	
	
}
?>