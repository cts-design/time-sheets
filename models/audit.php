<?php

class Audit extends AppModel {
    public $name = 'Audit';
    public $displayField = 'name';

    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'An audit name is required'
            )
        ),
        'start_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'A start date is required'
            )
        ),
        'end_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'An end date is required'
            )
        )
    );
}
