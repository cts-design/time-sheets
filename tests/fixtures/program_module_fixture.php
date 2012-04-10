<?php
/* ProgramModule Fixture generated on: 2012-04-10 10:40:49 : 1334068849 */
class ProgramModuleFixture extends CakeTestFixture {
	var $name = 'ProgramModule';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'passing_percentage' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'program_id' => array('column' => 'program_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'passing_percentage' => 1,
			'created' => '2012-04-10 10:40:49',
			'modified' => '2012-04-10 10:40:49'
		),
	);
}
?>