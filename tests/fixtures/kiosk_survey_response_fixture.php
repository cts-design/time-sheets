<?php
/* KioskSurveyResponse Fixture generated on: 2011-09-08 08:39:45 : 1315485585 */
class KioskSurveyResponseFixture extends CakeTestFixture {
	var $name = 'KioskSurveyResponse';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'kiosk_survey_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'user_id' => array('column' => array('user_id', 'kiosk_survey_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 0,
			'kiosk_survey_id' => 1,
			'created' => '2011-09-08 08:39:45',
			'modified' => '2011-09-08 08:39:45'
		),
		array(
			'id' => 2,
			'user_id' => 0,
			'kiosk_survey_id' => 1,
			'created' => '2011-09-08 08:39:45',
			'modified' => '2011-09-08 08:39:45'
		),
	);
}
?>
