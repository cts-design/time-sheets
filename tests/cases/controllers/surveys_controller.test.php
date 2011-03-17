<?php
/* Surveys Test cases generated on: 2011-03-11 16:02:54 : 1299859374*/
App::import('Controller', 'Surveys');
App::import('Lib', 'AtlasTestCase');
class TestSurveysController extends SurveysController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SurveysControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.survey');

	function startTest() {
		$this->Surveys =& new TestSurveysController();
		$this->Surveys->constructClasses();
	}

	function endTest() {
		unset($this->Surveys);
		ClassRegistry::flush();
	}

}
?>