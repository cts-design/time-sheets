<?php
class AuditsUserFixture extends CakeTestFixture {
	var $name = 'AuditsUser';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'audit_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'audit_id' => 1,
			'user_id' => 12
		),
		array(
			'id' => 2,
			'audit_id' => 1,
			'user_id' => 14
		),
		array(
			'id' => 3,
			'audit_id' => 2,
			'user_id' => 15
		),
		array(
			'id' => 4,
			'audit_id' => 2,
			'user_id' => 16
		)
	);
}
