<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */

App::import('Component', 'Email');

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
			$data['settings']['sendAs'] = 'both';
			$data['settings']['template'] = 'programs';
			$data['settings']['subject'] = $programEmail['subject'];
			$data['vars']['text'] = $programEmail['body'];
			return ClassRegistry::init('Queue.QueuedTask')->createJob('email', $data);
		}
		return false;
	}

	function sendEventRegistrationEmail($event=null, $user=null) {
		if($event) {
			if($user) {
				$data['settings']['to'] = $user['User']['firstname'] . ' ' .
				$user['User']['lastname'] .' <'. $user['User']['email']. '>';
			}
			else {
				$data['settings']['to'] = $this->Auth->user('firstname') . ' ' .
				$this->Auth->user('lastname') .' <'. $this->Auth->user('email'). '>';
			}

			$data['settings']['from'] = Configure::read('System.email');
			$data['settings']['sendAs'] = 'both';
			$data['settings']['template'] = 'event_registration';
			$data['settings']['subject'] = $programEmail['subject'];
			$data['vars']['event'] = $event;
			$data['vars']['user'] = $user;
			return ClassRegistry::init('Queue.QueuedTask')->createJob('email', $data);
		}
		return false;
	}

	function sendAbsorptionEmail($mySubject,$myMessage) {
		$this->Email = &new EmailComponent();
		$this->Email->from = Configure::read('Admin.alert.email');
		$this->Email->to = Configure::read('Admin.alert.email');
		$this->Email->subject = Configure::read('domain').": $mySubject";
		$this->Email->send($myMessage);
		$this->Email->reset();
	}
}
