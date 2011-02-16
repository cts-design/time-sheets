<?php
/* QueuedDocument Fixture generated on: 2010-11-08 13:11:31 : 1289221951 */
class QueuedDocumentFixture extends CakeTestFixture {
	var $name = 'QueuedDocument';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'filename' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'queue_category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'location_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 11, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locked_status' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'self_scan_cat_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'filename' => 'Lorem ipsum dolor sit amet',
			'queue_category_id' => 1,
			'location_id' => 'Lorem ips',
			'locked_by' => 1,
			'locked_status' => 1,
			'self_scan_cat_id' => 1,
			'created' => '2010-11-08 13:12:31'
		),
	);
}
?>