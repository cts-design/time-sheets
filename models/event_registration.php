<?php
class EventRegistration extends AppModel {

	public $name = 'EventRegistration';

	public $displayField = 'name';

	public $belongsTo = array(
		'Event' => array('counterCache' => true),
		'User'
	);

	public function countAttended($id) {
		return $this->find('count', array('conditions' => array('EventRegistration.event_id' => $id, 'EventRegistration.present' => 1)));
	}

}
