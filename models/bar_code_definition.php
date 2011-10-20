<?php
class BarCodeDefinition extends AppModel {
	var $name = 'BarCodeDefinition';
	var $displayField = 'name';
	
	var $belongsTo = array(
		'Cat1' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_1'
		),
		'Cat2' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_2'
		),
		'Cat3' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_3'
		),
		'DocumentQueueCategory' => array(
		    'className' => 'DocumentQueueCategory',
		    'foreignKey' => 'document_queue_category_id'			
		)	
	);
}
?>