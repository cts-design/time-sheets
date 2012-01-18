<?php
/* DocumentQueueFilter Fixture generated on: 2012-01-18 15:22:26 : 1326918146 */
class DocumentQueueFilterFixture extends CakeTestFixture {
	var $name = 'DocumentQueueFilter';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'locations' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'queue_cats' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'date_from' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'date_to' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'auto_load_docs' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'locations' => 'Lorem ipsum dolor sit amet',
			'queue_cats' => 'Lorem ipsum dolor sit amet',
			'date_from' => '2012-01-18',
			'date_to' => '2012-01-18',
			'auto_load_docs' => 1
		),
	);
}
?>