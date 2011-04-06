<?php
/* WatchedFilingCat Fixture generated on: 2011-04-06 15:17:53 : 1302103073 */
class WatchedFilingCatFixture extends CakeTestFixture {
	var $name = 'WatchedFilingCat';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'cat_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'cat_id' => array('column' => 'cat_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'cat_id' => 1,
			'program_id' => 1
		),
	);
}
?>