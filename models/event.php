<?php
class Event extends AppModel {

	public $name = 'Event';

	public $displayField = 'name';

	public $belongsTo = array('EventCategory', 'Location');

	public $hasMany = array(
		'EventRegistration' => array(
			'className' => 'EventRegistration',
			'foreignKey' => 'event_id'
		)
	);
}
