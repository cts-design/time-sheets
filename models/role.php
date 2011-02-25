<?php
class Role extends AppModel {
	var $name = 'Role';
	var $displayField = 'name';
	var $actsAs = array('Acl' => array('type' => 'requester'));
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'role_id',
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

    function parentNode() {
	return null;
    }

    function  afterFind($results, $primary = false) {
	parent::afterFind($results, $primary);
	foreach ($results as $key => $value) {
	    if(isset($value['Role']['created'])) {
		$results[$key]['Role']['created'] = $this->formatDateTimeAfterFind($value['Role']['created']);
	    }
	    if(isset($value['Role']['modified'])) {
		$results[$key]['Role']['modified'] = $this->formatDateTimeAfterFind($value['Role']['modified']);
	    }
	}
	return $results;
    }

}
