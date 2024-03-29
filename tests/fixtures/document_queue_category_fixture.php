<?php
/* DocumentQueueCategory Fixture generated on: 2010-11-05 19:11:36 : 1288984776 */
class DocumentQueueCategoryFixture extends CakeTestFixture {
	var $name = 'DocumentQueueCategory';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'ftp_path' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'secure' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'secure_admins' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'ftp_path' => '/ftp/path/to/file',
			'name' => 'Name1',
			'deleted' => 1,
			'created' => '2010-11-05 19:19:36',
			'modified' => '2010-11-05 19:19:36'
		),
		array(
			'id' => 2,
			'ftp_path' => '/ftp/path/to/file',
			'name' => 'Name2',
			'deleted' => 0,
			'secure' => 1,
			'secure_admins' => '[2]',
			'created' => '2010-11-05 19:19:36',
			'modified' => '2010-11-05 19:19:36'
		),
	);
}
?>