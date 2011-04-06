<?php
/* ProgramResponseDoc Fixture generated on: 2011-04-06 18:32:37 : 1302114757 */
class ProgramResponseDocFixture extends CakeTestFixture {
	var $name = 'ProgramResponseDoc';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'cat_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'program_response_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'filename' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'cat_id' => array('column' => 'cat_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'cat_id' => 1,
			'program_response_id' => 1,
			'filename' => 'Lorem ipsum dolor sit amet',
			'created' => '2011-04-06 18:32:37',
			'modified' => '2011-04-06 18:32:37'
		),
	);
}
?>