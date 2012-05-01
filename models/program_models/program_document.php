<?php
class ProgramDocument extends AppModel {
	var $name = 'ProgramDocument';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Program' => array(
			'className' => 'Program',
			'foreignKey' => 'program_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProgramStep' => array(
			'className' => 'ProgramStep',
			'foreignKey' => 'program_step_id'
		)
	);
}
