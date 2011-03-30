<?php
/* ProgramResponse Fixture generated on: 2011-03-29 17:26:26 : 1301419586 */
class ProgramResponseFixture extends CakeTestFixture {
	var $name = 'ProgramResponse';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'answers' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'prog_id' => array('column' => 'program_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_id' => 1,
			'answers' => '{\"preferred_program_schedule\":\"Winter\\/Spring only\",\"preferred_program_setting\":\"Public school\"}'
		),
	);
}
?>