<?php
class ProgramStep extends AppModel {
    public $name = 'ProgramStep';
    public $actsAs = array('Tree');
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'Program' => array(
            'className' => 'Program',
            'foreignKey' => 'program_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public $hasOne = array(
        'ProgramInstruction' => array(
            'className' => 'ProgramInstruction',
            'foreignKey' => 'program_step_id'
		),
		'ProgramEmail' => array(
			'className' => 'ProgramEmail',
			'foreignKey' => 'program_step_id'
		) 
    );

    public $hasMany = array(
        'ProgramFormField' => array(
            'className' => 'ProgramFormField',
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
		),
		'ProgramDocument' => array(
			'className' => 'ProgramDocument',
			'foreignKey' => 'program_step_id'
		)
    );

	public function getSteps($program, $stepId) {
		$completedStepIds = Set::extract('/ProgramResponseActivity[status=complete]/program_step_id', $program['ProgramResponse']);
		$currentStep = Set::extract('/ProgramStep[id=' . $stepId . ']/.[:first]', $program);
		// @TODO add some logic to check if the current step is complete and if it and and redo is not allowed 
		$return = array(); 
		if(in_array($currentStep[0]['id'], $completedStepIds) && !$currentStep[0]['redoable']) {
			$return['error'] = 'Step has already been completed.';
		}
		$previousStep = Set::extract('/ProgramStep[rght=' . ($currentStep[0]['rght'] - 1) .']/.[:first]', $program);
		if(!empty($currentStep)) {
			$return['current'] = $currentStep;
		}
		while(isset($previousStep[0]) && in_array($previousStep[0]['id'], $completedStepIds)) {
			$previousStep = Set::extract('/ProgramStep[rght=' . ($previousStep[0]['rght'] - 1) .']/.[:first]', $program);
		}		
		if(!empty($previousStep)) {
			$return['previous'] = $previousStep;
			return $return;
		}
		$nextStep = Set::extract('/ProgramStep[lft=' . $currentStep[0]['rght'] .']/.[:first]', $program);
		while(isset($nextStep[0]) && in_array($nextStep['0']['id'], $completedStepIds)) {
			$nextStep = Set::extract('/ProgramStep[lft=' . $nextStep[0]['rght'] .']/.[:first]', $program);
		}
		if(!empty($nextStep)) {
			$return['next'] = $nextStep;
		}
		return $return;
	}
}
