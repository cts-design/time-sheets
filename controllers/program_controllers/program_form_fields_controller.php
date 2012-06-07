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

			$count = count($formData);

			if ($count > 1) {
				$this->data['ProgramFormField'] = $formData;
				$formField = $this->ProgramFormField->saveAll($this->data['ProgramFormField']);
			} else {
				$this->data['ProgramFormField'] = $formData[0];
				$formField = $this->ProgramFormField->save($this->data);
			}

			if ($formField) {
				if ($count > 1) {
					foreach ($this->data['ProgramFormField'] as $k => $v) {
						$data['program_form_fields'][] = $v;
					}
				} else {
					$data['program_form_fields'] = $formField['ProgramFormField'];
				}

				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_read() {
		$programStepId = $this->params['url']['program_step_id'];

		$this->ProgramFormField->recursive = -1;
		$formFields = $this->ProgramFormField->find('all', array(
			'conditions' => array(
				'ProgramFormField.program_step_id' => $programStepId
			)
		));

		if ($formFields) {
			$data['success'] = true;
			foreach ($formFields as $key => $value) {
				$data['program_form_fields'][] = $value['ProgramFormField'];
			}
		} else {
			$data['success'] = false;
		}
	
		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_update() {
		$formField = json_decode($this->params['form']['program_form_fields'], true);
		$formField = $formField[0];

		$this->ProgramFormField->id = $formField['id'];
		$this->ProgramFormField->set($formField);

		if ($this->ProgramFormField->save()) {
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_destroy() {
		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

}
