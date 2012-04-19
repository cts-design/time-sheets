<?php
class ProgramResponseActivity extends AppModel {
	var $name = 'ProgramResponseActivity';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
    var $validate = array();
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
