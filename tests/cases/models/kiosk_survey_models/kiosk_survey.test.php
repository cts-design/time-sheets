<?php
/* KioskSurvey Test cases generated on: 2011-08-23 13:16:43 : 1314119803*/
App::import('Model', 'KioskSurvey');
App::import('Lib', 'AtlasTestCase');
class KioskSurveyTestCase extends AtlasTestCase {
	var $fixtures = array('app.kiosk_survey');

	function startTest() {
		$this->KioskSurvey =& ClassRegistry::init('KioskSurvey');
	}

	function endTest() {
		unset($this->KioskSurvey);
		ClassRegistry::flush();
	}

}
?>