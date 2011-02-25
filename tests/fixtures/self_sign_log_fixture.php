<?php
/* SelfSignLog Fixture generated on: 2010-09-30 15:09:08 : 1285861928 */
class SelfSignLogFixture extends CakeTestFixture {
	var $name = 'SelfSignLog';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'kiosk_location' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'level_1' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'level_2' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'level_3' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'other' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'deleted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'kiosk_location' => 'Lorem ipsum dolor sit amet',
			'level_1' => 'Lorem ipsum dolor sit amet',
			'level_2' => 'Lorem ipsum dolor sit amet',
			'level_3' => 'Lorem ipsum dolor sit amet',
			'other' => 'Lorem ipsum dolor sit amet',
			'status' => 1,
			'deleted' => 1,
			'created' => '2010-09-30 15:52:08',
			'modified' => '2010-09-30 15:52:08'
		),
	);
}
?>