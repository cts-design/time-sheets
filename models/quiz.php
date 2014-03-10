<?php

class Quiz extends AppModel {
	public $name = 'Quiz';

	public $belongsTo = array(
		'QuizCategory' => array(
			'className' => 'QuizCategory',
			'foreignKey' => 'quiz_category_id'
		)
	);

	public $hasMany = array(
		'QuizPage' => array(
			'className' => 'QuizPage',
			'foreignKey' => 'quiz_id'
		),
	);
}