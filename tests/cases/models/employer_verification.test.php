<?php
/* EmployerVerification Test cases generated on: 2012-02-01 09:18:38 : 1328105918*/
App::import('Model', 'EmployerVerification');
App::import('Lib', 'AtlasTestCase');
class EmployerVerificationTestCase extends AtlasTestCase {
	var $fixtures = array('app.employer_verification');

	function startTest() {
		$this->EmployerVerification =& ClassRegistry::init('EmployerVerification');
	}

	function endTest() {
		unset($this->EmployerVerification);
		ClassRegistry::flush();
	}

}
?>