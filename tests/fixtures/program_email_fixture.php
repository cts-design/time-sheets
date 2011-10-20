<?php
/* ProgramEmail Fixture generated on: 2011-06-06 14:02:45 : 1307383365 */
class ProgramEmailFixture extends CakeTestFixture {
	var $name = 'ProgramEmail';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'cat_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'to' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'from' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'subject' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'body' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 25, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'disabled' => array('type' => 'boolean', null => false, 'default' => 0),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'program_id' => array('column' => 'program_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_id' => 1,
			'cat_id' => NULL,
			'to' => NULL,
			'from' => NULL,
			'subject' => 'Required Media Complete ',
			'body' => 'Thank you for viewing the VPK Orientation. Please be certain to log back into the system and complete the VPK online process if you exited at this stage. ',
			'type' => 'media',
			'name' => 'VPK Media',
			'disabled' => 0,
			'created' => '2011-04-04 10:44:25',
			'modified' => '2011-04-04 10:44:28'
		),
		array(
			'id' => 2,
			'program_id' => 1,
			'cat_id' => 253,
			'to' => NULL,
			'from' => NULL,
			'subject' => 'Proof of Residency',
			'body' => 'We have accepted your proof of residency. Thank you for using the VPK Online system.  You will receive further notification from us shortly. \r\n',
			'type' => 'docFiled',
			'name' => 'VPK Required Doc',
			'disabled' => 0,
			'created' => '2011-04-04 11:24:01',
			'modified' => '0000-00-00 00:00:00'
		),
		array(
			'id' => 3,
			'program_id' => 1,
			'cat_id' => NULL,
			'to' => NULL,
			'from' => NULL,
			'subject' => 'VPK Program Form Completed',
			'body' => 'Thank you for completing the Application Form.  \r\nYou now have the option of uploading the required documentation.  \r\nIn order to qualify for VPK, you must provide documentation of current residence, as well as proof of the child�s age. \r\nExamples of proof of residency:  \r\nValid Florida Driver�s License with current address\r\nCurrent Utility bill in your name\r\nCurrent Pay Stub\r\n\r\nExamples of date of birth proof:\r\nBirth Certificate\r\nImmunization Record signed by a doctor or public health officer\r\nPassport\r\n\r\nIf you prefer to provide the documents by a method other than upload, you have the following options:\r\nMail the documents to:\r\nCoordinated Child Care VPK Online\r\n6500 102nd Avenue North\r\nPinellas Park, FL  33782\r\nDrop the documents off at any CCC office as listed on the CCC website. \r\nFax the documents to (727 547-2993. \r\n<strong>Please be advised that the VPK Application is not considered complete without the Proof of Residency and Date of Birth documents.</strong>',
			'type' => 'form',
			'name' => 'VPK Form Complete',
			'disabled' => 0,
			'created' => '2011-04-04 11:27:14',
			'modified' => '2011-04-04 11:27:17'
		),
		array(
			'id' => 4,
			'program_id' => 1,
			'cat_id' => 254,
			'to' => NULL,
			'from' => NULL,
			'subject' => 'VPK Enrollment Document Rejected',
			'body' => 'We have rejected your uploaded document.  Please see the comments below and provide appropriate documentation to meet the requirements for VPK. If you have questions about appropriate documentation, please call 727-547-5700 and ask for Coordinated Child Care�s VPK Online Team, but please be advised that we cannot proceed with processing your application until we receive the required documentation.',
			'type' => 'rejected',
			'name' => 'VPK Enrollment Doc Rejected',
			'disabled' => 0,
			'created' => '2011-04-07 11:39:28',
			'modified' => '2011-04-07 11:39:31'
		),
		array(
			'id' => 5,
			'program_id' => 1,
			'cat_id' => NULL,
			'to' => NULL,
			'from' => NULL,
			'subject' => 'Your enrollment cert ',
			'body' => 'Congratulations!  Your VPK Online Application has been approved.  Please click on the link below to print your Certificate of Eligibility.  This Certificate will be required to complete enrollment of your child with the provider of your choice. \r\n\r\n<a href=\"/programs/index/1/child\">VPK Program</a> ',
			'type' => 'final',
			'name' => 'VPK Final Email',
			'disabled' => 0,
			'created' => '2011-04-08 15:04:19',
			'modified' => '2011-04-08 15:04:22'
		),
		array(
			'id' => 6,
			'program_id' => 1,
			'cat_id' => 252,
			'to' => NULL,
			'from' => NULL,
			'subject' => 'Birth Proof Recieved',
			'body' => 'We have accepted your proof of child�s Date of Birth. Thank you for using the VPK Online system. You will receive further notification from us shortly. \r\n',
			'type' => 'docFiled',
			'name' => 'VPK Brth Proof',
			'disabled' => 0,
			'created' => '2011-04-27 14:09:21',
			'modified' => '2011-04-27 14:09:24'
		),
	);
}
