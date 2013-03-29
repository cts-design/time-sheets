<?php
/* EcourseModuleResponseTime Test cases generated on: 2013-02-11 10:13:03 : 1360595583*/
App::import('Model', 'EcourseModuleResponseTime');
App::import('Lib', 'AtlasTestCase');
class EcourseModuleResponseTimeTestCase extends AtlasTestCase {
	var $fixtures = array('app.ecourse_module_response_time', 'app.ecourse_module_response');

	function startTest() {
		$this->EcourseModuleResponseTime =& ClassRegistry::init('EcourseModuleResponseTime');
	}

	function endTest() {
		unset($this->EcourseModuleResponseTime);
		ClassRegistry::flush();
	}

}
?>