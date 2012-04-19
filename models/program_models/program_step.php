<?php
class ProgramStep extends AppModel {
    var $name = 'ProgramStep';
    var $actsAs = array('Tree');
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

    var $hasOne = array(
        'ProgramInstruction' => array(
            'className' => 'ProgramInstruction',
            'foreignKey' => 'program_step_id'
        )
    );

    var $hasMany = array(
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
        )
    );
}
