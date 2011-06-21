<?php
/* ReleaseNotes Test cases generated on: 2011-06-21 15:16:07 : 1308683767*/
App::import('Controller', 'ReleaseNotes');
App::import('Lib', 'AtlasTestCase');
class TestReleaseNotesController extends ReleaseNotesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ReleaseNotesControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.release_note');

	function startTest() {
		$this->ReleaseNotes =& new TestReleaseNotesController();
		$this->ReleaseNotes->constructClasses();
	}

	function endTest() {
		unset($this->ReleaseNotes);
		ClassRegistry::flush();
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