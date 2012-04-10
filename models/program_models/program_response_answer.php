<?php
class ProgramResponseAnswer extends AppModel {
	var $name = 'ProgramResponseAnswer';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ProgramResponse' => array(
			'className' => 'ProgramResponse',
			'foreignKey' => 'program_response_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProgramForm' => array(
			'className' => 'ProgramForm',
			'foreignKey' => 'program_form_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProgramModule' => array(
			'className' => 'ProgramModule',
			'foreignKey' => 'program_module_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>