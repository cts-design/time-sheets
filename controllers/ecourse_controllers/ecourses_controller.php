<?php

App::Import('Sanitize');

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
		$this->Auth->allow('load_media');
		if($this->Auth->user()) {
			$this->Auth->allow('index', 'media', 'quiz', 'grade');
		    if($this->Acl->check(array(
				'model' => 'User',
				'foreign_key' => $this->Auth->user('id')), 'Ecourses/admin_index', '*')){
					$this->Auth->allow('admin_read', 'admin_update', 'admin_create');
			}
		}		
	}

	public function index($id = null) {
		if(!$id){
			$this->Session->setFlash(__('Invalid id', true), 'flash_failure');
			$this->redirect($this->referer());
		}

		$this->Ecourse->recursive = -1;

		$ecourse = $this->Ecourse->find('first', array(
			'conditions' => array(
				'Ecourse.id' => $id
			),
			'contain' => array(
				'EcourseResponse' => array(
					'conditions' => array(
						'EcourseResponse.user_id' => $this->Auth->user('id')
					)
				)
			)
		));

		if (!empty($ecourse['EcourseResponse'])) {
			$this->redirect(array(
				'controller' => 'ecourses',
				'action' => 'media',
				$id
			));
		}

		$this->set('ecourse', $ecourse);
	}

	public function media($id = null) {
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
							'EcourseUser.ecourse_id' => $id,
							'EcourseUser.user_id' => $this->Auth->user('id'))),
				'EcourseResponse' => array (
					'conditions' => array(
						'EcourseResponse.user_id' => $this->Auth->user('id'),
						'EcourseResponse.reset' => 0),
					'EcourseModuleResponse'))));

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
		if(empty($ecourse['EcourseResponse']) && $id != 'undefined') {
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
		$modId = $nextModule[0]['id'];
		$passFail = null;
		$moduleResponse = Set::extract("/EcourseModuleResponse[ecourse_module_id=$modId]/.[1]", $ecourse['EcourseResponse']);
		if(empty($moduleResponse) || $moduleResponse[0]['pass_fail'] == 'Fail') {
			$this->data['EcourseModuleResponse']['ecourse_module_id'] = $nextModule[0]['id'];
			$this->data['EcourseModuleResponse']['ecourse_response_id'] = $ecourse['EcourseResponse'][0]['id'];
			if($this->Ecourse->EcourseResponse->EcourseModuleResponse->save($this->data)) {
				$newResponse = $this->Ecourse->EcourseResponse->EcourseModuleResponse->findById($this->Ecourse->EcourseResponse->EcourseModuleResponse->id);
				$moduleResponse[0] = $newResponse['EcourseModuleResponse'];
			}
		}

		$modResponseTimeId = $this->logTimeIn($moduleResponse[0]['id'], 'media');

		$title_for_layout = $nextModule[0]['name'] ;
		$this->set(compact('nextModule', 'title_for_layout', 'modResponseTimeId'));
	}

	public function quiz($id=null, $modResponseTimeId) {
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
					'EcourseModuleQuestionAnswer' => array('fields' => array('id', 'text', 'correct'))
				)
			)
		));
		$responseTime = $this->Ecourse->EcourseResponse->EcourseModuleResponse->EcourseModuleResponseTime->findById($modResponseTimeId);
		$this->logTimeOut($responseTime['EcourseModuleResponseTime']['id']);
		$responseTimeId = $this->logTimeIn($responseTime['EcourseModuleResponseTime']['ecourse_module_response_id'], 'quiz');
		// TODO: add time tracking
		// TODO: add transactions
		$title_for_layout = $ecourseModule['EcourseModule']['name'] . ' Quiz';
		$this->set(compact('ecourseModule', 'title_for_layout', 'responseTimeId'));
	}


	public function grade() {
		$this->Ecourse->EcourseModule->recursive = -1;
		$ecourseModule = $this->Ecourse->EcourseModule->find('first', array(
			'conditions' => array(
				'EcourseModule.id' => $this->data['Ecourse']['module_id']
			),
			'contain' => array(
				'Ecourse',
				'EcourseModuleQuestion' => array(
					'order' => array('EcourseModuleQuestion.order ASC'),
					'EcourseModuleQuestionAnswer' => array(
						'fields' => array('id', 'text', 'correct'))))));

		$this->Ecourse->EcourseResponse->recursive = -1;
		$ecourseResponse = $this->Ecourse->EcourseResponse->find('first', array(
			'conditions' => array(
				'EcourseResponse.user_id' => $this->Auth->user('id'),
				'EcourseResponse.ecourse_id' => $ecourseModule['EcourseModule']['ecourse_id'],
				'EcourseResponse.reset' => 0),
			'fields' => array('id'),
			'contain' => array(
				'EcourseModuleResponse' => array(
					'conditions' => array(
						'EcourseModuleResponse.ecourse_module_id' => $ecourseModule['EcourseModule']['id'],
						'EcourseModuleResponse.pass_fail' => NULL)))));
		$this->logTimeOut($this->data['Ecourse']['response_time_id']);
		
		$userAnswers = $this->data['Ecourse'];
		$count = count($userAnswers);
		$userAnswers = array_values($userAnswers);
		$userAnswers = array_slice($userAnswers, 0, $count - 2);

		$quizAnswers = Set::extract('/EcourseModuleQuestionAnswer[correct=1]/id', $ecourseModule['EcourseModuleQuestion']);

		$wrongAnswers = Set::diff($userAnswers, $quizAnswers);
		$numberCorrect = count($quizAnswers) - count($wrongAnswers);
		$quizScore = ($numberCorrect / count($quizAnswers)) * 100;

		$this->set(compact('quizScore', 'numberCorrect', 'wrongAnswers', 'quizAnswers', 'userAnswers'));
		$this->Session->write('userAnswers', $userAnswers);
		$this->Session->write('quizAnswers', $quizAnswers);
		
		$this->data['EcourseModuleResponse']['id'] = $ecourseResponse['EcourseModuleResponse'][0]['id'];
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
				$this->Session->delete('userAnswers');
				$this->Session->delete('quizAnswers');

				$nextModule = $this->Ecourse->EcourseModule->find('first',
					array('conditions' => array(
						'EcourseModule.ecourse_id' => $ecourseModule['EcourseModule']['ecourse_id'],
						'EcourseModule.order >' => $ecourseModule['EcourseModule']['order']))
				);
				// TODO: add logic to add passing transaction.
				$this->Transaction->createUserTransaction(
					'Ecourses',
					null,
					null,
					'Passed ecourse module: ' . $ecourseModule['EcourseModule']['name']
				);
				$this->Session->setFlash('You passed the quiz.', 'flash_success');
				if($nextModule) {
					$this->redirect(array('controller' => 'ecourses', 'action' => 'media', $ecourseModule['EcourseModule']['ecourse_id']));
				}
				else {
					// Logic to mark the ecourse response complete if passed quiz for last module
					$this->Transaction->createUserTransaction(
						'Ecourses',
						null,
						null,
						'Completed ecourse: ' . $ecourseModule['Ecourse']['name']
					);
					$this->Ecourse->EcourseResponse->read(null, $ecourseResponse['EcourseResponse']['id']);
					$this->Ecourse->EcourseResponse->set(array('completed' => date('Y-m-d H:i:s'), 'status' => 'completed'));
					if($this->Ecourse->EcourseResponse->save()) {
						$userAssignment = $this->Ecourse->EcourseUser->find('first', array(
							'conditions' => array(
								'EcourseUser.user_id' => $this->Auth->user('id'),
								'EcourseUser.ecourse_id' => $ecourseModule['EcourseModule']['ecourse_id'])));
						if($userAssignment) {
							$this->Ecourse->EcourseUser->delete($userAssignment['EcourseUser']['id']);
						}
					}
					$data = $this->Auth->user();
					$data['Ecourse'] = $ecourseModule['Ecourse'];
					$data['EcourseResponse'] = $ecourseResponse['EcourseResponse'];
					ClassRegistry::init('Queue.QueuedTask')->createJob('document', $data);
					$this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => $userIsAdmin));
				}
			}
			else {
				$this->Transaction->createUserTransaction(
					'Ecourses',
					null,
					null,
					'Failed ecourse module: ' . $ecourseModule['EcourseModule']['name']
				);
				// TODO: add logic to add failing transaction.
				$this->Session->setFlash('You did not pass the quiz, please try again', 'flash_failure');

				$this->set(compact('userAnswers', 'ecourseModule'));
				$this->render('failed_quiz');
			}
		}
		else {
			$title_for_layout = $ecourseModule['EcourseModule']['name'] . ' Quiz';
			$this->set(compact('ecourseModule', 'title_for_layout'));
			$this->render('/ecourses/quiz/');
		}
	}

    public function load_media($mediaLocation=null) {
        $this->view = 'Media';
        if($mediaLocation) {
            $explode = explode('.', $mediaLocation);

			if (preg_match('/pp/i', $explode[1])) {
				$download = true;
			} else {
				$download = false;
			}

            $params = array(
                'id' => $mediaLocation,
				'mimeType' => array('pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation'),
                'name' => $explode[0],
				'download' => $download,
                'extension' => $explode[1],
				'path' => Configure::read('Ecourse.media.path')
            );

            $this->set($params);
			return $params;
        }
		return false;
    }

	public function view_certificate($responseId = null) {
		if (!$responseId) {
			$this->Session->setFlash(__('Invalid Ecourse Response', true), 'flash_failure');
			$this->redirect($this->referer());
		}

		$moduleResponse = $this->Ecourse->EcourseResponse->find('first', array(
			'conditions' => array(
				'EcourseResponse.id' => $responseId
			)
		));
		$docId = Set::extract('/EcourseResponse/cert_id', $moduleResponse);

		if(!$docId) {
			$this->Session->setFlash(__('Document has not been generated just yet. Please try again in a few minutes.', true), 'flash_failure');
			$this->redirect($this->referer());
		}

		$this->view = 'Media';
		$this->loadModel('FiledDocument');

		$doc = $this->FiledDocument->read(null, $docId[0]);
		$params = array(
			'id' => $doc['FiledDocument']['filename'],
			'name' => str_replace('.pdf', '', $doc['FiledDocument']['filename']),
			'extension' => 'pdf',
			'cache' => true,
			'path' => Configure::read('Document.storage.path') .
			date('Y', strtotime($doc['FiledDocument']['created'])) . '/' .
			date('m', strtotime($doc['FiledDocument']['created'])) . '/'
		);

		$this->Transaction->createUserTransaction('Ecourses', null, null,
			'Viewed certificate for ' . $moduleResponse['Ecourse']['name']);
		$this->set($params);
		return $params;
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

	private function logTimeOut($modResponseTimeId) {
		$data['EcourseModuleResponseTime']['id'] = $modResponseTimeId;
		$data['EcourseModuleResponseTime']['time_out'] = date('Y-m-d H:i:s');
		$this->Ecourse->EcourseResponse->EcourseModuleResponse->EcourseModuleResponseTime->save($data);
		return $this->Ecourse->EcourseResponse->EcourseModuleResponse->EcourseModuleResponseTime->getLastInsertId();
	}

	private function logTimeIn($modResponseId, $type) {
		$data['EcourseModuleResponseTime']['ecourse_module_response_id'] = $modResponseId;
		$data['EcourseModuleResponseTime']['type'] = $type;
		$data['EcourseModuleResponseTime']['time_in'] = date("Y-m-d H:i:s");
		$this->Ecourse->EcourseResponse->EcourseModuleResponse->EcourseModuleResponseTime->create();
		$this->Ecourse->EcourseResponse->EcourseModuleResponse->EcourseModuleResponseTime->save($data['EcourseModuleResponseTime']);
		return $this->Ecourse->EcourseResponse->EcourseModuleResponse->EcourseModuleResponseTime->getLastInsertId();
	}
}
