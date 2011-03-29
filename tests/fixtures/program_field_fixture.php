<?php
/* ProgramField Fixture generated on: 2011-03-29 17:40:59 : 1301420459 */
class ProgramFieldFixture extends CakeTestFixture {
	var $name = 'ProgramField';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'program_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'label' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'attributes' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'options' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'validation' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'program_id' => array('column' => 'program_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'program_id' => 1,
			'label' => 'Preferred program schedule',
			'type' => 'select',
			'name' => 'preferred_program_schedule',
			'attributes' => '{\"class\":\"testClass\"}',
			'options' => '{\"\" : \"Select\", \"Winter\\/Spring only\":\"Winter\\/Spring only (540 Hours)\",\"School Year\":\"School Year (540 Hours)\", \"Summer Program\":\"Summer Program (300 Hours)\", \"Fall\\/Winter only\":\"Fall\\/Winter only (540 Hours)\"}',
			'validation' => '{\"rule\":\"notEmpty\"}',
			'created' => '2011-03-23 16:42:20',
			'modified' => '2011-03-24 16:42:24'
		),
		array(
			'id' => 2,
			'program_id' => 1,
			'label' => 'Preferred program setting',
			'type' => 'select',
			'name' => 'preferred_program_setting',
			'attributes' => NULL,
			'options' => '{\"\":\"Select\", \"Private provider\":\"Private provider (child care, private school, faith-based)\", \"Public school\":\"Public school\"}',
			'validation' => '{\"rule\":\"notEmpty\"}',
			'created' => '2011-03-24 15:01:17',
			'modified' => '2011-03-24 15:01:22'
		),
	);
}
?>