<?php

class ProgramResponse extends AppModel {

	public $name = 'ProgramResponse';
	public $actsAs = array('Containable');
	public $hasMany = array(
		'ProgramResponseDoc' => array(
			'dependent' => true
		),
		'ProgramResponseActivity' => array(
			'dependent' => true
		)
	);
	public $belongsTo = array(
		'Program' => array(
			'counterCache' => true
		),
		'User'
	);
	public $validate = array();


	public function getProgramResponse($programId, $userId) {
		$programResponse = $this->find('first', array(
			'conditions' => array(
				'ProgramResponse.user_id' => $userId,
				'ProgramResponse.program_id' => $programId
			),
			'order' => array('ProgramResponse.created DESC')
		));
		if($programResponse['ProgramResponse']['expires_on'] <= date('Y-m-d H:i:s') &&
			$programResponse['ProgramResponse']['status'] === 'incomplete') {
				$expiredResponse = $this->expireResponse($programResponse['ProgramResponse']['id']);
				if($expiredResponse) {
					$programResponse = $expiredResponse;
				}
			}
		return $programResponse;
	}

	public function expireResponse($responseId) {
		$this->id = $responseId;
		$this->saveField('status', 'expired');
		$response = $this->findById($responseId);
		if($response['ProgramResponse']['status'] === 'expired') {
			return $response;
		}
		else {
			return false;
		}

	}
}
