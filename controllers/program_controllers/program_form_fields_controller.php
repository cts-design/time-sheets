<?php
class ProgramFormFieldsController extends AppController {

	public $name = 'ProgramFormFields';
	public $components = array('Email');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_create() {
			$formData = json_decode($this->params['form']['program_form_fields'], true);
			foreach ($formData as $key => $value) {
				unset($formData[$key]['id'], $formData[$key]['created'], $formData[$key]['modified']);
			}

			$this->data['ProgramFormField'] = $formData;
			$formField = $this->ProgramFormField->saveAll($this->data['ProgramFormField']);

			if ($formField) {
				foreach ($this->data['ProgramFormField'] as $k => $v) {
					$data['program_form_fields'][] = $v;
				}

				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_read() {
		$steps = $this->ProgramStep->find('all');

		if ($steps) {
			$data['success'] = true;
			foreach ($steps as $key => $value) {
				
			}
		} else {
			$data['success'] = false;
		}
	
		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_update() {
		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_destroy() {
		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

}
