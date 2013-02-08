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
		$data = array();
		$this->set(compact('data'));
		$this->render(null, null, '/elements/ajaxreturn');
	}
}

?>
