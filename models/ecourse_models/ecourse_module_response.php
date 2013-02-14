<?php
class EcourseModuleResponse extends AppModel {
	public $name = 'EcourseModuleResponse';
	public $actsAs = array('Containable');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'EcourseResponse' => array(
			'className' => 'EcourseResponse',
			'foreignKey' => 'ecourse_response_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EcourseModule' => array(
			'className' => 'EcourseModule',
			'foreignKey' => 'ecourse_module_id'
		)
	);

	public $hasMany = array(
		'EcourseModuleResponseTime' => array(
			'className' => 'EcourseModuleResponseTime',
			'foreignKey' => 'ecourse_module_response_id',
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
