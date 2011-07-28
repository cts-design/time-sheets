<?php
/* KioskButton Fixture generated on: 2011-07-18 14:37:04 : 1311014224 */
class KioskButtonFixture extends CakeTestFixture {
	var $name = 'KioskButton';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'kiosk_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'status' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'button_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'logout_message' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'order' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'button_id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => NULL,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 1,
			'logout_message' => 'awesome logout message goes here.',
			'order' => 9999
		),
		array(
			'id' => 2,
			'parent_id' => 1,
			'kiosk_id' => 2,
			'status' => 1,
			'button_id' => 2,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 3,
			'parent_id' => 22,
			'kiosk_id' => 2,
			'status' => 1,
			'button_id' => 3,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 4,
			'parent_id' => 1,
			'kiosk_id' => 2,
			'status' => 1,
			'button_id' => 4,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 5,
			'parent_id' => NULL,
			'kiosk_id' => 2,
			'status' => 1,
			'button_id' => 5,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 10,
			'parent_id' => NULL,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 6,
			'logout_message' => 'wow this is a cool logout message. lets test a really long winded message just in case someone gets crazy and decides to write a novel of instructions to tell people what to do. Yeah.',
			'order' => 9999
		),
		array(
			'id' => 22,
			'parent_id' => 1,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 7,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 45,
			'parent_id' => NULL,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 8,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 59,
			'parent_id' => 22,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 9,
			'logout_message' => 'Level 3 logout message',
			'order' => 9999
		),
		array(
			'id' => 14,
			'parent_id' => NULL,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 10,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 26,
			'parent_id' => 14,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 11,
			'logout_message' => 'Level 2 Logout message',
			'order' => 9999
		),
		array(
			'id' => 6,
			'parent_id' => NULL,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 12,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 7,
			'parent_id' => 6,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 13,
			'logout_message' => 'Other logout message.',
			'order' => 9999
		),
		array(
			'id' => 25,
			'parent_id' => 1,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 14,
			'logout_message' => 'fdsadas',
			'order' => 9999
		),
		array(
			'id' => 70,
			'parent_id' => 25,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 15,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 1,
			'parent_id' => NULL,
			'kiosk_id' => 10,
			'status' => 0,
			'button_id' => 16,
			'logout_message' => NULL,
			'order' => 0
		),
		array(
			'id' => 22,
			'parent_id' => 1,
			'kiosk_id' => 10,
			'status' => 0,
			'button_id' => 17,
			'logout_message' => NULL,
			'order' => 1
		),
		array(
			'id' => 59,
			'parent_id' => 22,
			'kiosk_id' => 10,
			'status' => 0,
			'button_id' => 18,
			'logout_message' => NULL,
			'order' => 3
		),
		array(
			'id' => 60,
			'parent_id' => 22,
			'kiosk_id' => 10,
			'status' => 0,
			'button_id' => 19,
			'logout_message' => NULL,
			'order' => 2
		),
		array(
			'id' => 73,
			'parent_id' => NULL,
			'kiosk_id' => 2,
			'status' => 0,
			'button_id' => 20,
			'logout_message' => 'Good luck hope you win!',
			'order' => 9999
		),
		array(
			'id' => 1,
			'parent_id' => NULL,
			'kiosk_id' => 7,
			'status' => 0,
			'button_id' => 28,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 22,
			'parent_id' => 1,
			'kiosk_id' => 7,
			'status' => 0,
			'button_id' => 29,
			'logout_message' => NULL,
			'order' => 9999
		),
		array(
			'id' => 50,
			'parent_id' => NULL,
			'kiosk_id' => 7,
			'status' => 0,
			'button_id' => 30,
			'logout_message' => NULL,
			'order' => 9999
		),
	);
}
?>