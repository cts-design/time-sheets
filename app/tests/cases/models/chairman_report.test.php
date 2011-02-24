<?php
/* ChairmanReport Test cases generated on: 2011-02-09 18:13:19 : 1297275199*/
App::import('Model', 'ChairmanReport');

class ChairmanReportTestCase extends CakeTestCase {
	var $fixtures = array('app.chairman_report');

	function startTest() {
		$this->ChairmanReport =& ClassRegistry::init('ChairmanReport');
        $this->ChairmanReport->create();
	}

	function endTest() {
		unset($this->ChairmanReport);
		ClassRegistry::flush();
	}

        function testValidation() {
            $invalidRecordNoTitle = array(
                'ChairmanReport' => array(
                    'title' => '',
                    'file' => 'http://atlas.dev/files/public/press_releases/test.pdf'
                )
            );

            $invalidRecordIllegalTitle = array(
                'ChairmanReport' => array(
                    'title' => 'Invalid.Title!',
                    'file' => 'validfile.pdf'
                )
            );

            $invalidRecordNoFile = array(
                'ChairmanReport' => array(
                    'title' => 'Valid title',
                    'file' => ''
                )
            );

            $this->assertFalse($this->ChairmanReport->save($invalidRecordNoTitle));
            $this->assertFalse($this->ChairmanReport->save($invalidRecordIllegalTitle));
            $this->assertFalse($this->ChairmanReport->save($invalidRecordNoFile));
        }

        function testSavingValidRecord() {
            $timestamp = date('Y-m-d H:i:s');
            $validRecord = array(
                'ChairmanReport' => array(
                    'title' => 'Valid Title',
                    'file' => 'Valid file',
                    'created' => $timestamp,
                    'modified' => $timestamp
                )
            );

            $result = $this->ChairmanReport->save($validRecord);
            $expected = $validRecord;

            $this->assertEqual($result, $expected);
        }

}
?>