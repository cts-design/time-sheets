<?php
/* SurveyQuestion Test cases generated on: 2011-03-15 14:18:47 : 1300198727*/
App::import('Model', 'SurveyQuestion');
App::import('Lib', 'AtlasTestCase');
class SurveyQuestionTestCase extends AtlasTestCase {
	var $fixtures = array('app.survey', 'app.survey_question');

	function startTest() {
		$this->SurveyQuestion =& ClassRegistry::init('SurveyQuestion');
	}

	function endTest() {
		unset($this->SurveyQuestion);
		ClassRegistry::flush();
	}

}
?>