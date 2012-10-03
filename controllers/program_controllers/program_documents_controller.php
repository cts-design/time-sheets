<?php
class ProgramDocumentsController extends AppController {

	public $name = 'ProgramDocuments';
	public $components = array('Email');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_create() {
		$this->loadModel('WatchedFilingCat');
		$document = json_decode($this->params['form']['program_documents'], true);
		$data['success'] = false;

		if ($document['type'] === 'upload') {
			if ($document['cat_3']) {
				$watchedCatId = $document['cat_3'];
			} else if ($document['cat_2']) {
				$watchedCatId = $document['cat_2'];
			} else if ($document['cat_1']) {
				$watchedCatId = $document['cat_1'];
			}

			$catExists = $this->WatchedFilingCat->find('first', array(
				'conditions' => array(
					'WatchedFilingCat.cat_id' => $watchedCatId
				)
			));

			if ($catExists) {
				$data['message'] = 'You can not associate watched filing categories with ' .
					'more than one program. Please create another watched ' .
					'filing category.';
				$saved = false;
			} else {
				// get name of cat
				$watchedFilingCat['WatchedFilingCat'] = $document;
				$name = strtolower(Inflector::slug($watchedFilingCat['WatchedFilingCat']['name']));
				$watchedFilingCat['WatchedFilingCat']['name'] = $name;
				$watchedFilingCat['WatchedFilingCat']['cat_id'] = $watchedCatId;

				$saved = $this->WatchedFilingCat->save($watchedFilingCat);
				$watchedFilingCat['WatchedFilingCat']['id'] = $this->WatchedFilingCat->id;
				$programData = $watchedFilingCat['WatchedFilingCat'];
			}
		} else {
			$this->data['ProgramDocument'] = $document;
			$saved  = $this->ProgramDocument->save($this->data);
			$programData = $saved['ProgramDocument'];
		}

		if ($saved) {
			$data['program_documents'] = $programData;
			$data['success'] = true;
		}

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_read() {
		if (isset($this->params['url']['program_step_id'])) {
			$conditions = array(
				'ProgramDocument.program_step_id' => $this->params['url']['program_step_id']
			);
		} else if (isset($this->params['url']['program_id'])) {
			$conditions = array(
				'ProgramDocument.program_id' => $this->params['url']['program_id']
			);

			$catCondition = array(
				'WatchedFilingCat.program_id' => $this->params['url']['program_id']
			);
		}

		$this->ProgramDocument->recursive = -1;
		$documents = $this->ProgramDocument->find('all', array(
			'conditions' => $conditions
		));

		$this->loadModel('WatchedFilingCat');
		$this->WatchedFilingCat->recursive = -1;
		$watchedCats = $this->WatchedFilingCat->find('all', array(
			'conditions' => $catCondition
		));

		$data['success'] = true;

		if ($watchedCats) {
			foreach ($watchedCats as $key => $value) {
				$parents = $this->getWatchedCatPath($value['WatchedFilingCat']['cat_id']);

				for ($i = 0; $i < count($parents); $i++) {
					$id = $i + 1;
					$catId = "cat_$id";
					$catName = "{$catId}_name";

					$value['WatchedFilingCat'][$catId] = $parents[$i]['DocumentFilingCategory']['id'];
					$parent['cats'][$catName] = $parents[$i]['DocumentFilingCategory']['name'];
				}
				$value['WatchedFilingCat']['type'] = 'Upload';
				$data['program_documents'][] = $value['WatchedFilingCat'];
			}

			if (!$data['success']) { $data['success'] = true; }
		}

		if ($documents) {
			$data['success'] = true;
			foreach ($documents as $key => $value) {
				$data['program_documents'][] = $value['ProgramDocument'];
			}
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
		$document = json_decode($this->params['form']['program_documents'], true);
		$docType  = $this->params['form']['type'];
		$data['success'] = false;


		if ($docType === 'upload') {
			$this->loadModel('WatchedFilingCat');
			$watchedCat = $this->WatchedFilingCat->id = $document['id'];

			if ($this->WatchedFilingCat->delete()) {
				$data['success'] = true;
			}
		} else {
			$programDocument = $this->ProgramDocument->id = $document['id'];

			if ($this->ProgramDocument->delete()) {
				$data['success'] = true;
			}
		}

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_upload() {
		$this->layout = 'ajax';
		$storagePath = substr(APP, 0, -1) .
			Configure::read('Document.storage.path') .
			'program_forms/';

		switch ($_FILES['document']['type']) {
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
			if (!move_uploaded_file($_FILES['document']['tmp_name'], $url)) {
				$data['success'] = false;
			} else {
				$data['success'] = true;
				$data['url'] = $filename;
			}
		}

		$this->set(compact('data'));
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_create_watched_cat() {
		$this->loadModel('WatchedFilingCat');
		$watchedCat = json_decode($this->params['form']['cats'], true);

		$this->WatchedFilingCat->create();
		$data['WatchedFilingCat'] = $watchedCat[0];

		if ($this->WatchedFilingCat->save($data)) {
			$data['success'] = true;
			$data['cats'][] = $data['WatchedFilingCat'];
			$data['cats'][0]['id'] = $this->WatchedFilingCat->id;

			$parents = $this->getWatchedCatPath($data['WatchedFilingCat']['cat_id']);

			for ($i = 0; $i < count($parents); $i++) {
				$id = $i + 1;
				$catId = "cat_$id";
				$catName = "{$catId}_name";

				$data['cats'][0][$catId] = $parents[$i]['DocumentFilingCategory']['id'];
				$data['cats'][0][$catName] = $parents[$i]['DocumentFilingCategory']['name'];
			}
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_read_watched_cat() {
		$this->loadModel('WatchedFilingCat');
		$programId = $this->params['url']['program_id'];

		$watchedCats = $this->WatchedFilingCat->find('all', array(
			'conditions' => array(
				'WatchedFilingCat.program_id' => $programId
			)
		));

		if ($watchedCats) {
			$data['success'] = true;
			$j = 0;
			foreach ($watchedCats as $key => $value) {
				$data['cats'][$j] = $value['WatchedFilingCat'];

				$parents = $this->getWatchedCatPath($value['WatchedFilingCat']['cat_id']);

				for ($i = 0; $i < count($parents); $i++) {
					$id = $i + 1;
					$catId = "cat_$id";
					$catName = "{$catId}_name";

					$data['cats'][$j][$catId] = $parents[$i]['DocumentFilingCategory']['id'];
					$data['cats'][$j][$catName] = $parents[$i]['DocumentFilingCategory']['name'];
				}

				$j++;
			}
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_update_watched_cat() {
		$this->loadModel('WatchedFilingCat');
		$watchedCat = json_decode($this->params['form']['cats'], true);
	}

	public function admin_destroy_watched_cat() {
		$this->loadModel('WatchedFilingCat');
		$watchedCat = json_decode($this->params['form']['cats'], true);

		if ($this->WatchedFilingCat->delete($watchedCat[0]['id'])) {
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	private function getWatchedCatPath($id) {
		$this->loadModel('WatchedFilingCat');
		return $this->WatchedFilingCat->DocumentFilingCategory->getPath($id);
	}
}
