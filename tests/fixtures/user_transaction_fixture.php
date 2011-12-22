<?php
/* UserTransaction Fixture generated on: 2010-10-19 16:10:28 : 1287504148 */
class UserTransactionFixture extends CakeTestFixture {
	var $name = 'UserTransaction';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'admin_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'location' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'module' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'details' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'admin_id' => 1,
			'user_id' => 1,
			'location' => 'Lorem ipsum dolor sit amet',
			'module' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet',
			'created' => '2010-10-19 16:02:28'
		),
		array(
			'id' => 2,
			'admin_id' => 1,
			'user_id' => 9,
			'location' => 1,
			'module' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet',
			'created' => '2010-10-19 16:02:28'
		)		
	);
}
?>