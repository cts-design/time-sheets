<?php
/* DeletedDocument Fixture generated on: 2010-12-02 19:12:59 : 1291318919 */
class DeletedDocumentFixture extends CakeTestFixture {
	var $name = 'DeletedDocument';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'filename' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'queue_category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'admin_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'scanned_location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'filed_location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'deleted_location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'cat_1' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'cat_2' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'cat_3' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'entry_method' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'deleted_reason' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last_activity_admin_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array( //today
			'id' => 1,
			'filename' => '201102181820401535034.pdf',
			'queue_category_id' => NULL,
			'admin_id' => 1,
			'user_id' => 1,
			'scanned_location_id' => NULL, 
			'filed_location_id' => 1,
			'deleted_location_id' => 1,
			'cat_1' => 1,
			'cat_2' => 2,
			'cat_3' => '',
			'description' => '',
			'entry_method' => 'Upload',
			'deleted_reason' => 'Duplicate Scan',
			'last_activity_admin_id' => '2',
			'created' => '2011-02-18 19:41:59',
			'modified' => '2011-02-18 19:41:59'
		),
		array( //yesterday
			'id' => 2,
			'filename' => '201102181820401535034.pdf',
			'queue_category_id' => NULL,
			'admin_id' => 1,
			'user_id' => 1,
			'scanned_location_id' => NULL, 
			'filed_location_id' => 1,
			'deleted_location_id' => 1,
			'cat_1' => 1,
			'cat_2' => 2,
			'cat_3' => '',
			'description' => '',
			'entry_method' => 'Upload',
			'deleted_reason' => 'Duplicate Scan',
			'last_activity_admin_id' => '2',
			'created' => '2011-02-18 19:41:59',
			'modified' => '2011-02-17 19:41:59'
		),
		array( //last 7
			'id' => 3,
			'filename' => '201102181820401535034.pdf',
			'queue_category_id' => NULL,
			'admin_id' => 1,
			'user_id' => 1,
			'scanned_location_id' => NULL, 
			'filed_location_id' => 1,
			'deleted_location_id' => 1,
			'cat_1' => 1,
			'cat_2' => 2,
			'cat_3' => '',
			'description' => '',
			'entry_method' => 'Upload',
			'deleted_reason' => 'Duplicate Scan',
			'last_activity_admin_id' => '2',
			'created' => '2011-02-17 19:41:59',
			'modified' => '2011-02-17 19:41:59'
		),
		array( //last month
			'id' => 4,
			'filename' => '201102181820401535034.pdf',
			'queue_category_id' => NULL,
			'admin_id' => 1,
			'user_id' => 1,
			'scanned_location_id' => NULL, 
			'filed_location_id' => 1,
			'deleted_location_id' => 1,
			'cat_1' => 1,
			'cat_2' => 2,
			'cat_3' => '',
			'description' => '',
			'entry_method' => 'Upload',
			'deleted_reason' => 'Duplicate Scan',
			'last_activity_admin_id' => '2',
			'created' => '2011-02-17 19:41:59',
			'modified' => '2011-02-17 19:41:59'
		),
	);
	
	function init() {
		$this->records['0']['modified'] = date('Y-m-d h:i:s');
		$this->records['1']['modified'] = date('Y-m-d h:i:s', strtotime('-1 day'));
		$this->records['2']['modified'] = date('Y-m-d h:i:s', strtotime('-6 day'));
		$this->records['3']['modified'] = date('Y-m-d h:i:s', strtotime('-31 day'));
		parent::init();
	}
}
?>