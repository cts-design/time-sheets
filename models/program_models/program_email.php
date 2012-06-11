<?php
class ProgramEmail extends AppModel {
	var $name = 'ProgramEmail';
	
	var $displayField = 'name';
	
	var $actsAs = array('Containable','Disableable');

	var $belongsTo = array(
		'Program' => array(
			'className' => 'Program',
			'foreignKey' => 'program_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProgramStep' => array(
			'className' => 'Program',
			'foreignKey' => 'program_step_id'
		)
	);
}
?>
