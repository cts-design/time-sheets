<?php
/* CareerSeekersSurveys Test cases generated on: 2011-03-21 16:24:46 : 1300724686*/
App::import('Controller', 'CareerSeekersSurveys');
App::import('Lib', 'AtlasTestCase');
class TestCareerSeekersSurveysController extends CareerSeekersSurveysController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class CareerSeekersSurveysControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.career_seekers_survey');

	function startTest() {
		$this->CareerSeekersSurveys =& new TestCareerSeekersSurveysController();
		$this->CareerSeekersSurveys->constructClasses();
	}

	function endTest() {
		unset($this->CareerSeekersSurveys);
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