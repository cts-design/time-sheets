<?php
/* HotJob Fixture generated on: 2011-02-28 13:48:28 : 1298900908 */
class HotJobFixture extends CakeTestFixture {
	var $name = 'HotJob';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 9, 'key' => 'primary'),
		'employer' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'location' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'reference_number' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'contact' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'file' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'employer' => 'CNCists',
			'title' => 'CNC Swiss Lathe 7 Axis Operator',
			'description' => 'Must have HS/GED w/5 yrs exp in CNC lathe machinery & familiar w/ISO 9001 requirements. Will set-up, program & operate 2 CNC Swiss CNC lathe 7 axis machines. Pay: $15-25/hr.',
			'location' => 'Pinellas County',
			'url' => 'http://cncists.com',
			'reference_number' => '9509835',
			'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
			'file' => ''
		),
		array(
			'id' => 2,
			'employer' => 'Test Pests',
			'title' => 'Pest Control Tech/Sales',
			'description' => 'No exp necessary, willing to train. Must be min 18 yrs old, have a valid driver’s license w/clean driving record, must pass a drug test & be physically fit w/ability to crawl under houses & in attics. Background checks will be perform. Unemployment compensation recipients encouraged to apply. Pay: $10/hr plus comm.',
			'location' => 'Pinellas County',
			'url' => 'http://cncists.com',
			'reference_number' => '9544375',
			'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
			'file' => ''
		),
	);
}
?>