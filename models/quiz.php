<?php

class Quiz extends AppModel {
	public $name = 'Quiz';
	public $hasMany = array(
		'Pages' => array(
			'className' => 'Pages',
			'foreignKey' => 'quiz_id'
		),
	);
}