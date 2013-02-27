<?php
/* EcourseUser Fixture generated on: 2013-02-18 11:49:34 : 1361206174 */
class EcourseUserFixture extends CakeTestFixture {
	var $name = 'EcourseUser';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'ecourse_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'ecourse_id' => 1,
			'user_id' => 1
		),
	);
}
?>