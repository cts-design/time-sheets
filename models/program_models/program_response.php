<?php

class ProgramResponse extends AppModel {
	var $name = 'ProgramResponse';
	
	var $hasMany = array('ProgramResponseDoc');
	
	var $belongsTo = array('Program');
	
	var $validate = array();
	
	
	function processResponseDoc($data, $user) {
		$this->data = $data;	
		$watchedCat = $this->Program->WatchedFilingCat->findByCatId($this->data['FiledDocument']['cat_3']);
		if($watchedCat) {
			debug($watchedCat); 	
			$userResponse = $this->find('first', array('conditions' => array(
				'ProgramResponse.user_id' => $user['User']['id'],
				'ProgramResponse.program_id' => $watchedCat['Program']['id']
				)));			
			debug($userResponse);
			$this->data['ProgramResponseDoc']['cat_id'] = $this->data['FiledDocument']['cat_3'];
			$this->data['ProgramResponseDoc']['doc_id'] = $this->data['FiledDocument']['id'];
			$this->data['ProgramResponseDoc']['program_response_id'] = $userResponse['ProgramResponse']['id'];
			if($this->ProgramResponseDoc->save($this->data['ProgramResponseDoc'])) {
				$count = $this->ProgramResponseDoc->find('count', array('conditions' => array(
					'ProgramResponseDoc.program_response_id' => $userResponse['ProgramResponse']['id'])));
				if($count == $userResponse['Program']['number_required_docs'])	{
					debug('true'); die;
				}	
			}		
		}		
	}
	
	
}