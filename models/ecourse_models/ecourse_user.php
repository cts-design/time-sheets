<?php
class EcourseUser extends AppModel {
	var $name = 'EcourseUser';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Ecourse' => array(
			'className' => 'Ecourse',
			'foreignKey' => 'ecourse_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
