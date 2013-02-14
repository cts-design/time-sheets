<?php

/**
 * Ecourse Module Questions Controller
 *
 * @package   AtlasV3
 * @author    Brandon Cordell
 * @copyright 2013 Complete Technology Solutions
 */
class EcourseModuleQuestionsController extends AppController {
	public $name = 'EcourseModuleQuestions';

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_index() {
		$ecourse_module = $this->EcourseModuleQuestion
								->EcourseModule
								->findById($this->params['url']['ecourse_module_id']);

		if ($this->RequestHandler->isAjax()) {
			$ecourse_module_questions = $this->EcourseModuleQuestion->find('all', array(
				'conditions' => array(
					'EcourseModuleQuestion.ecourse_module_id' => $this->params['url']['ecourse_module_id']
				)
			));

			if ($ecourse_module_questions) {
				$data['success'] = true;

				foreach ($ecourse_module_questions as $key => $question) {
					$data['ecourse_module_questions'][$key] = $question['EcourseModuleQuestion'];
					$data['ecourse_module_questions'][$key]['answers'] = $question['EcourseModuleQuestionAnswer'];
				}
			} else {
				$data['success'] = true;
				$data['ecourse_module_questions'] = array();
			}

			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}

		$title_for_layout = "Quiz for {$ecourse_module['EcourseModule']['name']}";
		$this->set(compact('title_for_layout', 'ecourse_module'));
	}

	public function admin_create() {
		$ecourse_module_question = json_decode($this->params['form']['ecourse_module_questions'], true);

		$this->EcourseModuleQuestion->create();
		$this->data['EcourseModuleQuestionAnswer'] = $ecourse_module_question['answers'];
		unset($ecourse_module_question['answers']);
		$this->data['EcourseModuleQuestion'] = $ecourse_module_question;

		if ($this->EcourseModuleQuestion->saveAll($this->data)) {
			$data['success'] = true;
			$data['ecourse_module_questions'] = $ecourse_module_question;
			$data['ecourse_module_questions']['id'] = $this->EcourseModuleQuestion->id;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}
}

?>
