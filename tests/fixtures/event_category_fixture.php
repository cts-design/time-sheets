<?php
/* EventCategory Fixture generated on: 2011-04-01 15:56:04 : 1301687764 */
class EventCategoryFixture extends CakeTestFixture {
	var $name = 'EventCategory';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Board Meetings',
			'created' => '2011-02-23 19:58:30',
			'modified' => '2011-02-23 19:58:30'
		),
		array(
			'id' => 2,
			'name' => 'Business Seminars',
			'created' => '2011-02-23 20:04:22',
			'modified' => '2011-02-23 20:04:22'
		),
		array(
			'id' => 3,
			'name' => 'Job Fairs',
			'created' => '2011-02-23 20:05:28',
			'modified' => '2011-02-23 20:05:28'
		),
		array(
			'id' => 4,
			'name' => 'Networking Events',
			'created' => '2011-02-23 20:05:51',
			'modified' => '2011-02-23 20:06:41'
		),
		array(
			'id' => 6,
			'name' => 'Workshops',
			'created' => '2011-02-23 20:10:24',
			'modified' => '2011-02-23 20:10:24'
		),
	);
}
?>