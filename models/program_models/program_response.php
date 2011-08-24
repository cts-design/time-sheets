<?php

class ProgramResponse extends AppModel {
	
	var $name = 'ProgramResponse';
	
	var $hasMany = array('ProgramResponseDoc');
	
	var $belongsTo = array('Program', 'User');
	
	var $validate = array();
	
	function getProgramResponse($programId, $userId) {
		$this->recursive = -1;
		$programResponses = $this->find('all', array(
			'conditions' => array(
				'ProgramResponse.user_id' => $userId,
				'ProgramResponse.program_id' => $programId)));
		$date = date('Y-m-d H:i:s');
		$completedResponse = Set::extract('/ProgramResponse[complete=1]', $programResponses);
		$openResponse = Set::extract("/ProgramResponse[expires_on>$date]", $programResponses);
		if(!empty($completedResponse)) {
			$programResponse = $completedResponse[0];
		}
		elseif(!empty($openResponse)) {
			$programResponse = $openResponse[0];
		}
		else {
			$programResponse = null;
		}
		return $programResponse;		
	}
}