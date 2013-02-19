<?php

/**
 * EcourseUsers Controller
 *
 * @package   AtlasV3
 * @author    Daniel Nolan
 * @copyright 2013 Complete Technology Solutions
 */
class EcourseUsersController extends AppController {
	public $name = 'EcourseUsers';

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_assign_customers() {
		if($this->RequestHandler->isAjax()) {
			$this->data['EcourseUser']['user_id'] = $this->params['form']['user_id'];
			$this->data['EcourseUser']['ecourse_id'] = $this->params['form']['ecourse_id'];
			if($this->EcourseUser->save($this->data)) {
				$data['success'] = true;
				$data['message'] = 'User successfully assigned to ecourse.'; 
		   	}	
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}			
	}

	public function admin_assign_staff() {
		if($this->RequestHandler->isAjax()) {
			$this->data['EcourseUser']['user_id'] = $this->params['form']['user_id'];
			$this->data['EcourseUser']['ecourse_id'] = $this->params['form']['ecourse_id'];
			if($this->EcourseUser->save($this->data)) {
				$data['success'] = true;
				$data['message'] = 'User successfully assigned to ecourse.'; 
		   	}	
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}			
	}

	public function admin_delete() {
		if($this->RequestHandler->isAjax()) {
			
		}	
	}
}

