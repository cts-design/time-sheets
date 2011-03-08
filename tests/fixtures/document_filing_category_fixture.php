<?php
/* DocumentFilingCategory Fixture generated on: 2010-10-19 15:10:41 : 1287503861 */
class DocumentFilingCategoryFixture extends CakeTestFixture {
	var $name = 'DocumentFilingCategory';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'disabled' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => NULL,
			'name' => 'Valid Category',
			'disabled' => 0,
			'lft' => 1,
			'rght' => 2,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
		),
		array(
			'id' => 2,
			'parent_id' => NULL,
			'name' => 'Disabled Category',
			'disabled' => 1,
			'lft' => 2,
			'rght' => 5,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
		),
		array(
			'id' => 3,
			'parent_id' => 2,
			'name' => 'A Nested Valid Category',
			'disabled' => 0,
			'lft' => 3,
			'rght' => 6,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
		),
        array(
			'id' => 4,
			'parent_id' => 3,
			'name' => 'Aother Nested Disabled Category',
			'disabled' => 0,
			'lft' => 5,
			'rght' => 6,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
        )
	);
}
?>