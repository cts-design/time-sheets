<?php
/* ProgramPaperForm Fixture generated on: 2011-04-21 19:27:44 : 1303414064 */
class ProgramPaperFormFixture extends CakeTestFixture {
	var $name = 'ProgramPaperForm';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'template' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_id' => 1,
			'template' => 'Lorem ipsum dolor sit amet',
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2011-04-21 19:27:44',
			'modified' => '2011-04-21 19:27:44'
		),
	);
}
?>