<?php
/* SelfScanCategory Fixture generated on: 2010-12-15 21:12:27 : 1292447907 */
class SelfScanCategoryFixture extends CakeTestFixture {
	var $name = 'SelfScanCategory';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'queue_cat_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'cat_1' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'cat_2' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'cat_3' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'queue_cat_id' => 1,
			'cat_1' => 1,
			'cat_2' => 1,
			'cat_3' => 1,
			'parent_id' => 1,
			'created' => '2010-12-15 21:18:27',
			'modified' => '2010-12-15 21:18:27'
		),
	);
}
?>