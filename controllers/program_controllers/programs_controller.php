<?php

class ProgramsController extends AppController {
		
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		$program = $this->Program->findById('1');
		$title_for_layout = $program['Program']['name'];
		$this->set(compact('program', 'title_for_layout'));
	}
		
	function admin_index() {
		$title_for_layout = 'Programs';
	}
}
