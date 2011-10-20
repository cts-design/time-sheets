<?php
/* KioskSurveyQuestion Fixture generated on: 2011-09-08 08:43:16 : 1315485796 */
class KioskSurveyQuestionFixture extends CakeTestFixture {
	var $name = 'KioskSurveyQuestion';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'kiosk_survey_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'question' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'options' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 6),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'kiosk_survey_id' => array('column' => 'kiosk_survey_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'kiosk_survey_id' => 1,
			'question' => 'What color is the sky?',
			'type' => 'multi',
			'options' => 'blue,red,yellow,green',
			'order' => 1,
			'created' => '2011-09-08 08:43:14',
			'modified' => '2011-09-08 08:43:14'
		),
		array(
			'id' => 2,
			'kiosk_survey_id' => 1,
			'question' => 'What color is the ground?',
			'type' => 'multi',
			'options' => 'black,brown,green,no',
			'order' => 2,
			'created' => '2011-09-08 08:43:14',
			'modified' => '2011-09-08 08:43:14'
		),
		array(
			'id' => 3,
			'kiosk_survey_id' => 1,
			'question' => 'Do birds fly?',
			'type' => 'yesno',
			'options' => '',
			'order' => 3,
			'created' => '2011-09-08 08:43:14',
			'modified' => '2011-09-08 08:43:14'
		),
		array(
			'id' => 4,
			'kiosk_survey_id' => 1,
			'question' => 'Do fish fly?',
			'type' => 'yesno',
			'options' => '',
			'order' => 4,
			'created' => '2011-09-08 08:43:14',
			'modified' => '2011-09-08 08:43:14'
		),
		array(
			'id' => 5,
			'kiosk_survey_id' => 1,
			'question' => 'All cats are black',
			'type' => 'truefalse',
			'options' => '',
			'order' => 5,
			'created' => '2011-09-08 08:43:14',
			'modified' => '2011-09-08 08:43:14'
		),
	);
}
?>
