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
}
