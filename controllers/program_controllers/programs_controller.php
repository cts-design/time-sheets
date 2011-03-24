<?php

class ProgramsController extends AppController {
	
	
	function index() {
		$program = $this->Program->findById('1');
		$this->set(compact('program'));
	}
		
	function admin_index() {
		
	}
}
