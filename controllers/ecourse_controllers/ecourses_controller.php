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
		$this->Ecourse->recursive = -1;
		$ecourse = $this->Ecourse->find('first', array(
			'conditions' => array('Ecourse.id' => $id),
			'contain' => array(
				'EcourseModule' => array('order' => 'EcourseModule.order ASC'),
				'EcourseResponse' => array ('conditions' => array('EcourseResponse.user_id' => $this->Auth->user('id')),
				'EcourseModuleResponse' => array('conditions' => array('EcourseModuleResponse.pass_fail' => 'Pass'))))));
		if(empty($ecourse['EcourseResponse'])) {
			$this->data['EcourseResponse']['user_id'] = $this->Auth->user('id');
			$this->Ecourse->EcourseResponse->save($this->data);
		}
		$modules = Set::extract('/EcourseModule/id', $ecourse);
		$moduleRespneses = Set::extract('/EcourseModuleResponse/id', $ecourse['EcourseResponse']);
		foreach($modules as $module) {
			if(! in_array($module, $moduleRespneses)) {
				$nextModule = Set::extract("/EcourseModule[id=$module]/.[:first]", $ecourse);
			} 
		}
		$title_for_layout = $nextModule[0]['name'] ;
		$this->set(compact('nextModule', 'title_for_layout'));
	}

	public function quiz($id) {
        if(!$id){
            $this->Session->setFlash(__('Invalid id', true), 'flash_failure');
            $this->redirect($this->referer());
        }
		$ecourseModule = $this->Ecourse->EcourseModule->find('first', array(
			'conditions' => array(
				'EcourseModule.id' => $id
			),
			'contain' => array(
				'EcourseModuleQuestion' => array(
					'order' => array('EcourseModuleQuestion.order ASC'),
					'EcourseModuleQuestionAnswer'
				)
			)
		));
		$title_for_layout = $ecourseModule['EcourseModule']['name'] . ' Quiz';
		$this->set(compact('ecourseModule', 'title_for_layout'));
	}

	public function grade() {
		$this->Ecourse->EcourseModule->recursive = -1;
		$ecourseModule = $this->Ecourse->EcourseModule->find('first', array(
			'conditions' => array(
				'EcourseModule.id' => $this->data['Ecourse']['module_id']
			),
			'contain' => array(
				'EcourseModuleQuestion' => array(
					'order' => array('EcourseModuleQuestion.order ASC'),
					'EcourseModuleQuestionAnswer' => array(
						'fields' => array('id', 'text'),
						'conditions' => array('EcourseModuleQuestionAnswer.correct' => 1 ))
				)
			)
		));
		$ecourseResponse = $this->Ecourse->EcourseResponse->find('first', array(
			'conditions' => array(
				'EcourseResponse.user_id' => $this->Auth->user('id'),
				'EcourseResponse.ecourse_id' => $ecourseModule['EcourseModule']['ecourse_id']),
			'fields' => array('id')));

		$userAnswers = $this->data['Ecourse'];
		array_pop($userAnswers);
		$userAnswers = array_values($userAnswers);

		$quizAnswers = Set::extract('/EcourseModuleQuestionAnswer/id', $ecourseModule['EcourseModuleQuestion']);

		$wrongAnswers = Set::diff($userAnswers, $quizAnswers);
		$numberCorrect = count($quizAnswers) - count($wrongAnswers);
		$quizScore = ($numberCorrect / count($quizAnswers)) * 100;

		$this->data['EcourseModuleResponse']['ecourse_module_id'] = $ecourseModule['EcourseModule']['id'];
		$this->data['EcourseModuleResponse']['ecourse_response_id'] = $ecourseResponse['EcourseResponse']['id'];
		$this->data['EcourseModuleResponse']['score'] = $quizScore;
		$this->data['EcourseModuleResponse']['pass_fail'] = ($ecourseModule['EcourseModule']['passing_percentage'] <= $quizScore) ? 'Pass' : 'Fail';
		if($this->Ecourse->EcourseResponse->EcourseModuleResponse->save($this->data['EcourseModuleResponse'])) {
			// TODO: add logic to add transaction. direct to next module if passed, else redirect back to user dash
			// and show failed flash message
		}
		/*
		$testy = $this->Ecourse->EcourseModule->find('first',
			array('conditions' => array('EcourseModule.ecourse_id' => 2, 'EcourseModule.order >' => $ecourseModule['EcourseModule']['order']))
		);
		$this->log($testy, 'debug');
		 */
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
}
