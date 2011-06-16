<?php
/* FiledDocument Fixture generated on: 2010-11-24 15:11:21 : 1290612381 */
class FiledDocumentFixture extends CakeTestFixture {
	var $name = 'FiledDocument';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'filename' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'admin_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'filed_location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'scanned_location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'cat_1' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'cat_2' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'cat_3' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'entry_method' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last_activity_admin_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'filename' => 'Lorem ipsum dolor sit amet',
			'admin_id' => 1,
			'user_id' => 10,
			'scanned_location_id' => 1,
			'filed_location_id' => 1,
			'cat_1' => 3,
			'cat_2' => 5,
			'cat_3' => 14,
			'description' => 'Lorem ipsum dolor sit amet',
			'entry_method' => 'upload',
			'last_activity_admin_id' => 2,
			'created' => '2010-11-24 15:26:21',
			'modified' => '2010-11-24 15:26:21'
		),
		array(
			'id' => 111,
			'filename' => 'Lorem ipsum dolor sit amet',
			'admin_id' => 2,
			'user_id' => 10,
			'scanned_location_id' => 1,
			'filed_location_id' => 1,
			'cat_1' => 2,
			'cat_2' => 12,
			'cat_3' => 13,
			'description' => 'Lorem ipsum dolor sit amet',
			'entry_method' => 'upload',
			'last_activity_admin_id' => 2,
			'created' => '2010-11-24 15:26:21',
			'modified' => '2010-11-24 15:26:21'
		),
		array(
			'id' => 112,
			'filename' => '20110601093409467863.pdf',
			'admin_id' => 2,
			'user_id' => 15,
			'scanned_location_id' => 1,
			'filed_location_id' => 1,
			'cat_1' => 2,
			'cat_2' => 12,
			'cat_3' => 13,
			'description' => 'Lorem ipsum dolor sit amet',
			'entry_method' => 'Program Generated',
			'last_activity_admin_id' => 2,
			'created' => '2010-11-24 15:26:21',
			'modified' => '2010-11-24 15:26:21'
		)					
	);
}
?>