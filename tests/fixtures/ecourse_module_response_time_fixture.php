<?php
/* EcourseModuleResponseTime Fixture generated on: 2013-02-11 10:13:01 : 1360595581 */
class EcourseModuleResponseTimeFixture extends CakeTestFixture {
	var $name = 'EcourseModuleResponseTime';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'ecourse_module_response_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'time_in' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
		'time_out' => array('type' => 'timestamp', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'module_response_id' => array('column' => 'ecourse_module_response_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'ecourse_module_response_id' => 1,
			'type' => 'Lorem ipsum dolor sit amet',
			'time_in' => 1360595581,
			'time_out' => 1360595581
		),
	);
}
?>