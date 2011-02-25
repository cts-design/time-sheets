<?php
/* PressRelease Test cases generated on: 2011-02-09 15:20:21 : 1297264821*/
App::import('Model', 'PressRelease');

class PressReleaseTestCase extends CakeTestCase {
	var $fixtures = array('app.press_release');

	function startTest() {
		$this->PressRelease =& ClassRegistry::init('PressRelease');
        $this->PressRelease->create();
    }

	function endTest() {
		unset($this->PressRelease);
		ClassRegistry::flush();
	}

        function testValidation() {
            $invalidRecordNoTitle = array(
                'PressRelease' => array(
                    'title' => '',
                    'file' => 'http://atlas.dev/files/public/press_releases/test.pdf'
                )
            );

            $invalidRecordIllegalTitle = array(
                'PressRelease' => array(
                    'title' => 'Invalid.Title!',
                    'file' => 'validfile.pdf'
                )
            );

            $invalidRecordNoFile = array(
                'PressRelease' => array(
                    'title' => 'Valid title',
                    'file' => ''
                )
            );

            $this->assertFalse($this->PressRelease->save($invalidRecordNoTitle));
            $this->assertFalse($this->PressRelease->save($invalidRecordIllegalTitle));
            $this->assertFalse($this->PressRelease->save($invalidRecordNoFile));
        }

        function testSavingValidRecord() {
            $timestamp = date('Y-m-d H:i:s');
            $validRecord = array(
                'PressRelease' => array(
                    'title' => 'Valid Title',
                    'file' => 'Valid file',
                    'created' => $timestamp,
                    'modified' => $timestamp
                )
            );

            $result = $this->PressRelease->save($validRecord);
            $expected = $validRecord;

            $this->assertEqual($result, $expected);
        }

}
?>