<?php
/* ProgramResponse Fixture generated on: 2011-05-19 14:31:50 : 1305829910 */
class ProgramResponseFixture extends CakeTestFixture {
	var $name = 'ProgramResponse';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'answers' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'viewed_media' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'form_esignature' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'complete' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'needs_approval' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'conformation_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 12, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'expires_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'prog_id' => array('column' => 'program_id', 'unique' => 0), 'user_id' => array('column' => 'user_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_id' => 1,
			'answers' => '',
			'user_id' => 9,
			'viewed_media' => 1,
			'form_esignature' => 'Smith',
			'complete' => 0,
			'needs_approval' => 0,
			'conformation_id' => '',
			'created' => '2011-05-04 09:09:08',
			'modified' => '2011-05-12 11:12:24',
			'expires_on' => '2011-06-08 16:13:30'
		),
		array(
			'id' => 3,
			'program_id' => 1,
			'answers' => NULL,
			'user_id' => 9,
			'viewed_media' => 0,
			'form_esignature' => '',
			'complete' => 0,
			'needs_approval' => 0,
			'conformation_id' => '5682ac0ce',
			'created' => '2011-05-12 11:12:16',
			'modified' => '2011-05-12 11:12:16',
			'expires_on' => '2011-06-11 11:12:16'
		),
		array(
			'id' => 2,
			'program_id' => 1,
			'answers' => '{\"vpk_program_year\":\"2011-2012\",\"preferred_program_schedule\":\"Summer Program\",\"preferred_program_setting\":\"Private provider\",\"how_did_you_hear_about_vpk\":\"Television\",\"in_which_county_do_you_wish_your_child_to_receive_VPK_services\":\"Pinellas\",\"parent_or_guardian_title\":\"Mr.\",\"parent_or_guardian_first_name\":\"Bob\",\"parent_or_guardian_middle_name\":\"A\",\"parent_or_guardian_last_name\":\"Smtih\",\"parent_or_guardian_surname\":\"\",\"parent_or_guardian_home_address\":\"123 test ave\",\"parent_or_guardian_city\":\"spring hill\",\"parent_or_guardian_county\":\"hernando\",\"parent_or_guardian_state\":\"FL\",\"parent_or_guardian_zip_code\":\"34609\",\"parent_or_guardian_relationship_to_child\":\"Father\",\"other_parent_or_guardian_first_name\":\"\",\"other_parent_or_guardian_middle_name\":\"\",\"other_parent_or_guardian_last_name\":\"\",\"other_parent_or_guardian_surname\":\"\",\"other_parent_or_guardian_home_address\":\"\",\"other_parent_or_guardian_city\":\"\",\"other_parent_or_guardian_county\":\"\",\"other_parent_or_guardian_relationship_to_child\":\"\",\"would_you_like_to_receive_information_about_other_early_leaning_programs_and_services\":\"No\",\"form_esignature\":\"Smith\", \"form_completed\":\"12/22/11\"}',
			'user_id' => 9,
			'viewed_media' => 0,
			'form_esignature' => '',
			'complete' => 0,
			'needs_approval' => 0,
			'conformation_id' => '5682ac0ce',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'expires_on' => '0000-00-00 00:00:00'
		),
	);
}
?>