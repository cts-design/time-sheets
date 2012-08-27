<?php
class HelpDeskTicket extends AppModel {
	
	public $name = 'HelpDeskTicket';

	public $useTable = false;

	public $validate = array(
		'first_name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your first name.'
			)
		),
		'last_name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter your last name.'
			)
		),
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Must be a valid company email address.'
			)
		),
		'operating_system' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select your operating system.'
			)
		),
		'browser' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select your browser.'
			)
		),
		'url' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the url of page you are having a problem with.'
			),
			'url' => array(
				'rule' => 'url',
				'message' => 'Must be a valid url like http://atlasforworkforce.com.'
			)
		),
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please give a brief description of what is wrong.'
			)
		),
		'issue' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please give a detailed description of what is wrong.'
			)
		)
	);
}
