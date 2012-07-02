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
		$document = json_decode($this->params['form']['program_documents'], true);

		$this->ProgramDocument->id = $document['id'];
		$this->ProgramDocument->set($document);

		if ($this->ProgramDocument->save()) {
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

	public function admin_upload() {
		$this->layout = 'ajax';
		$storagePath = substr(APP, 0, -1) . Configure::read('Program.media.path');

		switch ($_FILES['media']['type']) {
			case 'application/pdf':
				$path = $storagePath;
				$ext = '.pdf';
				break;

			case 'video/x-flv':
				$path = $storagePath;
				$ext = '.flv';
				break;

			default:
				break;
		}

		$filename = date('YmdHis') . $ext;

		if (!is_dir($path)) {
			mkdir($path);
		}

		if (!file_exists($path . $filename)) {
			$url = $path . $filename;
			if (!move_uploaded_file($_FILES['media']['tmp_name'], $url)) {
				$data['success'] = false;
			} else {
				$data['success'] = true;
				$data['url'] = $filename;
			}
		}

		$this->set(compact('data'));
		return $this->render(null, null, '/elements/ajaxreturn');
	}

}
