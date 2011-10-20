<?php
/* BarCodeDefinition Test cases generated on: 2011-10-04 11:42:00 : 1317742920*/
App::import('Model', 'BarCodeDefinition');
App::import('Lib', 'AtlasTestCase');
class BarCodeDefinitionTestCase extends AtlasTestCase {
	var $fixtures = array('app.bar_code_definition');

	function startTest() {
		$this->BarCodeDefinition =& ClassRegistry::init('BarCodeDefinition');
	}

	function endTest() {
		unset($this->BarCodeDefinition);
		ClassRegistry::flush();
	}

}
?>