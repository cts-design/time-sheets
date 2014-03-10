<?php

class QuizCategory extends AppModel {
	var $name = 'QuizCategory';

	var $has_many = array(
		'Quiz' => array(
			'className' => 'Quiz',
			'foreignKey' => 'quiz_category_id'
		)
	);
}