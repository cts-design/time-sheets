<?php
/* SurveyQuestion Fixture generated on: 2011-03-15 14:18:47 : 1300198727 */
class SurveyQuestionFixture extends CakeTestFixture {
	var $name = 'SurveyQuestion';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 9, 'key' => 'primary'),
		'survey_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 9),
		'question' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 60, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'survey_id' => 1,
			'question' => 'First Name',
			'type' => 'text',
			'created' => '2011-03-15 14:18:47',
			'modified' => '2011-03-15 14:18:47'
		),
		array(
			'id' => 2,
			'survey_id' => 1,
			'question' => 'Last Name',
			'type' => 'text',
			'created' => '2011-03-15 14:18:47',
			'modified' => '2011-03-15 14:18:47'
		),
		array(
			'id' => 3,
			'survey_id' => 1,
			'question' => 'Date of Birth',
			'type' => 'date',
			'created' => '2011-03-15 14:18:47',
			'modified' => '2011-03-15 14:18:47'
		),
		array(
			'id' => 4,
			'survey_id' => 2,
			'question' => 'First Name',
			'type' => 'text',
			'created' => '2011-03-15 14:18:47',
			'modified' => '2011-03-15 14:18:47'
		),
		array(
			'id' => 5,
			'survey_id' => 2,
			'question' => 'Last Name',
			'type' => 'text',
			'created' => '2011-03-15 14:18:47',
			'modified' => '2011-03-15 14:18:47'
		),
		array(
			'id' => 6,
			'survey_id' => 2,
			'question' => 'Suggestions',
			'type' => 'text',
			'created' => '2011-03-15 14:18:47',
			'modified' => '2011-03-15 14:18:47'
		),
	);
}
?>