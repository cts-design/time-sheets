<?php
class Event extends AppModel {
	var $name = 'Event';
	var $displayField = 'title';
	var $belongsTo = 'EventCategory';
}
?>