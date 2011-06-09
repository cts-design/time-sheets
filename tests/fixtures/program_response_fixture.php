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
		'uploaded_docs' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'dropping_off_docs' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
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
			'viewed_media' => 0,
			'form_esignature' => 'Daniel Smith',
			'complete' => 0,
			'needs_approval' => 0,
			'conformation_id' => '',
			'uploaded_docs' => 0,
			'dropping_off_docs' => 0,
			'created' => '2011-05-04 09:09:08',
			'modified' => '2011-05-12 11:12:24',
			'expires_on' => '2120-06-10 16:13:30'
		),
		array(
			'id' => 2,
			'program_id' => 1,
			'answers' => '',
			'user_id' => 11,
			'viewed_media' => 1,
			'form_esignature' => 'Daffy Duck',
			'complete' => 0,
			'needs_approval' => 0,
			'conformation_id' => '',
			'uploaded_docs' => 0,
			'dropping_off_docs' => 0,
			'created' => '2011-05-04 09:09:08',
			'modified' => '2011-05-12 11:12:24',
			'expires_on' => '2120-06-08 16:13:30'
		),
		array(
			'id' => 3,
			'program_id' => 1,
			'answers' => 'bla bla bla',
			'user_id' => 12,
			'viewed_media' => 1,
			'form_esignature' => 'Roger Rabbit',
			'complete' => 0,
			'needs_approval' => 0,
			'conformation_id' => 'dasf234f34',
			'uploaded_docs' => 0,
			'dropping_off_docs' => 0,
			'created' => '2011-05-04 09:09:08',
			'modified' => '2011-05-12 11:12:24',
			'expires_on' => '2120-06-08 16:13:30'
		),
		array(
			'id' => 4,
			'program_id' => 1,
			'answers' => 'bla bla bla',
			'user_id' => 13,
			'viewed_media' => 1,
			'form_esignature' => 'Bob Marley',
			'complete' => 0,
			'needs_approval' => 0,
			'conformation_id' => 'ddasf4354',
			'uploaded_docs' => 1,
			'dropping_off_docs' => 0,
			'created' => '2011-05-04 09:09:08',
			'modified' => '2011-05-12 11:12:24',
			'expires_on' => '2120-06-08 16:13:30'
		),
		array(
			'id' => 5,
			'program_id' => 1,
			'answers' => 'bla bla bla',
			'user_id' => 14,
			'viewed_media' => 1,
			'form_esignature' => 'George Bush',
			'complete' => 0,
			'needs_approval' => 0,
			'conformation_id' => '5dfdasf34',
			'uploaded_docs' => 0,
			'dropping_off_docs' => 1,
			'created' => '2011-05-04 09:09:08',
			'modified' => '2011-05-12 11:12:24',
			'expires_on' => '2120-06-08 16:13:30'
		),
		array(
			'id' => 6,
			'program_id' => 1,
			'answers' => 'bla bla bla',
			'user_id' => 15,
			'viewed_media' => 1,
			'form_esignature' => 'Slim Jim',
			'complete' => 1,
			'needs_approval' => 0,
			'conformation_id' => '23eff2343',
			'uploaded_docs' => 0,
			'dropping_off_docs' => 1,
			'created' => '2011-05-04 09:09:08',
			'modified' => '2011-05-12 11:12:24',
			'expires_on' => '2120-06-08 16:13:30'
		)	
	);
}
?>