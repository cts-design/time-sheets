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
		$instructions = $ecourse['EcourseModule'][0]['instructions'];
		$media = '/ecourses/load_media/'. $ecourse['EcourseModule'][0]['media_location'];
		$this->set(compact('media', 'instructions'));
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
		$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
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
}
