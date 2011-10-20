<?php
/* KioskSurveyResponseAnswer Fixture generated on: 2011-09-08 08:39:40 : 1315485580 */
class KioskSurveyResponseAnswerFixture extends CakeTestFixture {
	var $name = 'KioskSurveyResponseAnswer';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'kiosk_survey_response_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'question_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'answer' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'survey_response_id' => array('column' => array('kiosk_survey_response_id', 'question_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'kiosk_survey_response_id' => 1,
			'question_id' => 1,
			'answer' => 'Blue',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 2,
			'kiosk_survey_response_id' => 1,
			'question_id' => 2,
			'answer' => 'Brown',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 3,
			'kiosk_survey_response_id' => 1,
			'question_id' => 3,
			'answer' => 'Yes',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 4,
			'kiosk_survey_response_id' => 1,
			'question_id' => 4,
			'answer' => 'No',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 5,
			'kiosk_survey_response_id' => 1,
			'question_id' => 5,
			'answer' => 'true',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 6,
			'kiosk_survey_response_id' => 2,
			'question_id' => 1,
			'answer' => 'Yellow',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 7,
			'kiosk_survey_response_id' => 2,
			'question_id' => 2,
			'answer' => 'Green',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 8,
			'kiosk_survey_response_id' => 2,
			'question_id' => 3,
			'answer' => 'No',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 9,
			'kiosk_survey_response_id' => 2,
			'question_id' => 4,
			'answer' => 'Yes',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
		array(
			'id' => 10,
			'kiosk_survey_response_id' => 2,
			'question_id' => 5,
			'answer' => 'False',
			'created' => '2011-09-08 08:39:39',
			'modified' => '2011-09-08 08:39:39'
		),
	);
}
?>
