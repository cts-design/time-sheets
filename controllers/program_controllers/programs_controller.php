<?php

class ProgramsController extends AppController {
	
	var $name = 'Programs';
			
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		$program = $this->Program->findById($id);
		if($program['Program']['disabled'] == 1){
			$this->Session->setFlash(__('This program is disabled', true), 'flash_failure');
			$this->redirect('/');
		}
		$title_for_layout = $program['Program']['name'];
		$this->set(compact('program', 'title_for_layout'));
	}
		
	function admin_index() {
		$title_for_layout = 'Programs';
	}
}