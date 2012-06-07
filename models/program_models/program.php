<?php
class Program extends AppModel {

	public $name = 'Program';
	public $displayField = 'name';
    public $actsAs = array('Containable', 'Disableable');
	public $hasMany = array(
						'ProgramStep',
						'ProgramResponse',
						'ProgramEmail',
						'ProgramDocument',
						'WatchedFilingCat',
						'ProgramInstruction');

	public function getProgramAndResponse($programId, $userId) {
		$this->contain(array(
			'ProgramStep' => array(
				'order' => array('ProgramStep.lft ASC'),
				'ProgramFormField'
			),
			'ProgramInstruction',
			'ProgramEmail' => array('conditions' => array('disabled' => 0)),
			'ProgramDocument',
			'ProgramResponse' => array(
				'conditions' => array('ProgramResponse.user_id' => $userId),
				'ProgramResponseActivity')));
         return $this->findById($programId);
	}
}

