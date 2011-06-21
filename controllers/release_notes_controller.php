<?php
class ReleaseNotesController extends AppController {

	var $name = 'ReleaseNotes';
	
	var $uses = array();
	
	function beforeFilter() {
		parent::beforeFilter();
		if($this->Auth->user('role_id') > 1) {
			$this->Auth->allowedActions = array('admin_index');
		}
	}

	function admin_index() {
		$title_for_layout = 'Atlas Release Notes';
		$this->set(compact('title_for_layout'));
	}

}