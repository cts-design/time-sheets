<?php
/* EcourseResponse Fixture generated on: 2013-02-04 14:26:40 : 1360006000 */
class EcourseResponseFixture extends CakeTestFixture {
	var $name = 'EcourseResponse';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'ecourse_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'incomplete', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'reset' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'time_spent' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ecourse_id' => array('column' => 'ecourse_id', 'unique' => 0), 'user_id' => array('column' => 'user_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'ecourse_id' => 1,
			'user_id' => 1,
			'status' => 'Lorem ipsum dolor sit amet',
			'reset' => 1,
			'time_spent' => '14:26:40',
			'created' => '2013-02-04 14:26:40',
			'modified' => '2013-02-04 14:26:40'
		),
	);
}
?>