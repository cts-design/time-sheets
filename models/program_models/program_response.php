<?php

class ProgramResponse extends AppModel {
	
	var $name = 'ProgramResponse';
	
	var $hasMany = array('ProgramResponseDoc');
	
	var $belongsTo = array('Program', 'User');
	
	var $validate = array();
	
	function getProgramResponse($programId, $userId) {
		$programResponses = $this->find('all', array(
			'conditions' => array(
				'ProgramResponse.user_id' => $userId,
				'ProgramResponse.program_id' => $programId)));
		foreach($programResponses as $programResponse) {
			if($programResponse['ProgramResponse']['complete']) {
				$return = $programResponse;
				break;
			}
			elseif($programResponse['ProgramResponse']['expires_on'] > date('Y-m-d H:i:s')) {
				$return = $programResponse;;
				break;
			}
		}
		if(empty($return)) {
			$return = null;
		}
		return $return;		
	}
}