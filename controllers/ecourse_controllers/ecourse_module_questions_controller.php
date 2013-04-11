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
		if($this->Auth->user()) {
		    if($this->Acl->check(array(
				'model' => 'User',
				'foreign_key' => $this->Auth->user('id')), 'Ecourses/admin_index', '*')){
					$this->Auth->allow('admin_index', 'admin_update', 'admin_create', 'admin_destroy');
			}
		}
	}

	public function admin_index($ecourse_module_id = null) {
		if (isset($this->params['url']['ecourse_module_id'])) {
			$ecourse_module_id = $this->params['url']['ecourse_module_id'];
		}

		if (!$ecourse_module_id) {
			$this->Session->setFlash(__('Invalid Ecourse Module id', true), 'flash_failure');
			$this->redirect(array('controller' => 'ecourse_modules', 'admin' => true));
		}

		$ecourse_module = $this->EcourseModuleQuestion
								->EcourseModule
								->findById($ecourse_module_id);

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
			$this->Transaction->createUserTransaction(
				'Ecourses',
				null,
				null,
				'Added module question id: ' . $this->EcourseModuleQuestion->id .
				' to ecourse module id: ' . $ecourse_module_question['ecourse_module_id']
			);
			$data['success'] = true;
			$data['ecourse_module_questions'] = $ecourse_module_question;
			$data['ecourse_module_questions']['id'] = $this->EcourseModuleQuestion->id;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}

	public function admin_update() {
		$ecourse_module_question = json_decode($this->params['form']['ecourse_module_questions'], true);

		$this->data['EcourseModuleQuestionAnswer'] = $ecourse_module_question['answers'];
		unset($ecourse_module_question['answers']);
		$this->data['EcourseModuleQuestion'] = $ecourse_module_question;

		// Get a list of answer id's so we can check if any have been deleted
		$existingAnswers = $this->EcourseModuleQuestion->EcourseModuleQuestionAnswer->find('list', array(
			'conditions' => array(
				'EcourseModuleQuestionAnswer.ecourse_module_question_id' => $ecourse_module_question['id']
			),
			'fields' => array('EcourseModuleQuestionAnswer.id')
		));
		$answersData = Set::extract('/EcourseModuleQuestionAnswer/id', $this->data);
		$removedAnswerIds = array_diff($existingAnswers, $answersData);

		foreach ($removedAnswerIds as $id) {
			$this->EcourseModuleQuestion->EcourseModuleQuestionAnswer->delete($id);
		}

		if ($this->EcourseModuleQuestion->saveAll($this->data)) {
			$this->Transaction->createUserTransaction(
				'Ecourses',
				null,
				null,
				'Updated module question id: ' . $ecourse_module_question['id'] .
				' for ecourse module id: ' . $this->params['form']['ecourse_module_id']
			);
			$data['success'] = true;
			$data['ecourse_module_questions'] = $ecourse_module_question;
			$data['ecourse_module_questions']['id'] = $this->EcourseModuleQuestion->id;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}

	public function admin_destroy() {
		$ecourse_module_question = json_decode($this->params['form']['ecourse_module_questions'], true);

		if ($this->EcourseModuleQuestion->delete($ecourse_module_question['id'])) {
			$this->Transaction->createUserTransaction(
				'Ecourses',
				null,
				null,
				'Deleted module question id: ' . $ecourse_module_question['id'] .
				' for ecourse module id: ' . $this->params['form']['ecourse_module_id']
			);
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}
}
