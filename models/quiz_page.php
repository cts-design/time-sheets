<?php

class QuizPage extends AppModel {
	var $name = 'QuizPage';

	var $belongsTo = array(
		'Quiz' => array(
			'className' => 'Quiz',
			'foreignKey' => 'quiz_id'
		)
	);
}