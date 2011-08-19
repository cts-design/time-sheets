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
		'ssn' => array(
		    'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a SSN.'
		    ),
		    'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please provide only numbers, no spaces or dashes.'
		    ),
		    'unique' => array(
				'rule' => 'isUnique',
				'message' => 'The SSN entered already exists in the system.'
		    ),
		    'minLength' => array(
				'rule' => array('minLength', 9),
				'message' => 'SSN must be full 9 digits.'
			)
		),
		'ssn_confirm' => array(
		    'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please confirm the SSN.',
		    ),
		    'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please provide only numbers, no spaces or dashes.',
		    ),
		    'verify' => array(
				'rule' => array('verifies', 'ssn'),
				'message' => 'SSNs do not match.'
		    ),
		    'minLength' => array(
				'rule' => array('minLength', 9),
				'message' => 'SSN must be full 9 digits.'
			)		    
		),
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
		'language' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide a language.',
				'on' => 'create'
			)
		),
		'ethnicity' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select a ethnicity.',
				'on' => 'create'
			)
		),
		'race' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select race.',
				'on' => 'create'
			)
		) 

    );
	
	var $validationSets = array(
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
					'message' => 'Last 4 SSN must only be numbers'
				),
				'minLength' => array(
					'rule' => array('minLength', 4),
					'message' => 'Last 4 SSN must be 4 digits',
					'required' => true
				)
			)
		),
		'miniRegistration' => array(
			'firstname' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a first name.'
			    ),
			    'maxlength' => array(
					'rule' => array('maxlength', 50),
					'message' => 'This field cannot excced 50 characters.'
			    )
			),
			'lastname' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a last name.'
			    ),
			    'maxlength' => array(
					'rule' => array('maxlength', 50),
					'message' => 'This field cannot excced 50 characters.'
			    )
			),
			'ssn' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a SSN.'
			    ),
			    'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.'
			    ),
			    'unique' => array(
					'rule' => 'isUnique',
					'message' => 'The system is unable to register you at this time. Please see a representative for assistance'
			    ),
			    'minLength' => array(
					'rule' => array('minLength', 9),
					'message' => 'SSN must be 9 characters.',
			    )
			),
			'ssn_confirm' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the SSN.',
			    ),
			    'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
			    ),
			    'verify' => array(
					'rule' => array('verifies', 'ssn'),
					'message' => 'SSNs do not match.'
			    ),
			    'minLength' => array(
					'rule' => array('minLength', 9),
					'message' => 'SSN must be 9 characters.'
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
			)		
		),
		'selfSignEdit' => array(
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
					'message' => 'This username cannot excced 25 characters.'
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
		),
		'last4Rules' => array(
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
			'ssn' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a SSN.'
			    ),
			    'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.'
			    ),
			    'unique' => array(
					'rule' => 'lastNameSSNUnique',
					'message' => 'The system is unable to register you at this time. Please contact us for assistance'
			    ),
			    '4or9' => array(
					'rule' => 'ssn4or9',
					'message' => 
						'SSN must either be full 9 digits using all three boxes, 
						or last 4 digits using only the last box.'
				)
			),
			'ssn_confirm' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the SSN.',
			    ),
			    'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
			    ),
			    'verify' => array(
					'rule' => array('verifies', 'ssn'),
					'message' => 'SSNs do not match.'
			    ),
			    '4or9' => array(
					'rule' => 'ssn4or9',
					'message' => 					
						'SSN must either be full 9 digits using all three boxes, 
						or last 4 digits using only the last box.'
				)		    
			),
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
				),
			    'unique' => array(
					'rule' => 'isUnique',
					'message' => 'The email address already exists in the system.'
			    )
			),
			'language' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a language.',
					'on' => 'create'
				)
			),
			'ethnicity' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please select a ethnicity.',
					'on' => 'create'
				)
			),
			'race' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please select race.',
					'on' => 'create'
				)
			) 		
		),
		'childLast4Rules' => array(
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
			'ssn' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a SSN.'
			    ),
			    'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.'
			    ),
			    'unique' => array(
					'rule' => 'lastNameSSNUnique',
					'message' => 'The system is unable to register you at this time. Please contact us for assistance'
			    ),
			    '4or9' => array(
					'rule' => 'ssn4or9',
					'message' => 
						'SSN must either be full 9 digits using all three boxes, 
						or last 4 digits using only the last box.'
				)
			),
			'ssn_confirm' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the SSN.',
			    ),
			    'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
			    ),
			    'verify' => array(
					'rule' => array('verifies', 'ssn'),
					'message' => 'SSNs do not match.'
			    ),
			    '4or9' => array(
					'rule' => 'ssn4or9',
					'message' => 					
						'SSN must either be full 9 digits using all three boxes, 
						or last 4 digits using only the last box.'
				)		    
			),
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
			'email_confirm' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the email address',
					'on' => 'create'
				),
			    'verify' => array(
					'rule' => array('verifies', 'email'),
					'message' => 'Email addresses do not match.'
			    ),
			),
			'language' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a language.',
					'on' => 'create'
				)
			),
			'ethnicity' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please select a ethnicity.',
					'on' => 'create'
				)
			),
			'race' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please select race.',
					'on' => 'create'
				)
			) 		
		),
		'adminAddCustomer' => array(
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
			'ssn' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please provide a SSN.'
			    ),
			    'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.'
			    ),
			    'unique' => array(
					'rule' => 'isUnique',
					'message' => 'The SSN entered already exists in the system.'
			    ),
			    'minLength' => array(
					'rule' => array('minLength', 9),
					'message' => 'SSN must be full 9 digits.'
				)
			),
			'ssn_confirm' => array(
			    'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Please confirm the SSN.',
			    ),
			    'numeric' => array(
					'rule' => 'numeric',
					'message' => 'Please provide only numbers, no spaces or dashes.',
			    ),
			    'verify' => array(
					'rule' => array('verifies', 'ssn'),
					'message' => 'SSNs do not match.'
			    ),
			    'minLength' => array(
					'rule' => array('minLength', 9),
					'message' => 'SSN must be full 9 digits.'
				)		    
			),
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
			)		
		)
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
	    $last4 = substr($this->data['User']['ssn'], -4);
	    $this->data['User']['password'] = Security::hash($last4, null, true);
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
