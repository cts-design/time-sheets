<?php
/* KioskSurveyQuestion Test cases generated on: 2011-08-24 09:12:08 : 1314191528*/
App::import('Model', 'KioskSurveyQuestion');
App::import('Lib', 'AtlasTestCase');
class KioskSurveyQuestionTestCase extends AtlasTestCase {
	var $fixtures = array('app.kiosk_survey_question');

	function startTest() {
		$this->KioskSurveyQuestion =& ClassRegistry::init('KioskSurveyQuestion');
	}

	function endTest() {
		unset($this->KioskSurveyQuestion);
		ClassRegistry::flush();
	}

}
?>