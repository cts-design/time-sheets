<?php

/**
 * Ecourse Modules Controller
 *
 * @package   AtlasV3
 * @author    Brandon Cordell
 * @copyright 2013 Complete Technology Solutions
 */
class EcourseModulesController extends AppController {
	public $name = 'EcourseModules';

	public function beforeFilter() {
		parent::beforeFilter();
		if($this->Auth->user()) {

		    if($this->Acl->check(array(
				'model' => 'User',
				'foreign_key' => $this->Auth->user('id')), 'Ecourses/admin_index', '*')){
					$this->Auth->allow('admin_index', 'admin_update', 'admin_create', 'admin_destroy', 'admin_upload_media');
			}

			$this->Auth->allow('admin_view_media');
		}
	}

	public function admin_index($ecourse_id = null) {
		if (isset($this->params['url']['ecourse_id'])) {
			$ecourse_id = $this->params['url']['ecourse_id'];
		}

		if (!$ecourse_id) {
			$this->Session->setFlash(__('Invalid Ecourse id', true), 'flash_failure');
			$this->redirect(array('controller' => 'ecourses', 'admin' => true));
		}

		$ecourse = $this->EcourseModule
						->Ecourse
						->findById($ecourse_id);

		if ($this->RequestHandler->isAjax()) {
			$this->paginate = array(
				'conditions' => array(
					'EcourseModule.ecourse_id' => $this->params['url']['ecourse_id']
				),
				'order' => array(
					'EcourseModule.order' => 'ASC'
				)
			);
			$ecourse_modules = $this->Paginate('EcourseModule');

			if ($ecourse_modules) {
				$data['success'] = true;

				foreach ($ecourse_modules as $key => $module) {
					$data['ecourse_modules'][] = $module['EcourseModule'];
				}
			} else {
				$data['success'] = true;
				$data['ecourse_modules'] = array();
			}
			$data['totalCount'] = $this->params['paging']['EcourseModule']['count'];

			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}

		$title_for_layout = "Modules for {$ecourse['Ecourse']['name']}";
		$this->set(compact('title_for_layout', 'ecourse'));
	}

	public function admin_create() {
		$ecourse_module = json_decode($this->params['form']['ecourse_modules'], true);

		$this->EcourseModule->create();
		$this->data['EcourseModule'] = $ecourse_module;

		if ($this->EcourseModule->save($this->data)) {
			$this->Transaction->createUserTransaction(
				'Ecourses',
				null,
				null,
				"Added ecourse module id: {$this->EcourseModule->id} to ecourse id: {$this->data['EcourseModule']['ecourse_id']}"
			);
			$data['success'] = true;
			$data['ecourse_modules'] = $ecourse_module;
			$data['ecourse_modules']['id'] = $this->EcourseModule->id;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}

	public function admin_update() {
		$ecourseData = json_decode($this->params['form']['ecourse_modules'], true);

		if (isset($ecourseData[1])) {
			$this->data['EcourseModule'] = $ecourseData;

			if ($this->EcourseModule->saveAll($this->data['EcourseModule'])) {
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}
		} else {
			$this->EcourseModule->read(null, $ecourseData['id']);
			$this->EcourseModule->set($ecourseData);

			if ($this->EcourseModule->save()) {
				$this->Transaction->createUserTransaction(
					'Ecourses',
					null,
					null,
					'Updated module id: ' . $ecourseData['id'] .
					' for ecourse id: ' . $this->params['form']['ecourse_id']
				);
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}

	public function admin_destroy() {
		$ecourse = json_decode($this->params['form']['ecourse_modules'], true);

		if ($this->EcourseModule->delete($ecourse['id'])) {
			$this->Transaction->createUserTransaction(
				'Ecourses',
				null,
				null,
				'Deleted module id: ' . $ecourse['id'] .
				' from ecourse id: ' . $this->params['form']['ecourse_id']
			);
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}

	public function admin_upload_media() {
		$this->layout = 'ajax';
		$storagePath = substr(APP, 0, -1) .
						Configure::read('Document.storage.path') .
						'ecourse_media/';

		$pathinfo = pathinfo($_FILES['media']['name']);
		$fileExt = ".{$pathinfo['extension']}";

		$filename = date('YmdHis') . $fileExt;

		if (!is_dir($storagePath)) {
			mkdir($storagePath);
		}

		$absFileLocation = $storagePath . $filename;

		if (!file_exists($absFileLocation)) {
			if (!move_uploaded_file($_FILES['media']['tmp_name'], $absFileLocation)) {
				$data['success'] = false;
			} else {
				$data['success'] = true;
				$data['location'] = $filename;
			}
		}

		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_view_media($moduleId=null) {
		$this->layout = 'ajax';

		$module = $this->EcourseModule->findById($moduleId);

		$this->set('module', $module);
	}
}

?>
