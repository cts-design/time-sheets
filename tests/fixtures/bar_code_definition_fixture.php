<?php
/* BarCodeDefinition Fixture generated on: 2011-10-04 11:42:00 : 1317742920 */
class BarCodeDefinitionFixture extends CakeTestFixture {
	var $name = 'BarCodeDefinition';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'number' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
		'cat_1' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'cat_2' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'cat_3' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'number' => 1,
			'cat_1' => 1,
			'cat_2' => 3,
			'cat_3' => 4,
			'created' => '2011-10-04 11:42:00',
			'modified' => '2011-10-04 11:42:00'
		),
	);
}
?>