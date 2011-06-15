<?php
/* User Fixture generated on: 2010-09-22 15:09:21 : 1285167741 */
class UserFixture extends CakeTestFixture {
	var $name = 'User';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'role_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 1),
		'firstname' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'lastname' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'middle_initial' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 3, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'ssn' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 9, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'username' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 25, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'address_1' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'address_2' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'city' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'state' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'zip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 5, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'alt_phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'gender' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'dob' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'signature' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'signature_created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'signature_modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'role_id' => 2,
			'firstname' => 'brandon',
			'lastname' => 'cordell',
			'middle_initial' => 'D',
			'ssn' => '222222222',
                        'username' => 'brandoncordell',
			'password' => 'asd123',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'brandonc@ctsfla.com',
			'status' => 1,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'
		),
		array(
			'id' => 2,
			'role_id' => 2,
			'firstname' => 'Daniel',
			'lastname' => 'Nolan',
			'middle_initial' => 'A',
			'ssn' => '222222222',
                        'username' => 'dnolan',
			'password' => 'asd123',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'daniel@ctsfla.com',
			'status' => 1,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'
		)		
		,
		array(
			'id' => 10,
			'role_id' => 1,
			'firstname' => 'Daniel',
			'lastname' => 'Test',
			'middle_initial' => 'A',
			'ssn' => '123441234',
                        'username' => 'test',
			'password' => '1234',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'danieltest@teststuff.com',
			'status' => 1,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'		
		),
		array(
			'id' => 9,
			'role_id' => 1,
			'firstname' => 'Daniel',
			'lastname' => 'Smith',
			'middle_initial' => 'A',
			'ssn' => '123441234',
            'username' => 'smith',
			'password' => '1234',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'danieltest@teststuff.com',
			'status' => 1,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'		
		),
		array(
			'id' => 11,
			'role_id' => 1,
			'firstname' => 'Daffy',
			'lastname' => 'Duck',
			'middle_initial' => 'D',
			'ssn' => '123441234',
                        'username' => 'dduck',
			'password' => '1234',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'daffyduckt@looneytunes.com',
			'status' => 0,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'		
		),
		array(
			'id' => 12,
			'role_id' => 1,
			'firstname' => 'Roger',
			'lastname' => 'Rabbit',
			'middle_initial' => '',
			'ssn' => '123441234',
                        'username' => 'rrabbit',
			'password' => '1234',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'rogerrabbit@toontown.com',
			'status' => 0,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'		
		),
		array(
			'id' => 13,
			'role_id' => 1,
			'firstname' => 'Bob',
			'lastname' => 'Marley',
			'middle_initial' => '',
			'ssn' => '123441234',
                        'username' => 'bmarley',
			'password' => '1234',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'bmarley@theislands.com',
			'status' => 0,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'		
		),
		array(
			'id' => 14,
			'role_id' => 1,
			'firstname' => 'George',
			'lastname' => 'Bush',
			'middle_initial' => 'W',
			'ssn' => '123441234',
                        'username' => 'bush',
			'password' => '1234',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'gbush@worstpresevar.com',
			'status' => 0,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'		
		),
		array(
			'id' => 15,
			'role_id' => 1,
			'firstname' => 'Slim',
			'lastname' => 'Jim',
			'middle_initial' => '',
			'ssn' => '123441234',
                        'username' => 'jim',
			'password' => '1234',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'slimjim@meatsnacks.com',
			'status' => 0,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'		
		),
		array(
			'id' => 16,
			'role_id' => 1,
			'firstname' => 'John',
			'lastname' => 'Doe',
			'middle_initial' => '',
			'ssn' => '122441234',
            'username' => 'doe',
			'password' => '1234',
			'address_1' => '123 main st',
			'address_2' => '',
			'city' => 'spring hill',
			'state' => 'fl',
			'zip' => '34609',
			'phone' => '3525551234',
			'alt_phone' => '',
			'gender' => 'Male',
			'dob' => '2010-09-22',
			'email' => 'johndoe@mia.com',
			'status' => 0,
			'deleted' => 0,
			'signature' => 1,
			'location_id' => '1',
			'signature_created' => '2010-09-22 15:02:21',
			'signature_modified' => '2010-09-22 15:02:21',
			'created' => '2010-09-22 15:02:21',
			'modified' => '2010-09-22 15:02:21'		
		)		
	);
}
?>