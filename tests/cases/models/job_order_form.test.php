<?php
/* JobOrderForm Test cases generated on: 2012-01-25 11:19:54 : 1327508394*/
App::import('Model', 'JobOrderForm');
App::import('Lib', 'AtlasTestCase');
class JobOrderFormTestCase extends AtlasTestCase {
	var $fixtures = array('app.job_order_form');

	function startTest() {
		$this->JobOrderForm =& ClassRegistry::init('JobOrderForm');
	}

	function endTest() {
		unset($this->JobOrderForm);
		ClassRegistry::flush();
	}

}
?>