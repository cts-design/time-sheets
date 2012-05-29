<?php
class ProgramStepsController extends AppController {

	public $name = 'ProgramSteps';
	public $components = array('Email');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_create() {
		if ($this->RequestHandler->isAjax()) {
			$programData = json_decode($this->params['form']['programs'], true);
			unset($programData['id'], $programData['created'], $programData['modified']);

			$this->data['Program'] = $programData;

			$this->Program->create();
			$program = $this->Program->save($this->data);

			if ($this->Program->save($program)) {
				$programId = $this->Program->id;
				$data['programs'] = $program['Program'];
				$data['programs']['id'] = $programId;

				$this->createRegistrationProgramSteps($programId, $program['Program']['name']);

				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_read() {
		if (isset($this->params['url']['program_id'])) {
			$steps = $this->ProgramStep->find('all', array(
				'conditions' => array('ProgramStep.program_id' => $this->params['url']['program_id'])
			));
		} else {
			$steps = $this->ProgramStep->find('all');
		}

		if ($steps) {
			$data['success'] = true;
			foreach ($steps as $key => $value) {
				$data['program_steps'][] = $value['ProgramStep'];
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
