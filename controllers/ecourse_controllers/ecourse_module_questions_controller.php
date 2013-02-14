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
			//$ecourse_modules = $this->EcourseModule
									//->find('all', array(
										//'conditions' => array(
											//'EcourseModule.ecourse_id' => $this->params['url']['ecourse_id']
										//)
									//));

			//if ($ecourse_modules) {
				//$data['success'] = true;

				//foreach ($ecourse_modules as $key => $module) {
					//$data['ecourse_modules'][] = $module['EcourseModule'];
				//}
			//} else {
				//$data['success'] = true;
				//$data['ecourse_modules'] = array();
			//}
			$data['success'] = true;
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}

		$title_for_layout = "Quiz for {$ecourse_module['EcourseModule']['name']}";
		$this->set(compact('title_for_layout', 'ecourse_module'));
	}

	public function admin_create() {
		$ecourse_module = json_decode($this->params['form']['ecourse_modules'], true);

		$this->EcourseModule->create();
		$this->data['EcourseModule'] = $ecourse_module;

		if ($this->EcourseModule->save($this->data)) {
			$data['success'] = true;
			$data['ecourse_modules'] = $ecourse_module;
			$data['ecourse_modules']['id'] = $this->EcourseModule->id;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}
}

?>
