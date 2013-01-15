<?php
class EventRegistration extends AppModel {

	public $name = 'EventRegistration';

	public $displayField = 'name';

	public $belongsTo = array(
		'Event' => array('counterCache' => true),
		'User'
	);

}
