<?php
/* Kiosks Test cases generated on: 2010-09-27 15:09:12 : 1285601112*/
App::import('Controller', 'Kiosks');
App::import('Lib', 'AtlasTestCase');
class TestKiosksController extends KiosksController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KiosksControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->Kiosks =& new TestKiosksController();
		$this->Kiosks->constructClasses();
	}

	function endTest() {
		unset($this->Kiosks);
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