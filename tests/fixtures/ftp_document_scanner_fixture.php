<?php
/* FtpDocumentScanner Fixture generated on: 2010-11-05 18:11:49 : 1288983529 */
class FtpDocumentScannerFixture extends CakeTestFixture {
	var $name = 'FtpDocumentScanner';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'device_ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'device_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'device_ip' => 'Lorem ipsum dolor sit amet',
			'device_name' => 'Lorem ipsum dolor sit amet',
			'location_id' => 1,
			'deleted' => 1,
			'created' => '2010-11-05 18:58:49',
			'modified' => '2010-11-05 18:58:49'
		),
	);
}
?>