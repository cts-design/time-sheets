<?php
class ModuleAccessControlsController extends AppController {

	var $name = 'ModuleAccessControls';

	function admin_index() {
		$this->ModuleAccessControl->recursive = 0;
		$this->set('moduleAccessControls', $this->paginate());
	}
}

