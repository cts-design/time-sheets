<?php 
class Event extends AppModel {

	public $name = 'Event';
	
	public $displayField = 'name';
	
	public $belongsTo = 'EventCategory';

	public $hasMany = 'EventRegistration';

	

}
