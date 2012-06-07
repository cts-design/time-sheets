<?php

class ExpireProgramResponsesShell extends Shell {

	public $uses = array('Program', 'Queue.QueuedTask');	

	public function main() {
		$this->Program->Contain(array(
				'ProgramResponse' => array(
					'User' => array('fields' => array('firstname', 'lastname', 'email')),
					'conditions' => array('status' => 'incomplete'),
					'fields' => array('id', 'expires_on')),
				'ProgramEmail' => array(
					'conditions' => array('type' => array('expired', 'expiring_soon'), 'disabled' => 0),
					'fields' => 'id, from, body, subject, type')));
		$programs = $this->Program->find('all', array('fields' => array('send_expiring_soon')));	
		$now = date('Y-m-d H:i:s');
		foreach($programs as $program) {
			$expiringSoon = date('Y-m-d', strtotime($now . "+" . $program['Program']['send_expiring_soon'] . " day"));
			$expiredEmail = Set::extract('/ProgramEmail[type=expired]/.[1]', $program);
			$expiringSoonEmail = Set::extract('/ProgramEmail[type=expiring_soon]/.[1]', $program);
			foreach($program['ProgramResponse'] as $response) {
				if($now > $response['expires_on']) {
					if($this->Program->ProgramResponse->expireResponse($response['id'])) {
						if(!empty($expiredEmail)) {
							$data['settings']['to'] = $response['User']['firstname'] . ' ' . 
								$response['User']['lastname'] .' <'. $response['User']['email']. '>';			
							if(!empty($expiredEmail[0])) {
								$data['settings']['from'] = $expiredEmail[0]['from'];
							}
							$data['settings']['subject'] = $expiredEmail[0]['subject'];
							$data['vars']['text'] = $expiredEmail[0]['body'];
						}
					}
				}
				elseif(date('Y-m-d',strtotime($response['expires_on'])) === $expiringSoon && !empty($expiringSoonEmail)) {
					$data['settings']['to'] = $response['User']['firstname'] . ' ' . 
							$response['User']['lastname'] .' <'. $response['User']['email']. '>';			
					if(!empty($expiringSoonEmail[0])) {
						$data['settings']['from'] = $expiringSoonEmail[0]['from'];
					}
					$data['settings']['subject'] = $expiringSoonEmail[0]['subject'];
					$data['vars']['text'] = $expiringSoonEmail[0]['body'];
				}
				if(!empty($data)) {
					if(!$data['settings']['from']) {
						$data['settings']['from'] = Configure::read('System.email');
					}
					$data['settings']['sendAs'] = 'text';
					$data['settings']['template'] = 'programs';
					$this->QueuedTask->createJob('email', $data);
				}
			}		
		}
	}
}
