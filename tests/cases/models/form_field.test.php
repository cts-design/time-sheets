<?php
/* FormField Test cases generated on: 2011-03-17 14:48:26 : 1300373306*/
App::import('Model', 'FormField');
App::import('Lib', 'AtlasTestCase');
class FormFieldTestCase extends AtlasTestCase {
	var $fixtures = array('app.form_field');

	function startTest() {
		$this->FormField =& ClassRegistry::init('FormField');
	}

	function endTest() {
		unset($this->FormField);
		ClassRegistry::flush();
	}

}
?>