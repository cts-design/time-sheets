<?php
App::import('Vendor', 'DebugKit.FireCake');
class ProgramFieldsController extends AppController {

	var $name = 'ProgramFields';

	function admin_index() {}
	
	function admin_create() {
		$programFields = json_decode($this->params['form']['ProgramFields'], true);

		$success = $this->ProgramField->saveAll($programFields);
		if ($success) {
			$data = $programFields;
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
	function admin_read() {}
	function admin_update() {}
	function admin_destroy() {}	
}
?>