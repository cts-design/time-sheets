<?php
/* Location Fixture generated on: 2011-06-13 15:52:52 : 1307994772 */
class LocationFixture extends CakeTestFixture {
	var $name = 'Location';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'public_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'address_1' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'address_2' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'city' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'zip' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'country' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'telephone' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 14, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'fax' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 14, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Citrus',
			'public_name' => '',
			'address_1' => '',
			'address_2' => '',
			'city' => '',
			'state' => '',
			'zip' => '',
			'country' => '',
			'telephone' => '',
			'fax' => ''
		),
		array(
			'id' => 2,
			'name' => 'Levy',
			'public_name' => '',
			'address_1' => '',
			'address_2' => '',
			'city' => '',
			'state' => '',
			'zip' => '',
			'country' => '',
			'telephone' => '',
			'fax' => ''
		),
		array(
			'id' => 3,
			'name' => 'Paddock',
			'public_name' => '',
			'address_1' => '',
			'address_2' => '',
			'city' => '',
			'state' => '',
			'zip' => '',
			'country' => '',
			'telephone' => '',
			'fax' => ''
		),
	);
}
