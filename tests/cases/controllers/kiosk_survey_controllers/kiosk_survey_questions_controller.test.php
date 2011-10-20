<?php
/* KioskSurveyQuestions Test cases generated on: 2011-08-24 09:12:25 : 1314191545*/
App::import('Controller', 'KioskSurveyQuestions');
App::import('Lib', 'AtlasTestCase');
class TestKioskSurveyQuestionsController extends KioskSurveyQuestionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KioskSurveyQuestionsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->KioskSurveyQuestions =& new TestKioskSurveyQuestionsController();
		$this->KioskSurveyQuestions->constructClasses();
		$this->KioskSurveyQuestions->params['controller'] = 'kiosk_survey_questions';
		$this->KioskSurveyQuestions->params['pass'] = array();
		$this->KioskSurveyQuestions->params['named'] = array();
		$this->testController = $this->KioskSurveyQuestions;
		$this->KioskSurveyQuestions->Session->write('surveyResponseId', 3);
	}

	function endTest() {
		unset($this->KioskSurveyQuestions);
		ClassRegistry::flush();
	}

	// test the five questions from the fixture
	function testQuestion() {
		$this->assertEqual($this->KioskSurveyQuestions->Session->read('surveyResponseId'), 3);
		$this->KioskSurveyQuestions->data = array();

		$result = $this->testAction('/kiosk/survey/1/question/1');
		$this->assertEqual($result['question']['order'], 1);
		$this->assertEqual(count($result['survey'][0]['KioskSurveyQuestion']), 5);

		$data = array(
			'KioskSurveyQuestions' => array(
				'question_id' => 1,
				'question_number' => 1,
				'survey_id' => 1,
				'answer' => 'Blue'
			)
		);

		$result = $this->testAction('/kiosk/survey/1/question/1', array('method' => 'post', 'data' => $data));
		$this->assertEqual($this->KioskSurveyQuestions->redirectUrl, '/kiosk/survey/1/question/2');
		$data = array();

		$result = $this->testAction('/kiosk/survey/1/question/2');
		$this->assertEqual($result['question']['order'], 2);
		$this->assertEqual(count($result['survey'][0]['KioskSurveyQuestion']), 5);

		$data = array(
			'KioskSurveyQuestions' => array(
				'question_id' => 2,
				'question_number' => 2,
				'survey_id' => 1,
				'answer' => 'Blue'
			)
		);

		$result = $this->testAction('/kiosk/survey/1/question/2', array('method' => 'post', 'data' => $data));
		$this->assertEqual($this->KioskSurveyQuestions->redirectUrl, '/kiosk/survey/1/question/3');
		$data = array();

		$result = $this->testAction('/kiosk/survey/1/question/3');
		$this->assertEqual($result['question']['order'], 3);
		$this->assertEqual(count($result['survey'][0]['KioskSurveyQuestion']), 5);

		$data = array(
			'KioskSurveyQuestions' => array(
				'question_id' => 3,
				'question_number' => 3,
				'survey_id' => 1,
				'answer' => 'Blue'
			)
		);

		$result = $this->testAction('/kiosk/survey/1/question/3', array('method' => 'post', 'data' => $data));
		$this->assertEqual($this->KioskSurveyQuestions->redirectUrl, '/kiosk/survey/1/question/4');
		$data = array();

		$result = $this->testAction('/kiosk/survey/1/question/4');
		$this->assertEqual($result['question']['order'], 4);
		$this->assertEqual(count($result['survey'][0]['KioskSurveyQuestion']), 5);

		$data = array(
			'KioskSurveyQuestions' => array(
				'question_id' => 4,
				'question_number' => 4,
				'survey_id' => 1,
				'answer' => 'Blue'
			)
		);

		$result = $this->testAction('/kiosk/survey/1/question/4', array('method' => 'post', 'data' => $data));
		$this->assertEqual($this->KioskSurveyQuestions->redirectUrl, '/kiosk/survey/1/question/5');
		$data = array();

		$result = $this->testAction('/kiosk/survey/1/question/5');
		$this->assertEqual($result['question']['order'], 5);
		$this->assertEqual(count($result['survey'][0]['KioskSurveyQuestion']), 5);

		$result = $this->testAction('/kiosk/survey/1/question/6');
		$this->assertFlashMessage(
			&$this->KioskSurveyQuestions, 
			'Thank you for taking the time to complete the survey. Your input is very important to us.',
			'flash_success'
		);
		$this->assertEqual($this->KioskSurveyQuestions->redirectUrl, '/kiosk');
	}

	function testAdminCreate() {
		$this->KioskSurveyQuestions->Component->initialize($this->KioskSurveyQuestions);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/kiosk_survey_questions/create', array(
			'method' => 'post',
			'form_data' => array(
				'surveyQuestions' => '{"kiosk_survey_id":"1","question":"New Question","type":"yesno","options":"","order":"1"}'
			)
		));
		$this->assertTrue($result['data']['success']);
	}

	function testAdminRead() {
		$this->KioskSurveyQuestions->Component->initialize($this->KioskSurveyQuestions);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/kiosk_survey_questions/read', array(
			'method' => 'get',
			'data' => array(
				'kiosk_id' => 1
			)
		));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['total'], 5);
		$this->assertEqual(count($result['data']['surveyQuestions']), 5);
	}

	function testAdminUpdate() {
		$this->KioskSurveyQuestions->Component->initialize($this->KioskSurveyQuestions);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/kiosk_survey_questions/update', array(
			'method' => 'post',
			'form_data' => array(
				'surveyQuestions' => '{"id":"1","kiosk_survey_id":"1","question":"New Question","type":"yesno","options":"","order":"1"}'
			)
		));
		$this->assertTrue($result['data']['success']);
	}
	function testAdminDestroy() {
		$this->KioskSurveyQuestions->Component->initialize($this->KioskSurveyQuestions);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/kiosk_survey_questions/destroy', array(
			'method' => 'post',
			'form_data' => array('surveyQuestions' => "1")
		));
		$this->assertTrue($result['data']['success']);

		$check = $this->KioskSurveyQuestions->KioskSurveyQuestion->findAllByKioskSurveyId(1);
		$this->assertEqual(count($check), 4);
	}

}
?>
