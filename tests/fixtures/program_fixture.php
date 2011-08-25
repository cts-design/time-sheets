<?php
/* Program Fixture generated on: 2011-06-01 11:40:08 : 1306942808 */
class ProgramFixture extends CakeTestFixture {
	var $name = 'Program';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'media' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'atlas_registration_type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'media_expires' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
		'disabled' => array('type' => 'boolean', 'null' => false, 'default' => 0),
		'queue_category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'cert_type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'approval_required' => array('type' => 'boolean', 'null' => false, 'default' => 0),
		'form_esign_required' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'conformation_id_length' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2),
		'response_expires_in' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'auth_required' => array('type' => 'boolean', 'null' => false, 'default' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'expires' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'VPK',
			'type' => 'video_form_docs',
			'media' => 'vpk.flv',
			'atlas_registration_type' => '',
			'media_expires' => 0,
			'disabled' => 0,
			'queue_category_id' => 6,
			'cert_type' => 'coe',
			'approval_required' => 1,
			'form_esign_required' => 1,
			'conformation_id_length' => 9,
			'response_expires_in' => 30,
			'auth_required' => 1,
			'created' => '2011-03-23 00:00:00',
			'modified' => '2011-05-03 12:36:22',
			'expires' => '2011-04-23 00:00:00'
		),
		array(
			'id' => 2,
			'name' => 'PPK',
			'type' => 'video_form_docs',
			'media' => 'ppk.flv',
			'atlas_registration_type' => '',
			'media_expires' => 0,
			'disabled' => 1,
			'queue_category_id' => 6,
			'cert_type' => 'coe',
			'approval_required' => 1,
			'form_esign_required' => 1,
			'conformation_id_length' => 9,
			'response_expires_in' => 30,
			'auth_required' => 1,
			'created' => '2011-03-23 00:00:00',
			'modified' => '2011-05-03 12:36:22',
			'expires' => '2011-04-23 00:00:00'
		)	
	);
}
?>