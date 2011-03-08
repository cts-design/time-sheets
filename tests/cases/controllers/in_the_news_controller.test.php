<?php
/* InTheNews Test cases generated on: 2011-03-07 19:52:23 : 1299527543*/
App::import('Controller', 'InTheNews');
App::import('Lib', 'AtlasTestCase');
class TestInTheNewsController extends InTheNewsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class InTheNewsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.in_the_news');

	function startTest() {
		$this->InTheNews =& new TestInTheNewsController();
		$this->InTheNews->constructClasses();
	}

	function endTest() {
		unset($this->InTheNews);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

	function testAdminIndex() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>