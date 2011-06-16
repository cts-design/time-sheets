<?php
class ProgramResponseDoc extends AppModel {
	var $name = 'ProgramResponseDoc';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'ProgramResponse' => array(
			'className' => 'ProgramResponse',
			'foreignKey' => 'program_response_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>