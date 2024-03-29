<?php

class Alert extends AppModel {
	public $name = 'Alert';
	public  $displayField = 'title';

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);
	
	public function getSelfSignAlerts($selfSign, $kioskName) {
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
				'Alert.type' => 'self_sign',
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

	public function getSelfScanAlerts($user, $docId) {
		$alerts = $this->find('all', array(
			'conditions' => array(
				'Alert.type' => 'self_scan',
				'Alert.disabled' => 0,
				'Alert.watched_id' => $user['User']['id'])));
		if($alerts && $user) {
			$data = array();			
			$i = 0;
			foreach($alerts as $alert) {			
				$data[$i]['username'] = strtolower($alert['User']['windows_username']);
				$data[$i]['email'] = $alert['User']['email'];
				$data[$i]['send_email'] = $alert['Alert']['send_email'];
 				$data[$i]['title'] = 'Self Scan';
				$message = $user['User']['firstname'] . ' ' . $user['User']['lastname'];
				$message .= ' self scanned document id: ' . $docId;
				$data[$i]['message'] = $message;
				$data[$i]['url'] = Router::url('/admin/queued_documents', true);							
				$i++;
			}
			return $data;	
		}
		else return false;
	}

	public function getSelfScanCategoryAlerts($user, $selfScanCat, $docId, $locationId) {
		$ids = array($selfScanCat['SelfScanCategory']['id']);
		if($selfScanCat['SelfScanCategory']['parent_id']) {
			array_push($ids, $selfScanCat['SelfScanCategory']['parent_id']);
		}
		$alerts = $this->find('all', array(
			'conditions' => array(
				'Alert.type' => 'self_scan_category',
				'Alert.disabled' => 0,
				'Alert.watched_id' => $ids)));
		if($alerts && $user) {
			$data = array();			
			$i = 0;
			foreach($alerts as $alert) {			
				if($alert['Alert']['location_id'] && $locationId !== $alert['Alert']['location_id']) {
					continue;
				}			
				$data[$i]['username'] = strtolower($alert['User']['windows_username']);
				$data[$i]['email'] = $alert['User']['email'];
				$data[$i]['send_email'] = $alert['Alert']['send_email'];
 				$data[$i]['title'] = 'Self Scan Category';
				$message = $user['User']['firstname'] . ' ' . $user['User']['lastname'];
				$message .= ' self scanned document id: ' . $docId;
				$message .= ' to category ' . $selfScanCat['SelfScanCategory']['name'];
				$data[$i]['message'] = $message;
				$data[$i]['url'] = Router::url('/admin/queued_documents', true);							
				$i++;
			}
			return $data;	
		}
		else return false;
	}
	
	public function getCusFiledDocAlerts($user, $docId) {
		$alerts = $this->find('all', array(
			'conditions' => array(
				'Alert.type' => 'customer_filed_document',
				'Alert.disabled' => 0,
				'Alert.watched_id' => $user['User']['id'])));
		if($alerts && $user) {
			$data = array();			
			$i = 0;
			foreach($alerts as $alert) {		
				$data[$i]['username'] = strtolower($alert['User']['windows_username']);
				$data[$i]['email'] = $alert['User']['email'];
				$data[$i]['send_email'] = $alert['Alert']['send_email'];
 				$data[$i]['title'] = 'Document filed to customer';
				$message = 'Document id: ' . $docId . ' filed to customer ';
				$message .= $user['User']['firstname'] . ' ' . $user['User']['lastname'];				
				$data[$i]['message'] = $message;
				$data[$i]['url'] = Router::url('/admin/filed_documents/index/'.$user['User']['id'], true);							
				$i++;
			}
			return $data;	
		}
		else return false;
	}	

	public function getStaffFiledDocAlerts($admin, $docId, $customer) {
		$alerts = $this->find('all', array(
			'conditions' => array(
				'Alert.type' => 'staff_filed_document',
				'Alert.disabled' => 0,
				'Alert.watched_id' => $admin['User']['id'])));
		if($alerts && $admin && $customer) {
			$data = array();			
			$i = 0;
			foreach($alerts as $alert) {		
				$data[$i]['username'] = strtolower($alert['User']['windows_username']);
				$data[$i]['email'] = $alert['User']['email'];
				$data[$i]['send_email'] = $alert['Alert']['send_email'];
 				$data[$i]['title'] = 'Staff filed document';
				$message = $admin['User']['firstname'] . ' ' . $admin['User']['lastname'];
				$message .= ' filed document id: ' . $docId;
				$message .= ' to customer: ' . $customer['User']['firstname'] . ' ' . $customer['User']['lastname'];
				$data[$i]['message'] = $message;
				$data[$i]['url'] = Router::url('/admin/filed_documents/view/'.$docId, true);							
				$i++;
			}
			return $data;	
		}
		else return false;
	}

	public function getCustomerDetailsAlerts($detail, $user, $kiosk) {
		$alerts = $this->find('all', array(
			'conditions' => array(
				'Alert.type'  => 'customer_details',
				'Alert.detail' => $detail,
				'Alert.disabled' => 0)));
		if($alerts) {
			$data = array();
			$i = 0;
			foreach($alerts as $alert) {
				if($alert['Alert']['location_id']) {
					if($alert['Alert']['location_id'] !== $kiosk['Kiosk']['location_id']) {
						continue;
					}
				}
				$data[$i]['username'] = strtolower($alert['User']['windows_username']);
				$data[$i]['email'] = $alert['User']['email'];
				$data[$i]['send_email'] = $alert['Alert']['send_email'];
 				$data[$i]['title'] = 'Customer Details';
				$message = 'Customer ' . $user['User']['firstname'] . ' ' . $user['User']['lastname'];
				$message .= ' logged in to ' . $kiosk['Kiosk']['location_description'];
				$message .= ' at ' . $kiosk['Location']['name']; 
				$message .= ' with detail ' . '"' . ucfirst($detail) . '".';
				$data[$i]['message'] = $message;
				$data[$i]['url'] = '';							
				$i++;				
			}
			return $data;
		}
		else return false;		
	}

	public function getCustomerLoginAlerts($user, $kiosk) {
		$alerts = $this->find('all', array(
			'conditions' => array(
				'Alert.type' => 'customer_login',
				'Alert.disabled' => 0,
				'Alert.watched_id' => $user['User']['id'])));
		if($alerts && $user) {
			$data = array();			
			$i = 0;
			foreach($alerts as $alert) {			
				$data[$i]['username'] = strtolower($alert['User']['windows_username']);
				$data[$i]['email'] = $alert['User']['email'];
				$data[$i]['send_email'] = $alert['Alert']['send_email'];
 				$data[$i]['title'] = 'Customer Login';
				$message = $user['User']['firstname'] . ' ' . $user['User']['lastname'];
				$message .= ' logged in to ' . $kiosk['Kiosk']['location_description'];
				$message .= ' at ' . $kiosk['Location']['name'];
				$data[$i]['message'] = $message;
				$data[$i]['url'] = '';
				$i++;
			}
			return $data;	
		}
		else return false;
	}	

	public function getProgramResponseStatusAlerts($user, $program, $status) {
		$alerts = $this->find('all', array(
			'conditions' => array(
				'Alert.type' => 'program_response_status',
				'Alert.disabled' => 0,
				'Alert.detail' => $status,
				'Alert.watched_id' => $program['Program']['id'])));
		$statuses = array('incomplete' => 'open', 'complete' => 'closed', 'pending_approval' => 'pending_approval');
		if($alerts && $user) {
			$data = array();			
			$i = 0;
			foreach($alerts as $alert) {			
				$data[$i]['username'] = strtolower($alert['User']['windows_username']);
				$data[$i]['email'] = $alert['User']['email'];
				$data[$i]['send_email'] = $alert['Alert']['send_email'];
 				$data[$i]['title'] = 'Program Response Status';
				$message = $user['User']['firstname'] . ' ' . $user['User']['lastname'] . "'s";
				$message .= ' program response status has changed to ' . $statuses[$status];
				$message .= ' for program ' . $program['Program']['name'];
				$data[$i]['message'] = $message;
				$data[$i]['url'] = '';
				$i++;
			}
			return $data;	
		}
		else return false;
	}	
}
