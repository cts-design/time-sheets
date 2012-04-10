<?php
class ProgramStep extends AppModel {
	var $name = 'ProgramStep';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ProgramModule' => array(
			'className' => 'ProgramModule',
			'foreignKey' => 'program_module_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'ProgramForm' => array(
			'className' => 'ProgramForm',
			'foreignKey' => 'program_step_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
?>