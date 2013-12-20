<?php

class TestController extends AppController
{
	var $uses = array();
	var $name = 'Test';
	var $components = array('Auth', 'Session');

	public function beforeFilter()
	{
		$this->Auth->allowedActions = array(
			'login'
		);
	}

	public function login()
	{
		$child_ssn_length 	= Configure::read('Login.child.ssn_length');

		$username 			= 'Shering';
		$password			= 11111;

		$this->loadModel('User');
		$this->User->recursive = -1;

		$conditions = array(
			'User.username' => $username
		);

		switch($child_ssn_length)
		{
			case 5:
				$conditions['User.ssn LIKE'] = '____' . $password;
				break;
			case 4:
				$conditions['User.ssn LIKE'] = '_____' . $password;
				break;
			case 9:
				$conditions['User.ssn'] =  $password;
		}

		$user = $this->User->find('first', array(
			'conditions' => $conditions
		));

		$this->Auth->login($user['User']['id']);
	}
}