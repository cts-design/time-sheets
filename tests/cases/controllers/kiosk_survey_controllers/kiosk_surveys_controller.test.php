<?php
/* KioskSurveys Test cases generated on: 2011-08-23 13:17:09 : 1314119829*/
App::import('Controller', 'KioskSurveys');
App::import('Lib', 'AtlasTestCase');
class TestKioskSurveysController extends KioskSurveysController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KioskSurveysControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->KioskSurveys =& new TestKioskSurveysController();
		$this->KioskSurveys->constructClasses();
		$this->KioskSurveys->params['controller'] = 'kiosk_surveys';
		$this->KioskSurveys->params['pass'] = array();
		$this->KioskSurveys->params['named'] = array();
		$this->testController = $this->KioskSurveys;
	}

	function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->KioskSurveys);
		ClassRegistry::flush();
	}

	 function testAdminCreateSuccess() {
		$this->KioskSurveys->Component->initialize($this->KioskSurveys);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/kiosk_surveys/create', array(
			'method' => 'post',
			'form_data' => array(
				'surveys' => '{"name":"New Survey"}'
			)
		));
		$this->assertTrue($result['data']['success']);
	}

	function testAdminCreateFailure() {
		$this->KioskSurveys->Component->initialize($this->KioskSurveys);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/kiosk_surveys/create', array(
			'method' => 'post',
			'form_data' => array(
				'surveys' => '{"name":""}'
			)
		));
		$this->assertFalse($result['data']['success']);
	}

	function testAdminRead() {
		$this->KioskSurveys->Component->initialize($this->KioskSurveys);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/kiosk_surveys/read', array('method' => 'get'));
		$this->assertEqual(count($result), 1);
		$this->assertEqual($result['data']['surveys'][0]['name'], 'Veteran Services');
	}

	function testAdminDestroy() {
		$this->KioskSurveys->Component->initialize($this->KioskSurveys);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/kiosk_surveys/destroy', array(
			'method' => 'get',
			'form_data' => array('surveys' => "1")
		));
		$this->assertTrue($result['data']['success']);
	}

	function testStart() {
		$this->KioskSurveys->Component->initialize($this->KioskSurveys);
		$result = $this->testAction('/kiosk/survey/1');

		$this->assertEqual($this->KioskSurveys->redirectUrl, '/kiosk/survey/1/question/1');
		$this->assertEqual($this->KioskSurveys->Session->read('surveyResponseId'), 3);
	}

	function testAttach() {
		$this->KioskSurveys->Component->initialize($this->KioskSurveys);
		$formData = array(
			'kiosk_id' => 2,
			'survey_id' => 1
		);

		$result = $this->testAction('/admin/kiosk_surveys/attach', array('form_data' => $formData));
		$this->assertTrue($result['data']['success']);
	}

	function testDetach() {
		$this->KioskSurveys->Component->initialize($this->KioskSurveys);
		$formData = array(
			'kiosk_id' => 1
		);

		$result = $this->testAction('/admin/kiosk_surveys/detach', array('form_data' => $formData));
		$this->assertTrue($result['data']['success']);
		$this->assertFalse(
			$this->KioskSurveys
				 ->KioskSurvey
				 ->KiosksKioskSurvey
				 ->findByKioskId(1));
	}

	// function testAdminReporting() {
	//     $this->KioskSurveys->Component->initialize($this->KioskSurveys);
	//     $result = $this->testAction('/admin/kiosk_surveys/report', array(
	//         'method' => 'get',
	//         'data' => array('survey_id' => 1)
	//     ));
	//     Configure::write('debug', 2);
	//     FireCake::log($result);
	// }
}
?>
