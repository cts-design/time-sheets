<?php
class ReleaseNotesController extends AppController {

	var $name = 'ReleaseNotes';
	
	var $uses = array();

	function admin_index() {
		$title_for_layout = 'Atlas Release Notes';
		$this->set(compact('title_for_layout'));
	}

}