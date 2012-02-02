<?php
/* EmployerVerification Fixture generated on: 2012-02-01 09:18:38 : 1328105918 */
class EmployerVerificationFixture extends CakeTestFixture {
	var $name = 'EmployerVerification';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'employer_name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'street_address1' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'street_address2' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'city' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'zip' => array('type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone_number' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name_of_employee' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_four_ssn_employee' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'start_date' => array('type' => 'date', 'null' => false, 'default' => '0000-00-00'),
		'job_title' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'salary' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'hours_per_week' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'benefits' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 1, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'employer_name' => 'Lorem ipsum dolor sit amet',
			'street_address1' => 'Lorem ipsum dolor sit amet',
			'street_address2' => 'Lorem ipsum dolor sit amet',
			'city' => 'Lorem ipsum dolor sit amet',
			'state' => 'Lorem ipsum dolor sit amet',
			'zip' => 'Lorem ip',
			'phone_number' => 'Lorem ipsum dolor sit amet',
			'name_of_employee' => 'Lorem ipsum dolor sit amet',
			'last_four_ssn_employee' => 1,
			'start_date' => '2012-02-01',
			'job_title' => 'Lorem ipsum dolor sit amet',
			'salary' => 'Lorem ipsum dolor sit amet',
			'hours_per_week' => 'Lorem ipsum dolor sit amet',
			'benefits' => 1,
			'deleted' => 'Lorem ipsum dolor sit ame',
			'created' => '2012-02-01 09:18:38',
			'modified' => '2012-02-01 09:18:38'
		),
	);
}
?>