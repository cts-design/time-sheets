<?php

class Form extends AppModel
{
	var $useTable = "form";
	var $name = 'Form';


	var $hasMany = array(
		'Page' => array(
			'className' => 'Page',
			'foreignKey' => 'form_id',
			'dependent' => TRUE,
			'order' => 'Page.order ASC'
		)
	);
}