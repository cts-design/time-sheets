<?php
/* KioskSurveys Test cases generated on: 2011-08-23 13:17:09 : 1314119829*/
App::import('Controller', 'KioskSurveys');
App::import('Lib', 'AtlasTestCase');
class TestKioskSurveysController extends KioskSurveysController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KioskSurveysControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.kiosk_survey');

	function startTest() {
		$this->KioskSurveys =& new TestKioskSurveysController();
		$this->KioskSurveys->constructClasses();
	}

	function endTest() {
		unset($this->KioskSurveys);
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