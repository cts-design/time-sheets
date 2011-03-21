<?php
/* Events Test cases generated on: 2011-02-22 19:52:31 : 1298404351*/
App::import('Controller', 'Events');
App::import('Lib', 'AtlasTestCase');
class TestEventsController extends EventsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class EventsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.event');

	function startTest() {
		$this->Events =& new TestEventsController();
		$this->Events->constructClasses();
	}

	function endTest() {
		unset($this->Events);
		ClassRegistry::flush();
	}

}
?>