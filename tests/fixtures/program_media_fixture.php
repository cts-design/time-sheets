<?php
/* ProgramMedia Fixture generated on: 2012-04-10 10:42:35 : 1334068955 */
class ProgramMediaFixture extends CakeTestFixture {
	var $name = 'ProgramMedia';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1
		),
	);
}
?>