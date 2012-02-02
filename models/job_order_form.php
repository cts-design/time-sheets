<?php
class JobOrderForm extends AppModel {
	var $name = 'JobOrderForm';
	var $validate = array(
		'federal_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your Federal ID (FEIN)'
			)
		),
		'company_name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your company name'
			)
		),
		'street_address1' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your street address'
			)
		),
		'city' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your city'
			)
		),
		'zip' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your zip'
			)
		),
		'contact_person_and_title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your contact person and title'
			)
		),
		'phone_number' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your phone number'
			)
		),
		'email_address' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your email address'
			)
		),
		'company_website' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide your company website address'
			)
		),
		'position_title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the position title'
			)
		),
		'openings' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the number of openings'
			)
		),
		'number_requested_to_interview' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the number requested to interview'
			)
		),
		'length_of_experience' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the length of experience required'
			)
		),
		'years_of_education' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the years of education required'
			)
		),
		'minimum_education_degree' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the minimum education/degree required'
			)
		),
		'minimum_age' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the minimum age required'
			)
		),
		'full_time_hours' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the full-time hours'
			)
		),
		'part_time_hours' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the part-time hours'
			)
		),
		'temp_hours' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the temporary hours'
			)
		),
		'length_of_assignment' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the length of the assignment'
			)
		),
		'wages_from' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the from wages'
			)
		),
		'wages_to' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the to wages'
			)
		),
		'from_time' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the from time'
			)
		),
		'to_time' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide the to time'
			)
		)
	);
	
	function properFileType($value) {
		$value = array_shift($value);
		$pass = false;
		
		switch ($value['type']) {
			case 'application/pdf':
				$pass = true;
				break;
			
			case 'application/msword':
				$pass = true;
				break;
				
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
				$pass = true;
				break;
			
			default:
				$pass = false;
				break;
		}
		
		return $pass;
	}
}
?>