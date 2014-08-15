<?php

class TestController extends AppController
{
	var $uses = array();
	var $name = 'Test';
	var $components = array('Auth', 'Session', 'Email');

	public function beforeFilter()
	{
		$this->Auth->allowedActions = array(
			'login',
			'video',
			'index'
		);
	}

	public function index() {
		$this->autoRender = FALSE;
		$this->loadModel('Setting');


		$settings = $this->Setting->getSettings('SelfSign', 'KioskRegistration');

		var_dump($settings);
	}

	public function email()
	{
		$this->autoRender = FALSE;
		$this->Email->to = 'flynnarite@gmail.com';
		$this->Email->subject = 'Success';

		$this->loadModel('Setting');
		$cc = $this->Setting->getEmails();

		if(count($cc))
			$this->Email->cc = $cc;
		
		$this->Email->send('We have cc\'d the people');
	}

	public function video()
	{
		$nextModule = array(
			array(
				'media_location' => 'swf/moviee.swf',
				'instructions' => 'Do stuff',
				'media_type' => 'swf',
				'id' => 1
			)
		);

		$modResponseTimeId = 100;

		$media = 'swf/movie.swf';

		$this->set(compact('nextModule', 'modResponseTimeId', 'media'));
		//$this->render('../ecourse_views/ecourses/media');
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