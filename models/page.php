<?php

class Page extends AppModel
{
	var $useTable = 'page';
	var $name = 'Page';

	var $hasMany = array(
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'page_id',
			'order' => 'Question.order ASC'
		)
	);
}