<?php
/* Form Test cases generated on: 2011-03-17 14:45:45 : 1300373145*/
App::import('Model', 'Form');
App::import('Lib', 'AtlasTestCase');
class FormTestCase extends AtlasTestCase {
	var $fixtures = array('app.form');

	function startTest() {
		$this->Form =& ClassRegistry::init('Form');
	}

	function endTest() {
		unset($this->Form);
		ClassRegistry::flush();
	}

}
?>