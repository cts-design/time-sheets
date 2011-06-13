<?php
/* WatchedFilingCat Fixture generated on: 2011-06-13 14:46:57 : 1307990817 */
class WatchedFilingCatFixture extends CakeTestFixture {
	var $name = 'WatchedFilingCat';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'cat_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'cat_id' => array('column' => 'cat_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'cat_id' => 253,
			'program_id' => 1
		),
		array(
			'id' => 2,
			'cat_id' => 252,
			'program_id' => 1
		),
		array(
			'id' => 3,
			'cat_id' => 254,
			'program_id' => 1
		),
	);
}
