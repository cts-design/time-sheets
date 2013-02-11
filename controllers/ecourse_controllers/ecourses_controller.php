<?php

/**
 * Ecourses Controller
 *
 * @package   AtlasV3
 * @author    Brandon Cordell
 * @copyright 2013 Complete Technology Solutions
 */
class EcoursesController extends AppController {
	public $name = 'Ecourses';

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_index() {
		if ($this->RequestHandler->isAjax()) {
			$ecourseType = $this->params['url']['ecourse_type'];

			$ecourses = $this->Ecourse->find('all', array(
				'conditions' => array(
					'Ecourse.type' => $ecourseType
				)
			));

			if ($ecourses) {
				foreach ($ecourses as $key => $ecourse) {
					$data['ecourses'][] = $ecourse['Ecourse'];
				}
			} else {
				$data['ecourses'] = array();
			}

			$data['success'] = true;
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}
		$title_for_layout = 'Ecourses';
		$this->set(compact('title_for_layout'));
	}
}

?>
