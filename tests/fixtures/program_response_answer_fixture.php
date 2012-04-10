<?php
/* ProgramResponseAnswer Fixture generated on: 2012-04-10 10:43:21 : 1334069001 */
class ProgramResponseAnswerFixture extends CakeTestFixture {
	var $name = 'ProgramResponseAnswer';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_response_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'program_form_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'program_module_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'answers' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'percent_correct' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'program_response_id' => array('column' => 'program_response_id', 'unique' => 0), 'program_form_id' => array('column' => 'program_form_id', 'unique' => 0), 'program_module_id' => array('column' => 'program_module_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_response_id' => 1,
			'program_form_id' => 1,
			'program_module_id' => 1,
			'answers' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'percent_correct' => 1,
			'created' => '2012-04-10 10:43:21',
			'modified' => '2012-04-10 10:43:21'
		),
	);
}
?>