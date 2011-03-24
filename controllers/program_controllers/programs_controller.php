<?php

class ProgramsController extends AppController {
		
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		$program = $this->Program->findById('1');
		$this->set(compact('program'));
	}
		
	function admin_index() {
		
	}
}
