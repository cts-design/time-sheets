<?php
/* EcourseModuleResponse Fixture generated on: 2013-02-11 10:15:14 : 1360595714 */
class EcourseModuleResponseFixture extends CakeTestFixture {
	var $name = 'EcourseModuleResponse';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'ecourse_response_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'score' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'pass_fail' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'media_started' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
		'media_finished' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
		'quiz_started' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ecourse_response_id' => array('column' => 'ecourse_response_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'ecourse_response_id' => 1,
			'score' => 1,
			'pass_fail' => 'Lorem ipsum dolor sit amet',
			'media_started' => 1360595714,
			'media_finished' => 1360595714,
			'quiz_started' => 1360595714
		),
	);
}
?>