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

	public function looseEqualTo($input) {
		$key   = array_pop(array_keys($input));
		$input = array_pop(array_values($input));
		$value = array_pop($this->validate[$key]['rule']);

		if (strtolower($input) === strtolower($value)) {
			return true;
		}

		return false;
	}
}
?>
