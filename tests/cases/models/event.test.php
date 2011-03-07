<?php
/* Event Test cases generated on: 2011-02-22 19:52:19 : 1298404339*/
App::import('Model', 'Event');
App::import('Lib', 'AtlasTestCase');
class EventTestCase extends AtlasTestCase {
	var $fixtures = array('app.event');

	function startTest() {
		$this->Event =& ClassRegistry::init('Event');
	}

	function endTest() {
		unset($this->Event);
		ClassRegistry::flush();
	}

}
?>