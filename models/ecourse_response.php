<?php
class EcourseResponse extends AppModel {
	var $name = 'EcourseResponse';

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

	var $hasMany = array(
		'EcourseModuleResponses' => array(
			'className' => 'EcourseModuleResponse',
			'foreignKey' => 'ecourse_response_id',
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
