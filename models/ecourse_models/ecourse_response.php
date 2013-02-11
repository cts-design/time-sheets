<?php
class EcourseResponse extends AppModel {
	public $name = 'EcourseResponse';

	public $actsAs = array('Containable');

	public $belongsTo = array(
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

	public $hasMany = array(
		'EcourseModuleResponse' => array(
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
