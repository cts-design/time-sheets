<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */

class NotificationsComponent extends Object {
	
	var $components = array('Email');
	
	function sendProgramEmail($programEmail=null, $user=null) {
		if($programEmail) {
			if($user) {
				$this->Email->to = $user['User']['firstname'] . ' ' . 
				$user['User']['lastname'] .' <'. $user['User']['email']. '>';			
			}
			else {
				$this->Email->to = $this->Auth->user('firstname') . ' ' . 
				$this->Auth->user('lastname') .' <'. $this->Auth->user('email'). '>';		
			}
			if($programEmail['ProgramEmail']['from']) {
				$this->Email->from = $programEmail['ProgramEmail']['from'];
			}
			else {
				$this->Email->from = Configure::read('System.email');
			}				
			$this->Email->subject = $programEmail['ProgramEmail']['subject'];
			return $this->Email->send($programEmail['ProgramEmail']['body']);			
		}
		return false;
	}
}	