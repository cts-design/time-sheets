<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class User extends AppModel {

	public $name = 'User';

	
	public $actsAs = array(
		'AtlasAcl' => 'requester',
		'Multivalidatable',
		'Disableable'
	);

	public $actAs = array(
		'Acl' => array(
			'type' => 'requester',
			'enabled' => 'false'
		)
	);

	public $belongsTo = array(
		'Role',
		'Location'
	);

	public $hasAndBelongsToMany = array(
		'Audit' => array(
			'joinTable' => 'audits_auditors',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'audit_id',
			'unique' => false
		)
	);


	public $hasMany = array(
		'SelfSignLog' => array(
			'className' => 'SelfSignLog',
			'foreignKey' => 'user_id'
		),
		'SelfSignLogArchive' => array(
			'className' => 'SelfSignLogArchive',
			'foreignKey' => 'user_id'
		),
		'UserTransaction' => array(
			'className' => 'UserTransaction',
			'foreignKey' => 'user_id'
		),
		'FiledDocument' => array(
			'className' => 'FiledDocument',
			'foreignKey' => 'user_id'
		),
		'QueuedDocument' => array(
			'className' => 'QueuedDocument',
			'foreignKey' => 'user_id'
		),
		'EventRegistration' => array(
			'className' => 'EventRegistration',
			'foreignKey' => 'user_id'
		),
		'EcourseUser' => array(
			'className' => 'EcourseUser',
			'foreignKey' => 'user_id'
		)
	);

	public $hasOne = array(
		'QueuedDocument' => array(
			'className' => 'QueuedDocument',
			'foreignKey' => 'locked_by'
		)
	);

	public $validate = array(
		'firstname' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a first name.',
				'required' => false
			),
			'maxlength' => array(
				'rule' => array('maxlength', 50),
				'message' => 'This field cannot excced 50 characters.',
				'required' => false
			)
		),
		'lastname' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a last name.',
				'required' => false
			),
			'maxlength' => array(
				'rule' => array('maxlength', 50),
				'message' => 'This field cannot excced 50 characters.',
				'required' => false
			),
		),
		'ssn' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a SSN.',
				'required' => false
			),
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please provide only numbers, no spaces or dashes.',
				'required' => false
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Sorry, we are unable to register you with the provided SSN.',
				'required' => false
			),
			'minLength' => array(
				'rule' => array('minLength', 9),
				'message' => 'SSN must be full 9 digits.',
				'required' => false
			)
		),
		'ssn_confirm' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please confirm the SSN.',
				'required' => false
			),
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please provide only numbers, no spaces or dashes.',
				'required' => false
			),
			'verify' => array(
				'rule' => array('verifies', 'ssn'),
				'message' => 'SSNs do not match.',
				'required' => false
			),
			'minLength' => array(
				'rule' => array('minLength', 9),
				'message' => 'SSN must be full 9 digits.',
				'required' => false
			)
		),
		'address_1' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a street address.',
				'on' => 'create',
				'required' => false
			)
		),
		'city' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a city.',
				'required' => false
			)
		),
		'county' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a county.',
				'required' => false
			)
		),
		'state' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a state',
				'required' => false
			)
		),
		'zip' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please provide only numbers, no spaces or dashes.',
				'required' => false
			),
			'minLength' => array(
				'rule' => array('minLength', 5),
				'message' => 'Please enter 5 digit zip code.',
				'required' => false
			),
			'maxLength' => array(
				'rule' => array('maxLength', 5),
				'message' => 'Please enter 5 digit zip code.',
				'required' => false
			)
		),
		'phone' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a phone number',
				'required' => false
			),
			'phone' => array(
				'rule' => array('maxLength', 20),
				'message' => 'Please no more than 20 characters.',
				'required' => false
			)
		),
		'alt_phone' => array(
			'phone' => array(
				'rule' => array('maxLength', 20),
				'message' => 'Please no more than 20 characters.',
				'required' => false
			)
		),
		'gender' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select a gender.',
				'required' => false
			)
		),
		'dob' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide date of birth.',
				'required' => false
			),
			'date' => array(
				'rule' => array('date', 'mdy'),
				'message' => 'Please provide a valid date in this format mm/dd/yyyy.',
				'required' => false
			)
		),
		'email' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide an email address.',
				'required' => false
			),
			'email' => array(
				'rule' => 'email',
				'message' => 'Must be a vaild email like bob@test.com.',
				'required' => false
			)
		),
		'email_confirm' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please confirm the email address',
				'required' => false
			),
			'verify' => array(
				'rule' => array('verifies', 'email'),
				'message' => 'Email addresses do not match.',
				'required' => false
			),
		),
		'language' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a language.',
				'required' => false
			)
		),
		'ethnicity' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select a ethnicity.',
				'required' => false
			)
		),
		'race' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select race.',
				'required' => false
			)
		),
		'veteran' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select yes or no.',
				'required' => false
			)
		)
	);

	public $validationSets = array(
		'customerMinimum' => array(
			'firstname' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a first name.',
					'required' => false
				),
				'maxlength' => array(
					'rule' => array('maxlength', 50),
					'message' => 'This field cannot excced 50 characters.',
					'required' => false
				)
			),
			'lastname' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a last name.',
					'required' => false
				),
				'maxlength' => array(
					'rule' => array('maxlength', 50),
					'message' => 'This field cannot excced 50 characters.',
					'required' => false
				)
			),
			'ssn' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a SSN.',
					'required' => false
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
					'required' => false
				),
				'unique' => array(
					'rule' => 'isUnique',
					'message' => 'Sorry, we are unable to register you with the provided SSN.',
					'required' => false
				),
				'minLength' => array(
					'rule' => array('minLength', 9),
					'message' => 'SSN must be at least 9 digits',
					'required' => false
				)
			),
			'ssn_confirm' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the SSN.',
					'required' => false
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
					'required' => false
				),
				'verify' => array(
					'rule' => array('verifies', 'ssn'),
					'message' => 'SSNs do not match.',
					'required' => false
				),
				'minLength' => array(
					'rule' => array('minLength', 4),
					'message' => 'SSN must be at least 4 digits',
					'required' => false
				)
			)
		),
		'passwordKioskLogin' => array(
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Your Last name is required'
				)
			),
			'password' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Your 9 digit SSN is required'
				)
			)
		),
		'ssnKioskLogin' => array(
			'lastname' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Your Last name is required'
				)
			),
			'ssn' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Your 9 digit SSN is required'
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Your SSN can only consist of numbers'
				),
				'minLength' => array(
					'rule' => array('minLength', 9),
					'message' => 'Your SSN must be at least 9 digits long'
				)
			)
		),
		'auditor' => array(
			'ssn' => array(
				'rule' => 'notEmpty',
				'required' => false,
				'allowBlank' => true
			),
			'username' => array(
				'rule' => 'isUnique',
				'message' => 'This username already exists in the system'
			)
		),
		'auditor_login' => array(
			'username' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a username'
			),
			'password' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a password'
			)
		),
		'customerLogin' => array(
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Last name field is required.'
				)
			)
		),
		'last9ssn' => array(
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Last name field is required.'
				)
			)
		),
		'last5ssn' => array(
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Last name field is required.'
				)
			)
		),
		'last4ssn' => array(
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Last name field is required.'
				)
			)
		),
		'childLogin' => array(
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Last name field is required.'
				)
			)
		),
		'admin' => array(
			'firstname' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a first name.'
				),
				'maxlength' => array(
					'rule' => array('maxlength', 50),
					'message' => 'This field cannot excced 50 characters.'
				),
			),
			'lastname' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a last name.'
				),
				'maxlength' => array(
					'rule' => array('maxlength', 50),
					'message' => 'This field cannot excced 50 characters.'
				),
			),
			'username' => array(
				'unique' => array(
					'rule' => 'isUnique',
					'message' => 'The username entered already exists in the system.',
					'on' => 'create'
				),
			),
			'pass' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a password',
					'on' => 'create'
				),
				'minlength' => array(
					'rule' => array('minlength', 6),
					'message' => 'Password must be atleast 6 characters.',
					'required' => false
				),
				'maxlength' => array(
					'rule' => array('maxlength', 25),
					'message' => 'This password cannot excced 25 characters.'
				)
			),
			'email' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide an email address.',
					'on' => 'create'
				),
				'email' => array(
					'rule' => 'email',
					'message' => 'Must be a vaild email like bob@test.com.',
					'allowEmpty' => true
				),
				'unique' => array(
					'rule' => 'isUnique',
					'message' => 'The email address already exists in the system.'
				)
			),
			'location_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please select a location.'
				)
			),
			'role_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please select a role.'
				)
			)
		)
	);

	public $validationEdits = array(
		'password' => array(
			'password' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a password.',
					'required' => false
				)
			)
		),
		'auditor' => array(
			'password' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a password'
				)
			),
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a username'
				)
			)
		),
		'last4ssn' => array(
			'ssn' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a SSN',
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes',
					'required' => false
				),
				'unique' => array(
					'rule' => 'lastNameSSNUnique',
					'message' => 'The system is unable to register you at this time. Please contact us for assistance',
					'required' => false
				),
				'minLength' => array(
					'rule' => array('minLength', 4),
					'message' => 'Must be at least 4 numeric characters',
				)
			),
			'ssn_confirm' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the SSN.',
					'required' => false
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
					'required' => false
				),
				'verify' => array(
					'rule' => array('verifies', 'ssn'),
					'message' => 'SSNs do not match.',
					'required' => false
				),
				'minLength' => array(
					'rule' => array('minLength', 4),
					'message' => 'Must be at least 4 numeric characters',
				)
			)
		),
		'last5ssn' => array(
			'ssn' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a SSN',
					'required' => false
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes',
					'required' => false
				),
				'unique' => array(
					'rule' => 'lastNameSSNUnique',
					'message' => 'The system is unable to register you at this time. Please contact us for assistance',
					'required' => false
				),
				'minLength' => array(
					'rule' => array('minLength', 5),
					'message' => 'Must be at least 5 numeric characters',
				)
			),
			'ssn_confirm' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the SSN.',
					'required' => false
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
					'required' => false
				),
				'verify' => array(
					'rule' => array('verifies', 'ssn'),
					'message' => 'SSNs do not match.',
					'required' => false
				),
				'minLength' => array(
					'rule' => array('minLength', 5),
					'message' => 'Must be at least 5 numeric characters',
				)
			)
		),
		'last4' => array(
			'ssn' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a SSN.',
					'required' => false
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
					'required' => false
				),
				'unique' => array(
					'rule' => 'lastNameSSNUnique',
					'message' => 'The system is unable to register you at this time. Please contact us for assistance',
					'required' => false
				),
				'4or9' => array(
					'rule' => 'ssn4or9',
					'message' =>
					'SSN must either be full 9 digits using all three boxes,
					or last 4 digits using only the last box.',
					'required' => false
				)
			),
			'ssn_confirm' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the SSN.',
					'required' => false
				),
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
					'required' => false
				),
				'verify' => array(
					'rule' => array('verifies', 'ssn'),
					'message' => 'SSNs do not match.',
					'required' => false
				),
				'4or9' => array(
					'rule' => 'ssn4or9',
					'message' =>
					'SSN must either be full 9 digits using all three boxes,
					or last 4 digits using only the last box.',
					'required' => false
				)
			)
		),
		'customer' => array(
			'address_1' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a street address.',
					'on' => 'create'
				)
			),
			'city' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a city.',
					'on' => 'create'
				)
			),
			'county' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a county.',
					'on' => 'create'
				)
			),
			'state' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a state',
					'on' => 'create'
				)
			),
			'zip' => array(
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.'
				),
				'minLength' => array(
					'rule' => array('minLength', 5),
					'message' => 'Please enter 5 digit zip code.'
				),
				'maxLength' => array(
					'rule' => array('maxLength', 5),
					'message' => 'Please enter 5 digit zip code.'
				)
			),
			'phone' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a phone number',
					'on' => 'create'
				),
				'phone' => array(
					'rule' => array('maxLength', 20),
					'message' => 'Please no more than 20 characters.',
					'allowEmpty' => true
				)
			),
			'alt_phone' => array(
				'phone' => array(
					'rule' => array('maxLength', 20),
					'message' => 'Please no more than 20 characters.',
					'allowEmpty' => true
				)
			),
			'gender' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please select a gender.',
					'on' => 'create'
				)
			),
			'dob' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide date of birth.'
				),
				'date' => array(
					'rule' => array('date', 'mdy'),
					'message' => 'Please provide a valid date in this format mm/dd/yyyy.'
				)
			),
			'email' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide an email address.',
					'on' => 'create'
				),
				'email' => array(
					'rule' => 'email',
					'message' => 'Must be a vaild email like bob@test.com.',
					'allowEmpty' => true
				)
			),
			'language' => array(),
			'race' => array(),
			'ethnicity' => array(),
			'veteran' => array(),
			'disability' => array()
		)
	);

	public $virtualFields = array(
		'name_last4' => 'CONCAT(User.lastname, ", ", User.firstname, " - ", RIGHT (User.ssn , 4))'
	);


	public function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}
		$data = $this->data;
		if (empty($this->data))
		{
			$data = $this->read();
		}
		if (empty($data['User']['role_id']))
		{
			return null;
		}
		else
		{
			return array('Role' => array('id' => $data['User']['role_id']));
		}
	}

	public function beforeSave($options = array()) {
		if (Configure::read('Registration.usePassword'))
		{
			$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
		}

		if (!Configure::read('Registration.usePassword') && isset($this->data['User']['pass']))
		{
			$this->data['User']['password'] = Security::hash($this->data['User']['pass'], null, true);
		}

		if (!Configure::read('Registration.usePassword') && !empty($this->data['User']['ssn']))
		{
			$this->data['User']['password'] = Security::hash($this->data['User']['ssn'], null, true);
		}

		if(isset($this->data['User']['password']) && !empty($this->data['User']['password']))
		{
			$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
		}

		if(isset($this->data['User']['firstname']) && isset($this->data['User']['lastname']))
			$firstAndLast = ($this->data['User']['firstname'] != '' && $this->data['User']['lastname'] != '');
		else
			$firstAndLast = false;

		if($firstAndLast && empty($this->data['User']['username']))
		{
			$first_initial = substr( $this->data['User']['firstname'], 0, 1 );
			$username = $first_initial . $this->data['User']['lastname'];
			$this->data['User']['username'] = $username;
		}

		if (isset($this->data['User']['dob']) && !empty($this->data['User']['dob'])) {
			$this->data['User']['dob'] = date('Y-m-d', strtotime($this->data['User']['dob']));
		}
		else {
			$this->data['User']['dob'] = null;
		}
		return true;
	}

	public function afterSave($created) {
		if (!$created) {
			$parent = $this->parentNode();
			if($parent) {
				$parent = $this->node($parent);
			}
			if(isset($parent[0]) && $parent[0]['Aro']['id'] != 1) {
				$node = $this->node();
				$aro = $node[0];
				$aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
				$this->Aro->save($aro);
			}
		}
	}

	public function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);
		if(is_array($results)) {
			foreach ($results as $key => $value) {
				if(is_array($value)) {
					if(isset($value['User']['created'])) {
						$results[$key]['User']['created'] = $this->formatDateTimeAfterFind($value['User']['created']);
					}
					if(isset($value['User']['modified'])) {
						$results[$key]['User']['modified'] = $this->formatDateTimeAfterFind($value['User']['modified']);
					}
					if(isset($value['User']['dob'])) {
						$results[$key]['User']['dob'] = $this->formatDateAfterFind($value['User']['dob']);
					}
				}
			}
		}
		return $results;
	}

	public function verifies($data, $field) {
		$value = Set::extract($data, "{s}");
		return ($value[0] == $this->data[$this->name][$field]);
	}

	public function ssn4or9() {
		if(strlen($this->data['User']['ssn_3']) < 4) {
			return false;
		}
		$length = strlen($this->data['User']['ssn']);
		if($length < 4 ) {
			return false;
		}
		if($length > 4 && $length < 9) {
			return false;
		}
		return true;
	}

	public function lastNameSSNUnique() {
		$user = $this->find('first', array(
			'conditions' => array(
				'User.lastname' => $this->data['User']['lastname'],
				'User.ssn' => $this->data['User']['ssn'])));
		if($user) {
			return false;
		}
		return true;
	}
	// $formatted_id_card = $this->User->decodeIdString($this->data['User']['raw_id']);
	// $formatted_id_card['id_full']; // returns somethign like S2556457845
	public function decodeIdString($data) {
	$this->log($_SERVER['REMOTE_ADDR'] . ":" . $data['User']['id_card'], 'debug');
		$return = array(
			'first_name' => null,
			'last_name' => null,
			'middle_name' => null,
			'id_number' => null,
			'birth_month' => null,
			'birth_day' => null,
			'birth_year' => null,
			'id_full' => null,
			'success' => true);
		
		$return['success'] = true;
		$first_test = substr($data['User']['id_card'], 0, 18);

		if (!preg_match("/\^/", $first_test)) {
			$return['city'] = substr($data['User']['id_card'], 3, 13);
			$remaining_dl = substr($data['User']['id_card'], 16);
			if (preg_match("/(.*?)\^(.*?)\?;(\d+)=(\d+)=\?#!(\s+)(\d{5,})(.*?)\?/", $remaining_dl, $my_results)) {
				$return['street'] = $my_results[2];
				$return['street'] = preg_replace('/[^a-zA-Z0-9_-\s]/', '', $return['street']);
				$return['state'] = substr($data['User']['id_card'], 1,2);

				$full_name = $my_results[1];
				$name_parts = explode('$',$full_name);
				$return['first_name'] = $name_parts[1];
				$return['last_name'] = $name_parts[0];
				$return['middle_name'] = $name_parts[2];

				$id_raw = $my_results[3];
				$return['id_number'] = substr($id_raw,8,11);

				$birth_raw = $my_results[4];
				$return['birth_month'] = substr($birth_raw,2,2);
				$return['birth_day'] = substr($birth_raw,10,2);
				$return['birth_year'] = substr($birth_raw,4,4);
			
				$return['zip_code'] = $my_results[6];
			}
		}

		elseif (preg_match("/(\w{2})(.*?)\^(.*?)\^(.*?)\?;(\d+)=(\d+)=\?/", $data['User']['id_card'], $my_results)) {
			$return['street'] = $my_results[4];
			$return['street'] = preg_replace('/[^a-zA-Z0-9_-\s]/', '', $return['street']);
			$return['state'] = $my_results[1];
			$return['city'] = $my_results[2];

			$full_name = $my_results[3];
			$name_parts = explode('$',$full_name);
			$return['first_name'] = $name_parts[1];
			$return['last_name'] = $name_parts[0];
			$return['middle_name'] = $name_parts[2];

			$id_raw = $my_results[5];
			$return['id_number'] = substr($id_raw,8,11);

			$birth_raw = $my_results[6];
			$return['birth_month'] = substr($birth_raw,2,2);
			$return['birth_day'] = substr($birth_raw,10,2);
			$return['birth_year'] = substr($birth_raw,4,4);
		}

		elseif (preg_match("/(\w{2})(.*?)\^(.*?)\^(.*?)\^(.*?)\?;(\d+)=(\d+)=\?/", $data['User']['id_card'], $my_results)) {
			$return['street'] = $my_results[4];
			$return['street'] = preg_replace('/[^a-zA-Z0-9_-\s]/', '', $return['street']);
			$return['state'] = $my_results[1];
			$return['city'] = $my_results[2];

			$full_name = $my_results[3];
			$name_parts = explode('$',$full_name);
			$return['first_name'] = $name_parts[1];
			$return['last_name'] = $name_parts[0];
			$return['middle_name'] = $name_parts[2];

			$id_raw = $my_results[6];
			$return['id_number'] = substr($id_raw,8,11);

			$birth_raw = $my_results[7];
			$return['birth_month'] = substr($birth_raw,2,2);
			$return['birth_day'] = substr($birth_raw,10,2);
			$return['birth_year'] = substr($birth_raw,4,4);
		}

		elseif (preg_match("/(\w{2})(.*?)\^(.*?)\^(.*?)\^(.*?)\?;(\d+)=(\d+)=\?#!(\s+)(\d{5,})(.*?)\?/", $data['User']['id_card'], $my_results)) {
			$return['street'] = $my_results[4];
			$return['street'] = preg_replace('/[^a-zA-Z0-9_-\s]/', '', $return['street']);
			$return['state'] = $my_results[1];
			$return['city'] = $my_results[2];

			$full_name = $my_results[3];
			$name_parts = explode('$',$full_name);
			$return['first_name'] = $name_parts[1];
			$return['last_name'] = $name_parts[0];
			$return['middle_name'] = $name_parts[2];

			$id_raw = $my_results[6];
			$return['id_number'] = substr($id_raw,8,11);

			$birth_raw = $my_results[7];
			$return['birth_month'] = substr($birth_raw,2,2);
			$return['birth_day'] = substr($birth_raw,10,2);
			$return['birth_year'] = substr($birth_raw,4,4);
		
			$return['zip_code'] = $my_results[9];
		}

		$return['id_full'] = substr($return['last_name'],0,1) . $return['id_number'];
		foreach($return as $k => $v) {
			if(empty($return[$k])) {
				if ($k != 'middle_name') {
					$return['success'] = false;
					$return['message'] = 'ID card swipe issue (Not all info present). <br/>Please swipe again.';
				}
			}
		}

	    $my_return = print_r($return,true);
	    $this->log($my_return, 'debug');

		return $return;
	}

	public function setLoginValidation($type = 'normal')
	{
		$login_method 	= Configure::read('Login.method');
		$ssn_length		= Configure::read('Login.' . $type . '.ssn_length');

		if($login_method == 'ssn')
		{
			$this->validate['lastname']['notEmpty'] = array(
				'rule' => 'notEmpty',
				'message' => 'Your last name is required'
			);

			$this->validate['ssn']['notEmpty'] = array(
				'rule' => 'notEmpty',
				'message' => 'Your SSN is required'
			);
			$this->validate['ssn']['numeric'] = array(
				'rule' => 'numeric',
				'message' => 'Your SSN can only be comprised of digits'
			);

			$this->validate['ssn']['minLength'] = array(
				'rule' => array('minLength', $ssn_length),
				'message' => 'Your SSN must be at least ' . $ssn_length . ' characters long'
			);
			$this->validate['ssn']['maxLength'] = array(
				'rule' => array('maxLength', $ssn_length),
				'message' => 'Your SSN cannot be longer than ' . $ssn_length . ' digits'
			);
		}
		else
		{
			$this->validate['username']['notEmpty'] = array(
				'rule' => 'notEmpty',
				'message' => 'Your username is required'
			);
			$this->validate['password']['notEmpty'] = array(
				'rule' => 'notEmpty',
				'message' => 'Password is required'
			);
		}
	}

	public function findUser($username, $password, $type = 'normal')
	{
		$login_method = Configure::read('Login.method');

		if($login_method == 'ssn')
		{
			$search['lastname'] = $username;
			$search['ssn LIKE'] = '%' . $password;
		}
		else
		{
			$search['username'] = $username;
			$search['password'] = Security::hash($password, null, true);
		}

		$user = $this->find('first', array(
			'conditions' => $search
		));

		if(!$user)
		{
			return FALSE;
		}
		else
		{
			return $user;
		}
	}

	public function setUserDataReq($type = 'normal')
	{
		$login_method 	= Configure::read('Login.method');
		$ssn_length		= Configure::read('Registration.' . $type . '.ssn_length');

		if($login_method == 'ssn')
		{
			$this->validate['ssn']['notEmpty'] = array(
				'rule' => 'notEmpty',
				'message' => 'Your SSN is required'
			);
			$this->validate['ssn']['numeric'] = array(
				'rule' => 'numeric',
				'message' => 'Your SSN can only be comprised of digits'
			);

			$this->validate['ssn_confirm']['notEmpty'] = array(
				'rule' => 'notEmpty',
				'message' => 'Re-Enter your SSN number'
			);
			$this->validate['ssn_confirm']['identical'] = array(
				'rule' => array('identicalFieldValues', 'ssn'),
				'message' => 'Your SSN numbers do not match'
			);
			$this->validate['ssn']['isUnique'] = array(
				'rule' => 'isUnique',
				'message' => 'Cannot register you with that SSN'
			);
		}
		else
		{
			$this->validate['username']['notEmpty'] = array(
				'rule' => 'notEmpty',
				'message' => 'A Username is required'
			);
			$this->validate['username']['isUnique'] = array(
				'rule' => 'isUnique',
				'message' => 'That username is not available'
			);

			$this->validate['password']['notEmpty'] = array(
				'rule' => 'notEmpty',
				'message' => 'Your password is required'
			);
			$this->validate['password']['identical'] = array(
				'rule' => array('identicalFieldValues', 'password_confirm'),
				'message' => 'Your passwords do not match'
			);
		}
	}

	public function setSSNLength($ssn_length)
	{
		$this->validate['ssn']['notEmpty'] = array(
			'rule' => 'notEmpty',
			'message' => 'Your SSN is required'
		);
		$this->validate['ssn']['minLength'] = array(
			'rule' => array('minLength', $ssn_length),
			'message' => 'Your SSN must be at least ' . $ssn_length . ' digits long'
		);
		$this->validate['ssn']['maxLength'] = array(
			'rule' => array('maxLength', $ssn_length),
			'message' => 'Your SSN must be ' . $ssn_length . ' digits long'
		);
		$this->validate['ssn']['numeric'] = array(
			'rule' => 'numeric',
			'message' => 'Your SSN may only be numbers'
		);

		$this->validate['ssn_confirm']['notEmpty'] = array(
			'rule' => 'notEmpty',
			'message' => 'SSN Confirmation required'
		);
		$this->validate['ssn_confirm']['minLength'] = array(
			'rule' => array('minLength', $ssn_length),
			'message' => 'Your Confirmation SSN must be at least ' . $ssn_length . ' digits long'
		);
		$this->validate['ssn_confirm']['maxLength'] = array(
			'rule' => array('maxLength', $ssn_length),
			'message' => 'Your Confirmation SSN must be ' . $ssn_length . ' digits long'
		);
		$this->validate['ssn_confirm']['numeric'] = array(
			'rule' => 'numeric',
			'message' => 'Your Confirmation SSN may only be numbers'
		);
	}

	public function setUsernamePasswordValidation()
	{
		$this->validate['username']['notEmpty'] = array(
			'rule' => 'notEmpty',
			'message' => 'Username is required'
		);
		$this->validate['password']['notEmpty'] = array(
			'rule' => 'notEmpty',
			'message' => 'Password is required'
		);
	}

	public function setUsernamePasswordRegistration()
	{
		$this->validate['username']['notEmpty'] = array(
			'rule' => 'notEmpty',
			'message' => 'Username is required'
		);
		$this->validate['username']['isUnique'] = array(
			'rule' => 'isUnique',
			'message' => 'That Username is already taken'
		);
		$this->validate['password']['notEmpty'] = array(
			'rule' => 'notEmpty',
			'message' => 'Password is required'
		);
		$this->validate['password']['identical'] = array(
			'rule' => array('identicalFieldValues', 'password_confirm'),
			'message' => 'Your passwords do not match'
		);
		$this->validate['password_confirm']['notEmpty'] = array(
			'rule' => 'notEmpty',
			'message' => 'Password Confirmation is required'
		);
	}

	public function identicalFieldValues(&$data, $compareField)
	{
	    // $data array is passed using the form field name as the key
	    // so let's just get the field name to compare
	    $value = array_values($data);
	    $comparewithvalue = $value[0];
	    return ($this->data[$this->name][$compareField] == $comparewithvalue);
	}
}


