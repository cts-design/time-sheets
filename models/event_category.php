<?php
class EventCategory extends AppModel {
	var $name = 'EventCategory';
	var $hasMany = 'Events';
	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must supply a name for your category'
			),
			'alpha' => array(
				'rule' => 'alpha',
				'message' => 'Category names can only contain letters'
			)
		)
	);
	
	function alpha($check) {
		$value = array_values($check);
		$value = $value[0];
		
		return preg_match('|^[a-zA-Z ]*$|', $value);
	}
}
?>