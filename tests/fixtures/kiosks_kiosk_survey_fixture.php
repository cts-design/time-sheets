<?php
/* KiosksKioskSurvey Fixture generated on: 2011-09-08 08:41:27 : 1315485687 */
class KiosksKioskSurveyFixture extends CakeTestFixture {
	var $name = 'KiosksKioskSurvey';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'kiosk_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'kiosk_survey_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'kiosk_id' => array('column' => array('kiosk_id', 'kiosk_survey_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'kiosk_id' => 1,
			'kiosk_survey_id' => 1
		),
	);
}
?>
