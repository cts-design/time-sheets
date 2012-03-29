<?php
/* QueuedDocument Fixture generated on: 2012-02-13 11:20:19 : 1329150019 */
class QueuedDocumentFixture extends CakeTestFixture {
	var $name = 'QueuedDocument';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'filename' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'queue_category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'bar_code_definition_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'scanned_location_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 11, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locked_status' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'self_scan_cat_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'entry_method' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_activity_admin_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => '48',
			'filename' => '201112300934197126580.pdf',
			'queue_category_id' => '2',
			'bar_code_definition_id' => '21',
			'scanned_location_id' => '1',
			'locked_by' => NULL,
			'locked_status' => 0,
			'self_scan_cat_id' => NULL,
			'entry_method' => 'Program Upload',
			'last_activity_admin_id' => '1',
			'user_id' => '43',
			'created' => '2011-12-06 11:14:12',
			'modified' => '2012-02-13 10:33:35'
		),
		array(
			'id' => '49',
			'filename' => '201112300934197126580.pdf',
			'queue_category_id' => '6',
			'bar_code_definition_id' => '20',
			'scanned_location_id' => '2',
			'locked_by' => '2',
			'locked_status' => '1',
			'self_scan_cat_id' => NULL,
			'entry_method' => 'Program Upload',
			'last_activity_admin_id' => '2',
			'user_id' => '9',
			'created' => '2011-12-06 11:14:12',
			'modified' => '2012-02-13 10:33:57'
		)
	);
}
?>