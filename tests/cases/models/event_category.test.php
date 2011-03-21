<?php
/* EventCategory Test cases generated on: 2011-02-23 19:40:20 : 1298490020*/
App::import('Model', 'EventCategory');
App::import('Lib', 'AtlasTestCase');
class EventCategoryTestCase extends AtlasTestCase {
	var $fixtures = array('app.event_category');

	function startTest() {
		$this->EventCategory =& ClassRegistry::init('EventCategory');
	}

	function endTest() {
		unset($this->EventCategory);
		ClassRegistry::flush();
	}

}
?>