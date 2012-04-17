<?php
class ProgramForm extends AppModel {
	var $name = 'ProgramForm';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ProgramStep' => array(
			'className' => 'ProgramStep',
			'foreignKey' => 'program_step_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'ProgramResponseActivity' => array(
			'className' => 'ProgramResponseActivity',
			'foreignKey' => 'program_form_id',
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
