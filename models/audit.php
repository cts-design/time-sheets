<?php

class Audit extends AppModel {
    public $name = 'Audit';
    public $displayField = 'name';

    public $actsAs = array('Disableable');

    public $hasAndBelongsToMany = array('User');

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
                'message' => 'A start date is required',
                'last' => true
            ),
            'validDate' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Start date must be a valid date (yyyy-mm-dd)'
            )
        ),
        'end_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'An end date is required',
                'last' => true
            ),
            'validDate' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'End date must be a valid date (yyyy-mm-dd)'
            )
        )
    );
}
