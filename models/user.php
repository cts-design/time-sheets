<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class User extends AppModel {

    var $name = 'User';
	
    var $hasMany = array(
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
		)		
    );
	
    var $hasOne = array(
		'QueuedDocument' => array(
		    'className' => 'QueuedDocument',
		    'foreignKey' => 'locked_by'
		)
    );
    var $belongsTo = array('Role', 'Location');
	
    var $actsAs = array('AtlasAcl' => 'requester', 'Multivalidatable', 'Disableable');
	
    var $validate = array(
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
	
	var $validationSets = array(
		'kioskRegistration' => array(
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
		'customerLogin' => array(
			'username' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Last name field is required.'
				)
			),
			'password' => array(
				'numeric' => array(
					'rule' => 'numeric',
					'message' => 'SSN must only be numbers'
				),
				'minLength' => array(
					'rule' => array('minLength', 9),
					'message' => 'SSN must be 9 digits',
					'required' => true
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
	
	var $validationEdits = array(
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
	
    var $virtualFields = array(
     'name_last4' => 'CONCAT(User.lastname, ", ", User.firstname, " - ", RIGHT (User.ssn , 4))'
    );	


    function parentNode() {
		if (!$this->id && empty($this->data)) {
		    return null;
		}
		$data = $this->data;
		if (empty($this->data)) {
		    $data = $this->read();
		}
		if (empty($data['User']['role_id'])) {
		    return null;
		} 
		else {
		    return array('Role' => array('id' => $data['User']['role_id']));
		}
    }

    function beforeSave($options = array()) {
	if (isset($this->data['User']['pass'])) {
	    $this->data['User']['password'] = Security::hash($this->data['User']['pass'], null, true);
	}
	if (!empty($this->data['User']['ssn'])) {
	    $this->data['User']['password'] = Security::hash($this->data['User']['ssn'], null, true);
	}
	if(!empty($this->data['User']['firstname']) && !empty($this->data['User']['lastname'])) {
	    if(!empty($this->data['User']['role_id']) && $this->data['User']['role_id'] > 1) {
			$this->data['User']['username'] = substr($this->data['User']['firstname'], 0, 1) . $this->data['User']['lastname'];
	    }
	    else {
			$this->data['User']['username'] = $this->data['User']['lastname'];
	    }
	    
	}
	if (isset($this->data['User']['dob'])) {
	    $this->data['User']['dob'] = date('Y-m-d', strtotime($this->data['User']['dob']));
	}

	return true;
    }

    function afterSave($created) {
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
   
    function  afterFind($results, $primary = false) {
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

    function verifies($data, $field) {
		$value = Set::extract($data, "{s}");
		return ($value[0] == $this->data[$this->name][$field]);
	}
	
	function ssn4or9() {
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
	
	function lastNameSSNUnique() {
		$user = $this->find('first', array(
			'conditions' => array(
				'User.lastname' => $this->data['User']['lastname'],
				'User.ssn' => $this->data['User']['ssn'])));
		if($user) {
			return false;	
		}
		return true;	
	}
	
}
