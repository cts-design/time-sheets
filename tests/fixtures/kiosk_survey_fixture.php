<?php
/* KioskSurvey Fixture generated on: 2011-08-23 13:16:43 : 1314119803 */
class KioskSurveyFixture extends CakeTestFixture {
	var $name = 'KioskSurvey';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Veteran Services',
			'created' => '2011-08-23 13:16:43',
			'modified' => '2011-08-23 13:16:43'
		),
	);
}
?>
