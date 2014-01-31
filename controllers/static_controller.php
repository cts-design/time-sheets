<?php

class StaticController extends AppController
{
	var $uses = array();
	var $name = 'static';
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('registration_privacy');
	}

	function registration_privacy()
	{

	}
}