<?php
/* HotJobs Test cases generated on: 2011-02-28 13:49:09 : 1298900949*/
App::import('Controller', 'HotJobs');
App::import('Lib', 'AtlasTestCase');
class TestHotJobsController extends HotJobsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class HotJobsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.hot_job');

	function startTest() {
		$this->HotJobs =& new TestHotJobsController();
		$this->HotJobs->constructClasses();
	}

	function endTest() {
		unset($this->HotJobs);
		ClassRegistry::flush();
	}

	function testIndex() {

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