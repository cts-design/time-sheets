<?php
/* ProgramResponseDoc Fixture generated on: 2011-06-13 09:41:45 : 1307972505 */
class ProgramResponseDocFixture extends CakeTestFixture {
	var $name = 'ProgramResponseDoc';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'cat_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'program_response_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'doc_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'paper_form' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'cert' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'cat_id' => array('column' => 'cat_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'cat_id' => 254,
			'program_response_id' => 1,
			'doc_id' => 8,
			'paper_form' => 0,
			'cert' => 0,
			'created' => '2011-05-06 10:14:24',
			'modified' => '2011-05-06 10:14:24'
		),
		array(
			'id' => 2,
			'cat_id' => 252,
			'program_response_id' => 2,
			'doc_id' => 9,
			'paper_form' => 0,
			'cert' => 0,
			'created' => '2011-05-06 10:15:29',
			'modified' => '2011-05-06 10:15:29'
		),
		array(
			'id' => 3,
			'cat_id' => 253,
			'program_response_id' => 1,
			'doc_id' => 10,
			'paper_form' => 0,
			'cert' => 0,
			'created' => '2011-05-06 10:15:47',
			'modified' => '2011-05-06 10:15:47'
		),
		array(
			'id' => 4,
			'cat_id' => 255,
			'program_response_id' => 1,
			'doc_id' => 11,
			'paper_form' => 1,
			'cert' => 0,
			'created' => '2011-05-09 11:24:44',
			'modified' => '2011-05-09 11:58:37'
		),
		array(
			'id' => 5,
			'cat_id' => 255,
			'program_response_id' => 2,
			'doc_id' => 14,
			'paper_form' => 1,
			'cert' => 0,
			'created' => '2011-05-16 10:04:10',
			'modified' => '2011-05-16 15:23:09'
		),
		array(
			'id' => 6,
			'cat_id' => 256,
			'program_response_id' => 6,
			'doc_id' => 112,
			'paper_form' => 1,
			'cert' => 1,
			'created' => '2011-05-16 10:28:57',
			'modified' => '2011-05-16 14:24:04'
		),
		array(
			'id' => 7,
			'cat_id' => 256,
			'program_response_id' => 4,
			'doc_id' => 19,
			'paper_form' => 1,
			'cert' => 1,
			'created' => '2011-06-01 09:34:10',
			'modified' => '2011-06-01 09:34:10'
		),
		array(
			'id' => 8,
			'cat_id' => 252,
			'program_response_id' => 8,
			'doc_id' => 9,
			'paper_form' => 0,
			'cert' => 0,
			'created' => '2011-05-06 10:15:29',
			'modified' => '2011-05-06 10:15:29'
		),
		array(
			'id' => 9,
			'cat_id' => 253,
			'program_response_id' => 8,
			'doc_id' => 10,
			'paper_form' => 0,
			'cert' => 0,
			'created' => '2011-05-06 10:15:47',
			'modified' => '2011-05-06 10:15:47'
		),
		array(
			'id' => 10,
			'cat_id' => 255,
			'program_response_id' => 8,
			'doc_id' => 11,
			'paper_form' => 1,
			'cert' => 0,
			'created' => '2011-05-09 11:24:44',
			'modified' => '2011-05-09 11:58:37'
		),
		array(
			'id' => 11,
			'cat_id' => 256,
			'program_response_id' => 8,
			'doc_id' => 14,
			'paper_form' => 1,
			'cert' => 0,
			'created' => '2011-05-16 10:04:10',
			'modified' => '2011-05-16 15:23:09'
		),		
	);
}
