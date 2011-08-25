<?php
/* KioskSurveyQuestions Test cases generated on: 2011-08-24 09:12:25 : 1314191545*/
App::import('Controller', 'KioskSurveyQuestions');
App::import('Lib', 'AtlasTestCase');
class TestKioskSurveyQuestionsController extends KioskSurveyQuestionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KioskSurveyQuestionsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.kiosk_survey_question');

	function startTest() {
		$this->KioskSurveyQuestions =& new TestKioskSurveyQuestionsController();
		$this->KioskSurveyQuestions->constructClasses();
	}

	function endTest() {
		unset($this->KioskSurveyQuestions);
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