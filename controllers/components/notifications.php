<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */

class NotificationsComponent extends Object {
	
	var $components = array('Email', 'Auth');
	
	function sendProgramEmail($programEmail=null, $user=null) {
		if($programEmail) {
			if($user) {
				$email['email']['to'] = $user['User']['firstname'] . ' ' . 
				$user['User']['lastname'] .' <'. $user['User']['email']. '>';			
			}
			else {
				$email['email']['to'] = $this->Auth->user('firstname') . ' ' . 
				$this->Auth->user('lastname') .' <'. $this->Auth->user('email'). '>';		
			}
			if($programEmail['from']) {
				$email['email']['from'] = $programEmail['from'];
			}
			else {
				$email['email']['from'] = Configure::read('System.email');
			}				
			$email['email']['subject'] = $programEmail['subject'];
			$email['email']['body'] = $programEmail['body'];
			$options = array('priority' => 5000, 'tube' => 'program_email');
			return ClassRegistry::init('Queue.Job')->put($email, $options);
		}
		return false;
	}
}	
