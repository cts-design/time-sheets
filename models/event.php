<?php
class Event extends AppModel {
	var $name = 'Event';
	var $displayField = 'title';
	var $belongsTo = 'EventCategory';
	var $actsAs = array(
		'Translatable' => array(
			'title', 'description'
		),
		'AtlasTranslate' => array(
			'title', 'description'
		)
	);
}
?>