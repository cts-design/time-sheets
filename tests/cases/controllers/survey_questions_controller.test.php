<?php
/* SurveyQuestions Test cases generated on: 2011-03-16 13:37:24 : 1300282644*/
App::import('Controller', 'SurveyQuestions');
App::import('Lib', 'AtlasTestCase');
class TestSurveyQuestionsController extends SurveyQuestionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SurveyQuestionsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->SurveyQuestions =& new TestSurveyQuestionsController();
		$this->SurveyQuestions->constructClasses();
		$this->RequestHandler =& $this->SurveyQuestions->RequestHandler;
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
	}

	function endTest() {
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->RequestHandler);
		unset($this->SurveyQuestions);
		ClassRegistry::flush();
	}
	
	function testAdminCreate() {
		$this->SurveyQuestions->params = Router::parse('/admin/survey_questions/create');
		$this->SurveyQuestions->params['form'] = json_encode(array('survey_id' => 2, 'question' => 'new survey question', 'type' => 'text', 'required' => 0));
		
		$this->SurveyQuestions->Component->initialize($this->SurveyQuestions);
		$this->SurveyQuestions->beforeFilter();
		$this->SurveyQuestions->Component->startup($this->SurveyQuestions);
		
		$result = json_decode($this->SurveyQuestions->admin_create(), true);

		$this->assertTrue($this->RequestHandler->isAjax());
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);
		
		$result = $this->SurveyQuestions->SurveyQuestion->read(null, 7);
		FireCake::log($result);
		$expected = array(
			'SurveyQuestion' => array(
				'id' => 7,
				'survey_id' => 2,
				'question' => 'new survey question',
				'type' => 'text'
			)
		);
		
		$this->assertEqual($result['SurveyQuestion']['id'], $expected['SurveyQuestion']['id']);
		$this->assertEqual($result['SurveyQuestion']['survey_id'], $expected['SurveyQuestion']['survey_id']);
		$this->assertEqual($result['SurveyQuestion']['question'], $expected['SurveyQuestion']['question']);
		$this->assertEqual($result['SurveyQuestion']['type'], $expected['SurveyQuestion']['type']);
	}

	function testAdminReadWithNoParams() {
		$result = json_decode($this->testAction('/admin/survey_questions/read'), true);
		
		$this->assertTrue($this->RequestHandler->isAjax());
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);
		//FireCake::log($result);
	}
	
	function testAdminReadWithParams() {
		$this->SurveyQuestions->params = Router::parse('/admin/survey_questions/read');
		$this->SurveyQuestions->params['url']['survey_id'] = 1;
		
		$this->SurveyQuestions->Component->initialize($this->SurveyQuestions);
		$this->SurveyQuestions->beforeFilter();
		$this->SurveyQuestions->Component->startup($this->SurveyQuestions);
		
		$result = json_decode($this->SurveyQuestions->admin_read(), true);

		$this->assertTrue($this->RequestHandler->isAjax());
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);
		//FireCake::log($result);
	}

}
?>