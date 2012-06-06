<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */

class NotificationsComponent extends Object {
	
	var $components = array('Auth');
	
	function sendProgramEmail($programEmail=null, $user=null) {
		if($programEmail) {
			if($user) {
				$data['settings']['to'] = $user['User']['firstname'] . ' ' . 
				$user['User']['lastname'] .' <'. $user['User']['email']. '>';			
			}
			else {
				$data['settings']['to'] = $this->Auth->user('firstname') . ' ' . 
				$this->Auth->user('lastname') .' <'. $this->Auth->user('email'). '>';		
			}
			if($programEmail['from']) {
				$data['settings']['from'] = $programEmail['from'];
			}
			else {
				$data['settings']['from'] = Configure::read('System.email');
			}				
			$data['settings']['sendAs'] = 'text';
			$data['settings']['template'] = 'programs';
			$data['settings']['subject'] = $programEmail['subject'];
			$data['vars']['text'] = $programEmail['body'];
			return ClassRegistry::init('Queue.QueuedTask')->createJob('email', $data);
		}
		return false;
	}
}	
