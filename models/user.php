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
	'ProgramResponse' => array(
		'className' => 'ProgramResponse', 
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
    var $actsAs = array('Acl' => 'requester');
    var $validate = array(
		'firstname' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide a first name.'
			),
		    'maxlength' => array(
				'rule' => array('maxlength', 50),
				'message' => 'This field cannot excced 50 characters.',
		    )
		),
		'lastname' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide a last name.',
		    ),
		    'maxlength' => array(
				'rule' => array('maxlength', 50),
				'message' => 'This field cannot excced 50 characters.',
		    )
		),
		'username' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide a username',
				'on' => 'create'
		    ),
		    'minlength' => array(
				'rule' => array('minlength', 5),
				'message' => 'Username must be at least 5 characters.',
				'on' => 'create'
		    ),
		    'maxlength' => array(
				'rule' => array('maxlength', 25),
				'message' => 'This username cannot excced 25 characters.',
				'on' => 'create'
		    ),
		    'unique' => array(
				'rule' => 'isUnique',
				'message' => 'The username entered already exists in the system.',
				'on' => 'create'
		    )
		),
		'pass' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide a password',
				'on' => 'create', 
		    ),
		    'minlength' => array(
				'rule' => array('minlength', 6),
				'message' => 'Password must be atleast 6 characters.',
				'required' => false,
		    ),
		    'maxlength' => array(
				'rule' => array('maxlength', 25),
				'message' => 'This username cannot excced 25 characters.',
		    )
		),
		'ssn' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide a SSN.',
		    ),
		    'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please provide only numbers, no spaces or dashes.',
		    ),
		    'unique' => array(
				'rule' => 'isUnique',
				'message' => 'The SSN entered already exists in the system.'
		    ),
		    'minLength' => array(
				'rule' => array('minLength', 9),
				'message' => 'SSN must be 9 characters.',
		    )
		),
		'ssn_confirm' => array(
		    'notempty' => array(
				'rule' => 'notempty',
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
		'address_1' => array(
			'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide an address (number and street).'
			)
		),
		'city' => array(
			'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide a city.'
			)
		),
		'county' => array(
			'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide a county.'
			)
		),
		'state' => array(
			'notempty' => array(
				'rule' => 'notempty', 
				'message' => 'Please provide a state.' 
			)
		),	
		'zip' => array(
		    'numeric' => array(
				'rule' => 'numeric',
				'message' => 'Please provide only numbers, no spaces or dashes.',
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
		    'maxLength' => array(
				'rule' => array('maxLength', 20),
				'message' => 'Please no more than 20 characters.'
		    ),
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide a phone number.' 
			)
		),
		'alt_phone' => array(
		    'maxLength' => array(
				'rule' => array('maxLength', 20),
				'message' => 'Please no more than 20 characters.',
				'allowEmpty' => true
		    )
		),
		'gender' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please select gender.',
				'required' => true
		    )
		),
		'language' => array(
			'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide primary spoken language.'
			)
		),
		'dob' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please provide date of birth.'
		    ),
		    'date' => array(
				'rule' => array('date', 'mdy'),
				'message' => 'Please provide a valid date in this format mm/dd/yyyy'
		    )
		),
		'email' => array(
		    'notempty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please provide an email address.',
		    ),
		    'email' => array(
				'rule' => 'email',
				'message' => 'Must be a vaild email like bob@test.com.',
		    ),
		    'unique' => array(
				'rule' => 'isUnique',
				'message' => 'The email address already exists in the system.'
		    )
		),
		'location_id' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please select a location.'
		    )
		),
		'role_id' => array(
		    'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please select a role.'
		    )
		),
		'ethnicity' => array(
			'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please select ethnicity.'
			)
		),
		'race' => array(
			'notempty' => array(
				'rule' => 'notempty',
				'message' => 'Please select race.'
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
		} else {
		    return array('Role' => array('id' => $data['User']['role_id']));
		}
    }


    function beforeValidate($options = array()) {
		parent::beforeValidate($options);
		$miniRules = array(
		    'address_1' => 'notempty',
		    'city' => 'notempty',
		    'state' => 'notempty',
		    'phone' => 'notempty',
		    'gender' => 'notempty',
		    'location_id' => 'notempty',
			'language' => 'notempty',
			'ethnicity' => 'notempty',
			'race' => 'notempty',
			'email' => array('notempty', 'email', 'unique'),
			'language' => 'notempty',
			'ethnicity' => 'notempty',
			'race' => 'notempty'
		);
		if (isset($this->data['User']['mini_registration']) &&
			$this->data['User']['mini_registration'] == 'kiosk') {
			    $this->pauseValidation($miniRules);
		}

		if (isset($this->data['User']['self_sign_edit']) &&
			$this->data['User']['self_sign_edit'] == 'edit') {
			    $miniRules['ssn'] = 'notempty';
			    $this->pauseValidation($miniRules);
		}
		if (isset($this->data['User']['admin_registration'])) {
		    $rules = array(
			'address_1' => 'notempty',
			'city' => 'notempty',
			'county' => 'notempty',
			'phone' => 'notempty',
			'state' => 'notempty',
			'zip' => array('notempty', 'numeric', 'minLength', 'maxLength'),
			'gender' => 'notempty',
			'ssn' => array('notempty', 'numeric', 'minLength', 'unique' ),
			'ssn_confirm' => array('notempty', 'numeric', 'minLength'),
			'dob' => array('notempty', 'date'),
			'language' => 'notempty',
			'ethnicity' => 'notempty',
			'race' => 'notempty'
		    );
		    $this->pauseValidation($rules);
		}
		if(isset($this->data['User']['registration']) && 
			$this->data['User']['registration'] == 'child_website') {
			    $rules = array(
					'email' => 'unique'
			    );
				$this->pauseValidation($rules);					
		}
		if (isset($this->data['User']['role_id']) && $this->data['User']['role_id'] == 1) {
		    $rules = array(
				'username' => 'unique',
				'email' => 'notempty'
		    );
		    $this->pauseValidation($rules);
		}
    }

    function pauseValidation($rules) {
        foreach ($rules as $key => $value) {
            if(is_array($value)) {
                foreach ($value as $k => $v){
                    unset($this->validate[$key][$v]);
                }
            }
            else {
                unset($this->validate[$key][$value]);
            }
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
		    $parent = $this->node($parent);
		    $node = $this->node();
		    $aro = $node[0];
		    $aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
	            $this->Aro->save($aro);
		}
    }
   
    function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);
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
		return $results;
    }

    function verifies($data, $field) {
		$value = Set::extract($data, "{s}");
		return ($value[0] == $this->data[$this->name][$field]);
    }

    function delete($id = null) {
		if ($this->saveField('deleted', 1)) {
		    return true;
		}
		return false;
    }

}