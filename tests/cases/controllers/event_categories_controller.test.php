<?php
/* EventCategories Test cases generated on: 2011-02-23 19:40:31 : 1298490031*/
App::import('Controller', 'EventCategories');
App::import('Lib', 'AtlasTestCase');
class TestEventCategoriesController extends EventCategoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class EventCategoriesControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.event_category');

	function startTest() {
		$this->EventCategories =& new TestEventCategoriesController();
		$this->EventCategories->constructClasses();
	}

	function endTest() {
		unset($this->EventCategories);
		ClassRegistry::flush();
	}

}
?>