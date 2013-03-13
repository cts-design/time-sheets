<?php

/**
 * Ecourses Controller
 *
 * @package   AtlasV3
 * @author    Brandon Cordell & Daniel Nolan
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
				'EcourseUser' => array(
						'conditions' => array(
							'EcourseUser.ecourse_id' => $id, 'EcourseUser.user_id' => $this->Auth->user('id'))),
				'EcourseResponse' => array (
					'conditions' => array(
						'EcourseResponse.user_id' => $this->Auth->user('id'),
						'EcourseResponse.reset' => 0),
					'EcourseModuleResponse' => array('conditions' => array('EcourseModuleResponse.pass_fail' => 'Pass'))
					))));

		if($ecourse['Ecourse']['requires_user_assignment']) {
			if(empty($ecourse['EcourseUser'][0])) {
				$this->Session->setFlash('You are not assigned to that course', 'flash_failure');	
				if($this->Auth->user('role_id') > 1) {
					$this->redirect('/admin/users/dashboard');
				}
				else {
					$this->redirect('/users/dashboard');
				}
			}
		}

		if(empty($ecourse['EcourseResponse'])) {
			$this->data['EcourseResponse']['user_id'] = $this->Auth->user('id');
			$this->data['EcourseResponse']['ecourse_id'] = $id;
			$this->Ecourse->EcourseResponse->save($this->data);
			$response = $this->Ecourse->EcourseResponse->findById($this->Ecourse->EcourseResponse->id);
			$ecourse['EcourseResponse'][0] = $response['EcourseResponse']; 
		}

		if($ecourse['EcourseResponse'][0]['status'] == 'completed') {
			$this->Session->setFlash('You have already completed this course', 'flash_failure');
			if($this->Auth->user('role_id') > 1) {
				$this->redirect('/admin/users/dashboard');
			}
			else {
				$this->redirect('/users/dashboard');
			}
		}

		$modules = Set::extract('/EcourseModule/id', $ecourse);
		$moduleResponses = Set::extract('/EcourseModuleResponse[pass_fail=Pass]/ecourse_module_id', $ecourse['EcourseResponse']);
		$diff = Set::diff($moduleResponses, $modules);

		if(!empty($diff)) {
			$diff = array_values($diff);
			$nextModule = Set::extract("/EcourseModule[id=$diff[0]]/.[1]", $ecourse);
		} 
		else {
			$nextModule = Set::extract("/EcourseModule/.[1]", $ecourse);
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
					'EcourseModuleQuestionAnswer' => array('fields' => array('id', 'text'))
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
						'fields' => array('id', 'text', 'correct'))
					))));
		$ecourseResponse = $this->Ecourse->EcourseResponse->find('first', array(
			'conditions' => array(
				'EcourseResponse.user_id' => $this->Auth->user('id'),
				'EcourseResponse.ecourse_id' => $ecourseModule['EcourseModule']['ecourse_id']),
			'fields' => array('id')));

		$userAnswers = $this->data['Ecourse'];
		array_pop($userAnswers);
		$userAnswers = array_values($userAnswers);

		$quizAnswers = Set::extract('/EcourseModuleQuestionAnswer[correct=1]/id', $ecourseModule['EcourseModuleQuestion']);

		$wrongAnswers = Set::diff($userAnswers, $quizAnswers);
		$numberCorrect = count($quizAnswers) - count($wrongAnswers);
		$quizScore = ($numberCorrect / count($quizAnswers)) * 100;

		$this->data['EcourseModuleResponse']['ecourse_module_id'] = $ecourseModule['EcourseModule']['id'];
		$this->data['EcourseModuleResponse']['ecourse_response_id'] = $ecourseResponse['EcourseResponse']['id'];
		$this->data['EcourseModuleResponse']['score'] = $quizScore;
		$this->data['EcourseModuleResponse']['pass_fail'] = ($ecourseModule['EcourseModule']['passing_percentage'] <= $quizScore) ? 'Pass' : 'Fail';

		if ($this->Auth->user('role_id') == 1) {
			$userIsAdmin = false;
		} else {
			$userIsAdmin = true;
		}

		if($this->Ecourse->EcourseResponse->EcourseModuleResponse->save($this->data['EcourseModuleResponse'])) {
			if($ecourseModule['EcourseModule']['passing_percentage'] <= $quizScore) {
				$nextModule = $this->Ecourse->EcourseModule->find('first',
					array('conditions' => array('EcourseModule.ecourse_id' => 2, 'EcourseModule.order >' => $ecourseModule['EcourseModule']['order']))
				);
				// TODO: add logic to add passing transaction.
				$this->Session->setFlash('You passed the quiz.', 'flash_success');
				if($nextModule) {
					$this->redirect(array('controller' => 'ecourses', 'action' => 'index', $ecourseModule['EcourseModule']['ecourse_id'], 'admin' => $userIsAdmin));
				}
				else {
					// Logic to mark the ecourse response complete if passed quiz for last module
					$this->Ecourse->EcourseResponse->id = $ecourseResponse['EcourseResponse']['id'];
					$this->Ecourse->EcourseResponse->saveField('completed', date('Y-m-d H:i:s'));
					$this->Ecourse->EcourseResponse->saveField('status', 'completed');
					$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => $userIsAdmin));
				}
			}
			else {
				// TODO: add logic to add failing transaction.
				$this->Session->setFlash('You did not pass the quiz, please try again', 'flash_failure');

				$this->set(compact('userAnswers', 'ecourseModule'));
				$this->render('failed_quiz');
				//$this->redirect(array('controller' => 'ecourses', 'action' => 'index', $ecourseModule['EcourseModule']['ecourse_id'], 'admin' => $userIsAdmin));
			}
		}
		else {
			$title_for_layout = $ecourseModule['EcourseModule']['name'] . ' Quiz';
			$this->set(compact('ecourseModule', 'title_for_layout'));
			$this->render('/ecourses/quiz/');
			// TODO: fix this so that form data is preserved in the event the response cannot be saved	
		}
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
				$this->Transaction->createUserTransaction(
					'Ecourses', null, null, 'Added ecourse id: ' . $this->Ecourse->id
				);
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
				$this->Transaction->createUserTransaction(
					'Ecourses', null, null, 'Updated ecourse id: ' . $ecourseData['id']
				);
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
