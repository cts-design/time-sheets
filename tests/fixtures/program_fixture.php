<?php
/* Program Fixture generated on: 2011-03-29 13:18:18 : 1301404698 */
class ProgramFixture extends CakeTestFixture {
	var $name = 'Program';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'media' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'instructions' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'disabled' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'expires' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'VPK',
			'type' => 'video_registration_docs',
			'media' => '/programs/vpk/vpk.flv',
			'instructions' => 'Please watch the video below. You will be taken to the registration page automatically when the video is over. ',
			'disabled' => 0,
			'created' => '2011-03-23 00:00:00',
			'modified' => '2011-03-23 00:00:00',
			'expires' => '2011-04-23 00:00:00'
		),
		array(
			'id' => 2,
			'name' => 'FQZ',
			'type' => 'video_registration_docs',
			'media' => '/programs/fqz/fqz.flv',
			'instructions' => 'Please watch the video below. You will be taken to the registration page automatically when the video is over. ',
			'disabled' => 1,
			'created' => '2011-03-23 00:00:00',
			'modified' => '2011-03-23 00:00:00',
			'expires' => '2011-04-23 00:00:00'		
		)
	);
}
?>