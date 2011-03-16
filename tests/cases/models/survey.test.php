<?php
/* Survey Test cases generated on: 2011-03-11 16:02:44 : 1299859364*/
App::import('Model', 'Survey');
App::import('Lib', 'AtlasTestCase');
class SurveyTestCase extends AtlasTestCase {
	var $fixtures = array('app.survey');

	function startTest() {
		$this->Survey =& ClassRegistry::init('Survey');
	}

	function endTest() {
		unset($this->Survey);
		ClassRegistry::flush();
	}

}
?>