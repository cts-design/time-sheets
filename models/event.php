<?php 
class Event extends AppModel {

	public $name = 'Event';
	
	public $displayField = 'title';
	
	public $belongsTo = 'EventCategory';

	public $actsAs = array(
		'Translatable' => array(
			'title', 'description'
		),
		'AtlasTranslate' => array(
			'title', 'description'
		)
	);
}
