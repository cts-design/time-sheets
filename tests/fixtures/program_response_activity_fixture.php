<?php
/* ProgramResponseActivity Fixture generated on: 2012-04-13 11:17:09 : 1334330229 */
class ProgramResponseActivityFixture extends CakeTestFixture {
	var $name = 'ProgramResponseActivity';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_response_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'program_form_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'completed' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'answers' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'percent_correct' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'program_step_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'program_response_id' => array('column' => 'program_response_id', 'unique' => 0), 'program_form_id' => array('column' => 'program_form_id', 'unique' => 0), 'program_step_id' => array('column' => 'program_step_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_response_id' => 1,
			'program_form_id' => 1,
			'type' => 'Lorem ipsum dolor sit amet',
			'completed' => 1,
			'answers' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'percent_correct' => 1,
			'created' => '2012-04-13 11:17:09',
			'modified' => '2012-04-13 11:17:09',
			'program_step_id' => 1
		),
	);
}
?>