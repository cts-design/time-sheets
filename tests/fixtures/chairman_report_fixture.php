<?php
/* ChairmanReport Fixture generated on: 2011-02-09 18:13:19 : 1297275199 */
class ChairmanReportFixture extends CakeTestFixture {
	var $name = 'ChairmanReport';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'file' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'title' => array('column' => array('title', 'file'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
                array(
			'id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'file' => 'http://atlas.dev/files/public/file.pdf',
			'created' => '2011-02-09 15:20:21',
			'modified' => '2011-02-09 15:20:21'
		),
	);
}
?>