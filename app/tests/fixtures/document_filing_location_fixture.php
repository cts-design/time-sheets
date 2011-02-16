<?php
/* DocumentFilingLocation Fixture generated on: 2010-10-19 15:10:41 : 1287503861 */
class DocumentFilingLocationFixture extends CakeTestFixture {
	var $name = 'DocumentFilingLocation';

	var $fields = array(
		'int' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'scanner_ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'scanner_display_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'general_display_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'int', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'int' => 1,
			'scanner_ip' => 'Lorem ipsum dolor sit amet',
			'scanner_display_name' => 'Lorem ipsum dolor sit amet',
			'general_display_name' => 'Lorem ipsum dolor sit amet',
			'deleted' => 1,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
		),
	);
}
?>