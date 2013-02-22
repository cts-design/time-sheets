<?php

/**
 * EcourseUsers Controller
 *
 * @package   AtlasV3
 * @author    Daniel Nolan
 * @copyright 2013 Complete Technology Solutions
 */
class EcourseUsersController extends AppController {
	public $name = 'EcourseUsers';

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_index($id=null) {
		if(!$id) {
			$this->Session->setFlash('Invalid ecourse id', 'flash_failure');
			$this->redirect(array('controller' => 'ecourses', 'action' => 'admin_index'));
		}
		$assignments = $this->EcourseUser->find('all', array(
			'conditions' => array(
				'EcourseUser.ecourse_id' => $this->params['pass'][0])));
		$this->set('title_for_layout', $assignments[0]['Ecourse']['name']);
		if($this->RequestHandler->isAjax()) {
			$data['assignments'] = array();
			if($assignments) {
				$i = 0;
				foreach($assignments as $assignment) {
					$data['assignments'][$i]['id'] = $assignment['EcourseUser']['id'];
					$data['assignments'][$i]['firstname'] = $assignment['User']['firstname'];
					$data['assignments'][$i]['lastname'] = $assignment['User']['lastname'];
					$data['assignments'][$i]['ecourse_id'] = $assignment['Ecourse']['id'];
					$data['assignments'][$i]['user_id'] = $assignment['User']['id'];
					if(!empty($assignment['User']['ssn'])) {
						$data['assignments'][$i]['last4'] = substr($assignment['User']['ssn'], -4);
					}
					$i++;
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_assign_customers($id=null) {
		if(!$id) {
			$this->Session->setFlash('Invalid ecourse id.', 'flash_failure');
			$this->redirect(array('controller' => 'ecourses', 'action' => 'index', 'admin' => true));
		}	
		$this->EcourseUser->Ecourse->recursive = -1;
		$ecourse = $this->EcourseUser->Ecourse->findById($id);
		$this->set('title_for_layout', $ecourse['Ecourse']['name']);
		if($this->RequestHandler->isAjax()) {
			$this->data['EcourseUser']['user_id'] = $this->params['form']['user_id'];
			$this->data['EcourseUser']['ecourse_id'] = $this->params['form']['ecourse_id'];
			if($this->EcourseUser->save($this->data)) {
				$data['success'] = true;
				$data['message'] = 'User successfully assigned to ecourse.';
				$this->Transaction->createUserTransaction(
					'Ecourses',
					$this->Auth->user('id'),
					$this->Auth->user('location_id'),
					'Assinged customer id: ' . $this->params['form']['user_id'] . ' to ecourse id: ' . $this->params['form']['ecourse_id']
				);
		   	}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_assign_staff($id=null) {
		if(!$id) {
			$this->Session->setFlash('Invalid ecourse id.', 'flash_failure');
			$this->redirect(array('controller' => 'ecourses', 'action' => 'index', 'admin' => true));
		}	
		$this->EcourseUser->Ecourse->recursive = -1;
		$ecourse = $this->EcourseUser->Ecourse->findById($id);
		$this->set('title_for_layout', $ecourse['Ecourse']['name']);
		if($this->RequestHandler->isAjax()) {
			$this->data['EcourseUser']['user_id'] = $this->params['form']['user_id'];
			$this->data['EcourseUser']['ecourse_id'] = $this->params['form']['ecourse_id'];
			if($this->EcourseUser->save($this->data)) {
				$data['success'] = true;
				$data['message'] = 'User successfully assigned to ecourse.';
				$this->Transaction->createUserTransaction(
					'Ecourses',
					$this->Auth->user('id'),
					$this->Auth->user('location_id'),
					'Assinged staff member id: ' . $this->params['form']['user_id'] . ' to ecourse id: ' . $this->params['form']['ecourse_id']
				);
		   	}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_delete() {
		if($this->RequestHandler->isAjax()) {
			$data['success'] = false;
			if(isset($this->data['EcourseUser'])) {
				$this->data['EcourseUser'] = json_decode($this->data['EcourseUser'], true);
				$ids = Set::Extract('/id', $this->data['EcourseUser']);
				$userIds = Set::Extract('/user_id', $this->data['EcourseUser']);
				$conditions = array('EcourseUser.id' => $ids);
				if($this->EcourseUser->deleteAll($conditions, true, true)) {
					$data['success'] = true;
					$data['message'] = 'Assignments deleted successfully';
					$message = 'Deleted ';
					if(count($ids) > 1) {
						$message .= 'ecourse assignments for users ';
						$ecourseId = $this->data['EcourseUser'][0]['ecourse_id'];
					}
					else {
						$message .= 'ecourse assignment for user ';
						$ecourseId = $this->data['EcourseUser']['ecourse_id'];
					}
					$siteUrl = trim(Router::url('/', true),'/');
					foreach($userIds as $k => $v) {
						$message .= "<a href='$siteUrl/admin/user_transactions/index/$v'>$v</a>,&nbsp;";
					}
                    $this->Transaction->createUserTransaction(
                        'Ecourses',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        $message . ' for ecourse, id: ' . $ecourseId);
				}
			}
			else {
				$data['message'] = 'Invalid assignment ids';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
}
