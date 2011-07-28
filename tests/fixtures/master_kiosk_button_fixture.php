<?php
/* MasterKioskButton Fixture generated on: 2011-07-15 14:56:56 : 1310756216 */
class MasterKioskButtonFixture extends CakeTestFixture {
	var $name = 'MasterKioskButton';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'tag' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => NULL,
			'name' => 'Cash Assistance & Food Stamps',
			'tag' => 'Test Tag',
			'deleted' => 0
		),
		array(
			'id' => 6,
			'parent_id' => NULL,
			'name' => 'Look For A Job',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 7,
			'parent_id' => 6,
			'name' => 'Other',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 8,
			'parent_id' => NULL,
			'name' => 'Orientations',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 9,
			'parent_id' => NULL,
			'name' => 'FSET',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 10,
			'parent_id' => NULL,
			'name' => 'Veteran Services',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 11,
			'parent_id' => 9,
			'name' => 'Cash Assistance',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 12,
			'parent_id' => NULL,
			'name' => 'Copy/Fax',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 13,
			'parent_id' => NULL,
			'name' => 'Partners',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 14,
			'parent_id' => NULL,
			'name' => 'Orientations',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 15,
			'parent_id' => NULL,
			'name' => 'Workshops/Seminars',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 16,
			'parent_id' => 13,
			'name' => 'CCC',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 17,
			'parent_id' => NULL,
			'name' => 'Resource Room',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 18,
			'parent_id' => NULL,
			'name' => 'Tests',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 19,
			'parent_id' => 9,
			'name' => 'Gas card',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 22,
			'parent_id' => 1,
			'name' => 'Cash Assistance (WTP)',
			'tag' => 'Please select your cash program',
			'deleted' => 0
		),
		array(
			'id' => 23,
			'parent_id' => 1,
			'name' => 'Food Stamp Employ & Training',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 24,
			'parent_id' => NULL,
			'name' => 'Job Smart Workshops',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 25,
			'parent_id' => 1,
			'name' => 'Job Smart Workshops',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 26,
			'parent_id' => 14,
			'name' => 'Cash Assistance (WTP)',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 27,
			'parent_id' => 15,
			'name' => 'Basic Computer',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 28,
			'parent_id' => 15,
			'name' => 'Creating a Resume',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 29,
			'parent_id' => 15,
			'name' => 'Job Power Seminar',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 30,
			'parent_id' => 14,
			'name' => 'Unemployment',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 31,
			'parent_id' => NULL,
			'name' => 'Unemployment',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 32,
			'parent_id' => 31,
			'name' => 'File Unemployment Claim',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 33,
			'parent_id' => 31,
			'name' => 'Claim Weeks',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 34,
			'parent_id' => 13,
			'name' => 'AARP',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 35,
			'parent_id' => 13,
			'name' => 'Deaf Services Bureau',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 36,
			'parent_id' => NULL,
			'name' => 'Experience Works',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 37,
			'parent_id' => 13,
			'name' => 'Experience Works',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 38,
			'parent_id' => 13,
			'name' => 'Job Corps',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 39,
			'parent_id' => 13,
			'name' => 'Non Custodial Parent Employ',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 40,
			'parent_id' => 13,
			'name' => 'Disability Navigator',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 42,
			'parent_id' => 9,
			'name' => 'Test',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 43,
			'parent_id' => 22,
			'name' => 'Other',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 44,
			'parent_id' => 22,
			'name' => 'Test 3',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 45,
			'parent_id' => NULL,
			'name' => 'Scan Documents',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 46,
			'parent_id' => NULL,
			'name' => 'Training',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 47,
			'parent_id' => NULL,
			'name' => 'Appointment',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 48,
			'parent_id' => 31,
			'name' => 'Questions',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 49,
			'parent_id' => 15,
			'name' => 'Intro to Computers',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 50,
			'parent_id' => NULL,
			'name' => 'Interview Skills',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 51,
			'parent_id' => NULL,
			'name' => 'Professional',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 52,
			'parent_id' => 15,
			'name' => 'Intro to EFM',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 53,
			'parent_id' => 14,
			'name' => 'Cash Assistance',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 54,
			'parent_id' => 15,
			'name' => 'Food Stamps Employment & TRN.',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 55,
			'parent_id' => NULL,
			'name' => 'WIA',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 56,
			'parent_id' => NULL,
			'name' => 'One Stop Overview',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 57,
			'parent_id' => 15,
			'name' => 'Disability Program Navigator',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 58,
			'parent_id' => 1,
			'name' => 'Other',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 59,
			'parent_id' => 22,
			'name' => 'More Cash Stuffs',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 60,
			'parent_id' => 22,
			'name' => 'test',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 61,
			'parent_id' => 13,
			'name' => 'Test',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 62,
			'parent_id' => 10,
			'name' => 'Tesster',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 63,
			'parent_id' => 56,
			'name' => 'test',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 64,
			'parent_id' => 56,
			'name' => 'test 2',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 65,
			'parent_id' => NULL,
			'name' => 'test ',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 66,
			'parent_id' => 56,
			'name' => 'test 3',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 67,
			'parent_id' => NULL,
			'name' => 'test 4',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 68,
			'parent_id' => 65,
			'name' => 'test child',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 69,
			'parent_id' => 68,
			'name' => 'test grand',
			'tag' => NULL,
			'deleted' => 1
		),
		array(
			'id' => 70,
			'parent_id' => 25,
			'name' => 'other',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 71,
			'parent_id' => 65,
			'name' => 'test 4',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 72,
			'parent_id' => 71,
			'name' => 'tygfdasf',
			'tag' => NULL,
			'deleted' => 0
		),
		array(
			'id' => 73,
			'parent_id' => NULL,
			'name' => 'Register To Win A Kindle',
			'tag' => NULL,
			'deleted' => 0
		),
	);
}
?>