<?php

class StaticController extends AppController
{
	var $uses = array();
	var $name = 'static';
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('registration_privacy', 'kid_script');
	}

	function registration_privacy()
	{

	}

	function kid_script()
	{

	}
}