<?php
/* DocumentQueueFilter Fixture generated on: 2012-02-14 09:52:36 : 1329231156 */
class DocumentQueueFilterFixture extends CakeTestFixture {
	var $name = 'DocumentQueueFilter';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'locations' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'queue_cats' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'from_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'to_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'auto_load_docs' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => '1',
			'user_id' => '2',
			'locations' => '[]',
			'queue_cats' => '[]',
			'from_date' => NULL,
			'to_date' => NULL,
			'auto_load_docs' => 0,
			'created' => '2012-01-26 15:05:49',
			'modified' => '2012-02-13 10:22:56'
		),
		array(
			'id' => '2',
			'user_id' => '1',
			'locations' => '["1","2","3"]',
			'queue_cats' => '["1","2","4","5","3","6"]',
			'from_date' => NULL,
			'to_date' => NULL,
			'auto_load_docs' => 0,
			'created' => '2012-01-26 15:32:10',
			'modified' => '2012-02-13 10:32:34'
		),
		array(
			'id' => '5',
			'user_id' => '3',
			'locations' => '["1","2","3"]',
			'queue_cats' => '[]',
			'from_date' => NULL,
			'to_date' => NULL,
			'auto_load_docs' => 0,
			'created' => '2012-02-08 10:58:47',
			'modified' => '2012-02-09 14:20:50'
		),
		array(
			'id' => '6',
			'user_id' => '17',
			'locations' => '[]',
			'queue_cats' => '[]',
			'from_date' => NULL,
			'to_date' => NULL,
			'auto_load_docs' => 0,
			'created' => '2012-02-10 10:31:41',
			'modified' => '2012-02-10 10:31:59'
		),
	);
}
?>