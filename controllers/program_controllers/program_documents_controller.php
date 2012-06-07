<?php
class ProgramDocumentsController extends AppController {

	public $name = 'ProgramDocuments';
	public $components = array('Email');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_create() {
			$document = json_decode($this->params['form']['program_documents'], true);

			$this->data['ProgramDocument'] = $document;
			$programDocument  = $this->ProgramDocument->save($this->data);

			if ($programDocument) {
				$data['program_documents'] = $programDocument;
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_read() {
		$programStepId = $this->params['url']['program_step_id'];

		$this->ProgramDocument->recursive = -1;
		$documents = $this->ProgramDocument->find('all', array(
			'conditions' => array(
				'ProgramDocument.program_step_id' => $programStepId
			)
		));

		if ($documents) {
			$data['success'] = true;
			foreach ($documents as $key => $value) {
				$data['program_documents'][] = $value['ProgramDocument'];
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
