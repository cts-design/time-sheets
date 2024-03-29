<?php
/* Kiosk Fixture generated on: 2010-09-27 15:09:40 : 1285601080 */
class KioskFixture extends CakeTestFixture {
	var $name = 'Kiosk';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'location_recognition_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'location_description' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
            'location_id' => 1,
			'location_recognition_name' => 'Lorem ipsum dolor sit amet',
			'location_description' => 'Lorem ipsum dolor sit amet'
		),
		array(
			'id' => 2,
            'location_id' => 1,
			'location_recognition_name' => 'Lorem ipsum dolor sit amet',
			'location_description' => 'Lorem ipsum dolor sit amet'
		)		
	);
}
?>