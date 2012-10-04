<?php
/* ProgramStep Fixture generated on: 2012-04-10 10:44:33 : 1334069073 */
class ProgramStepFixture extends CakeTestFixture {
	var $name = 'ProgramStep';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_module_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'order' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'program_module_id' => array('column' => 'program_module_id', 'unique' => 0), 'program_step_type_id' => array('column' => 'type', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_module_id' => 1,
			'type' => 'Lorem ipsum dolor sit amet',
			'order' => 1,
			'created' => '2012-04-10 10:44:33',
			'modified' => '2012-04-10 10:44:33'
		),
	);
}
?>