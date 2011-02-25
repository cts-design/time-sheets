<?php
/* SelfSignLogArchives Test cases generated on: 2010-10-29 12:10:24 : 1288355004*/
App::import('Controller', 'SelfSignLogArchives');
App::import('Lib', 'AtlasTestCase');
class TestSelfSignLogArchivesController extends SelfSignLogArchivesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SelfSignLogArchivesControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->SelfSignLogArchives =& new TestSelfSignLogArchivesController();
		$this->SelfSignLogArchives->constructClasses();
	}

	function endTest() {
		unset($this->SelfSignLogArchives);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>