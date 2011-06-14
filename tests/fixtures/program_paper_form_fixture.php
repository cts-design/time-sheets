<?php
/* ProgramPaperForm Fixture generated on: 2011-06-13 14:55:59 : 1307991359 */
class ProgramPaperFormFixture extends CakeTestFixture {
	var $name = 'ProgramPaperForm';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'template' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'cat_1' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'cat_2' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'cat_3' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'cert' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_id' => 1,
			'template' => 'vpk_app.pdf',
			'name' => 'VPK Application',
			'cat_1' => 250,
			'cat_2' => 251,
			'cat_3' => 255,
			'cert' => 0,
			'created' => '2011-04-21 15:29:40',
			'modified' => '2011-04-21 15:29:43'
		),
		array(
			'id' => 2,
			'program_id' => 1,
			'template' => 'vpk_coe.pdf',
			'name' => 'VPK COE',
			'cat_1' => 250,
			'cat_2' => 251,
			'cat_3' => 256,
			'cert' => 1,
			'created' => '2011-04-21 15:30:15',
			'modified' => '2011-04-21 15:30:17'
		),
	);
}
