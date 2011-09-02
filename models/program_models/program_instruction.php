<?php
class ProgramInstruction extends AppModel {
	var $name = 'ProgramInstruction';
	var $displayField = 'type';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Program' => array(
			'className' => 'Program',
			'foreignKey' => 'program_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	function getInstructions($programId, $type) {
		$instructions = $this->find('first', array(
			'conditions' => array(
				'ProgramInstruction.program_id' => $programId,
				'ProgramInstruction.type' => $type
			),
			'fields' => array(
				'ProgramInstruction.text'
			)
		));
		if($instructions) {
			return $instructions['ProgramInstruction']['text'];
		}
		else return false;		
	}
}
?>