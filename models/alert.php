<?php

class Alert extends AppModel {
	var $name = 'Alert';
	var $displayField = 'title';

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);
	
	function getSelfSignAlerts($selfSign, $kioskName) {
		$buttons = array();	
		if(isset($selfSign['level_1'])) {
			$buttons[0] = $selfSign['level_1'];
		}
		if(isset($selfSign['level_2'])) {
			$buttons[1] = $selfSign['level_2'];
		}
		if(isset($selfSign['level_3'])) {
			$buttons[2] = $selfSign['level_3'];
		}
		$alerts = $this->find('all', array(
			'conditions' => array(
				'Alert.type' => 'selfSign',
				'Alert.disabled' => 0,
				'Alert.watched_id' => $buttons)));
		$user = $this->User->UserTransaction->find('first', 
			array('conditions' => array('UserTransaction.user_id' => $selfSign['user_id']),
				  'order' => array('UserTransaction.id DESC')));  			
		if($alerts && $user) {
			$data = array();
			$i = 0;
			foreach($alerts as $alert) {
				if($selfSign['location_id'] !== $alert['Alert']['location_id']) {
					continue;
				}			
				$data[$i]['username'] = strtolower($alert['User']['windows_username']);
				$data[$i]['email'] = $alert['User']['email'];
				$data[$i]['send_email'] = $alert['Alert']['send_email'];
 				$data[$i]['title'] = 'Self Sign';
				$message = $user['User']['firstname'] . ' ' . $user['User']['lastname'];
				$message .= ' self signed into ' . $user['UserTransaction']['details'];
				$message .= ' on ' . $kioskName .  ' at ' .  $user['UserTransaction']['location'];
				$data[$i]['message'] = $message;
				$data[$i]['url'] = Router::url('/admin/self_sign_logs', true);							
				$i++;
			}
			return $data;	
		}
		else return false;
	}
}