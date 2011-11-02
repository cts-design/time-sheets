<?php
class Setting extends AppModel {
	var $name = 'Setting';
	var $displayField = 'name';
	var $validate = array(
		'module' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'value' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		)
	);
}
?>