<?php

/**
 * Ecourses Controller
 *
 * @package   AtlasV3
 * @author    Brandon Cordell
 * @copyright 2013 Complete Technology Solutions
 */
class EcoursesController extends AppController {
	public $name = 'Ecourses';

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index($id = null) {
        if(!$id){
            $this->Session->setFlash(__('Invalid id', true), 'flash_failure');
            $this->redirect($this->referer());
        }
		$ecourse = $this->Ecourse->findById($id);
		// TODO logic to figure what user has completed, and what module should be loaded next	
		$this->set(compact('ecourse'));
	}

	public function quiz($id) {
		$this->Ecourse->Behaviors->attach('Containable');

		$ecourse = $this->Ecourse->find('first', array(
			'conditions' => array(
				'Ecourse.id' => $id
			),
			'contain' => array(
				'EcourseModule' => array(
					'EcourseModuleQuestion' => array(
						'order' => array('EcourseModuleQuestion.order ASC'),
						'EcourseModuleQuestionAnswer'
					)
				)
			)
		));

		$title_for_layout = $ecourse['EcourseModule'][0]['name'] . ' Quiz';
		$this->set(compact('ecourse', 'title_for_layout'));
	}

	public function save($id) {
		$ecourse = $this->Ecourse->find('first', array(
			'conditions' => array(
				'Ecourse.id' => $id
			),
			'contain' => array(
				'EcourseModule'
			)
		));

		$this->Session->setFlash(__('You have passed ' . $ecourse['EcourseModule'][0]['name'], true), 'flash_success');

		
		if ($this->Auth->user('role_id') == 1) {
			$userIsAdmin = false;
		} else {
			$userIsAdmin = true;
		}

		$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => $userIsAdmin));
	}

    public function load_media($mediaLocation=null) {
        $this->view = 'Media';
        if($mediaLocation) {
            $explode = explode('.', $mediaLocation);
            $params = array(
                'id' => $mediaLocation,
                'name' => $explode[0],
                'extension' => $explode[1],
                'path' => Configure::read('Ecourse.media.path')
            );
            $this->set($params);
            return $params;
        }
		return false;
    }

	public function admin_index() {
		$this->Ecourse->recursive = -1;
		$this->Ecourse->Behaviors->attach('Containable');

		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['url']['ecourse_type'])) {
				$ecourseType = $this->params['url']['ecourse_type'];

				$ecourses = $this->Ecourse->find('all', array(
					'conditions' => array(
						'Ecourse.type' => $ecourseType
					),
					'contain' => array(
						'EcourseUser'
					)
				));
			}

			if (isset($this->params['url']['id'])) {
				$ecourseId = $this->params['url']['id'];

				$ecourses = $this->Ecourse->find('all', array(
					'conditions' => array(
						'Ecourse.id' => $ecourseId
					),
					'contain' => array(
						'EcourseUser'
					)
				));
			}

			if ($ecourses) {
				$this->log($ecourses, 'debug');
				foreach ($ecourses as $key => $ecourse) {
					$ecourse['Ecourse']['assigned_user_count'] = count($ecourse['EcourseUser']);
					$data['ecourses'][$key] = $ecourse['Ecourse'];
				}
			} else {
				$data['ecourses'] = array();
			}

			$data['success'] = true;
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}

		$title_for_layout = 'Ecourses';
		$this->set(compact('title_for_layout'));
	}

	public function admin_create($type = 'customer') {
		if ($this->RequestHandler->isAjax()) {
			$ecourseData = json_decode($this->params['form']['ecourses'], true);
			$this->data['Ecourse'] = $ecourseData;

			$this->Ecourse->create();

			if ($this->Ecourse->save($this->data)) {
				$data['success'] = true;
				$data['ecourses'] = $ecourseData;
			} else {
				$data['success'] = false;
			}
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}

		$ecourseType = $type;
		$title_for_layout = 'New Ecourse';
		$this->set(compact('title_for_layout', 'type'));
	}

	public function admin_read($type = 'customer') {
		if ($this->RequestHandler->isAjax()) {
			$ecourseId = $this->params['url']['id'];

			$ecourse = $this->Ecourse->findById($ecourseId);

			if ($ecourse) {
				$data[] = $ecourse['Ecourse'];
			}

			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}
	}

	public function admin_update($id = null) {
		if ($this->RequestHandler->isAjax()) {
			$ecourseData = json_decode($this->params['form']['ecourses'], true);

			// monkey patch to fix ExtJS not sending disabled filed cats
			if (isset($ecourseData['certificate_cat_1']) && !isset($ecourseData['certificate_cat_2'])) {
				$ecourseData['certificate_cat_2'] = null;
			}

			if (isset($ecourseData['certificate_cat_2']) && !isset($ecourseData['certificate_cat_3'])) {
				$ecourseData['certificate_cat_3'] = null;
			}

			$ecourse = $this->Ecourse->read(null, $ecourseData['id']);
			$this->Ecourse->set($ecourseData);

			if ($this->Ecourse->save()) {
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		} else {
			if(!$id){
				$this->Session->setFlash(__('Invalid id', true), 'flash_failure');
				$this->redirect($this->referer());
			}

			$ecourse = $this->Ecourse->findById($id);
			$title_for_layout = 'Edit ' . ucwords($ecourse['Ecourse']['name']);
			$this->set(compact('title_for_layout', 'ecourse'));
		}

	}
}
