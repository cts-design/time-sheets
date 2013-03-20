<?php

class EcourseResponsesController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_index($id = null) {
		if($id){
			$this->EcourseResponse->Ecourse->recursive = -1;
			$ecourse = $this->EcourseResponse->Ecourse->findById($id);
			if($this->RequestHandler->isAjax()){
				$conditions = array('EcourseResponse.ecourse_id' => $id);
				if(!empty($this->params['url']['fromDate']) && !empty($this->params['url']['toDate'])) {
					$from = date('Y-m-d H:i:m', strtotime($this->params['url']['fromDate'] . '12:00 AM'));
					$to = date('Y-m-d H:i:m', strtotime($this->params['url']['toDate'] . '11:59 PM'));
					if($this->params['url']['status'] === 'incomplete') {
						$conditions['EcourseResponse.created BETWEEN ? AND ?'] = array($from, $to);
					} 
					else {
						$conditions['EcourseResponse.completed BETWEEN ? AND ?'] = array($from, $to);
					}
				}
				if(!empty($this->params['url']['id'])) {
					$conditions['EcourseResponse.id'] = $this->params['url']['id'];
				}
				if(!empty($this->params['url']['searchType']) && !empty($this->params['url']['search'])) {
					switch($this->params['url']['searchType']) {
						case 'firstname' :
							$conditions['User.firstname LIKE'] = '%' .
								$this->params['url']['search'] . '%';
							break;
						case 'lastname' :
							$conditions['User.lastname LIKE'] = '%' .
								$this->params['url']['search'] . '%';
							break;
						case 'last4' :
							$conditions['RIGHT (User.ssn , 4) LIKE'] = '%' .
								$this->params['url']['search'] . '%';
							break;
						case 'fullssn' :
							$conditions['User.ssn LIKE'] = '%' . $this->params['url']['search'] . '%';
							break;
					}
				}
				if(!empty($this->params['url']['status'])) {
					$conditions['EcourseResponse.status'] = $this->params['url']['status'];
				}

				$this->paginate = array('conditions' => $conditions);
				$responses = $this->Paginate('EcourseResponse');
				$data['totalCount'] = $this->params['paging']['EcourseResponse']['count'];
				if($responses) {
					$i = 0;
					foreach($responses as $response) {
						$data['responses'][$i] = array(
							'id' => $response['EcourseResponse']['id'],
							'User-lastname' => trim(ucwords($response['User']['lastname'] . ', ' .
								$response['User']['firstname']) . ' - ' .
								substr($response['User']['ssn'], -4), ' , -'),
							'created' => $response['EcourseResponse']['created'],
							'modified' => $response['EcourseResponse']['modified'],
							'status' => $response['EcourseResponse']['status']
						);
						$data['responses'][$i]['actions'] =
							'<a href="/admin/ecourse_responses/view/'.
								$response['EcourseResponse']['id'].'">View</a>';
						$i++;
					}
				}
				else {
					$data['responses'] = array();
					$data['message'] = 'No results at this time.';
				}
				$data['success'] = true;
				$this->set('data', $data);
				$this->render('/elements/ajaxreturn');
			}
			$ecourseName = $ecourse['Ecourse']['name'];
			$this->set(compact('approvalPermission', 'ecourseName', 'ecourseType'));
		}
	
	}

	public function admin_view() {
		$this->EcourseResponse->recursive = -1;
				
		$options['conditions'] = array('EcourseResponse.id' => $this->params['pass'][0]);
		$options['contain'] = array('User', 'Ecourse', 'EcourseModuleResponse' => array('EcourseModule', 'EcourseModuleResponseTime'));
		$response = $this->EcourseResponse->find('first', $options);
		if($this->RequestHandler->isAjax()) {
			if($response) {
				$i = 0;
				foreach($response['EcourseModuleResponse'] as $moduleResponse) {
					$data['response'][$i]['id'] = $moduleResponse['id'];
					$data['response'][$i]['module'] = $moduleResponse['EcourseModule']['name'];
					$data['response'][$i]['score'] = $moduleResponse['score'];
					$data['response'][$i]['pass_fail'] = $moduleResponse['pass_fail'];
					$data['response'][$i]['time_on_quiz'] =
						$this->EcourseResponse->EcourseModuleResponse->EcourseModuleResponseTime->getTimeSpent($moduleResponse['id'], 'quiz');
					$data['response'][$i]['time_on_media'] = 
						$this->EcourseResponse->EcourseModuleResponse->EcourseModuleResponseTime->getTimeSpent($moduleResponse['id'], 'media');
					$data['response'][$i]['total_time'] = $data['response'][$i]['time_on_media'] + $data['response'][$i]['time_on_quiz'];
					$i++;
				} 
				$data['success'] = true;
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');

		}
		$userName = $response['User']['firstname'] . ' ' . $response['User']['lastname'];
		$ecourseName = $response['Ecourse']['name'];
		$this->set(compact('ecourseName', 'ecourseId', 'userName'));
	}

	public function admin_reset() {
		if($this->RequestHandler->isAjax()) {
			$ecourseResponse = $this->EcourseResponse->find('first', array(
				'conditions' => array(
					'EcourseResponse.id' => $this->params['form']['id'],
					'EcourseResponse.reset !=' => 1),
				'contain' => array('Ecourse')));
			$data['EcourseResponse']['id'] = $this->params['form']['id'];
			$data['EcourseResponse']['reset'] = 1;
			if($this->EcourseResponse->save($data)) {
				if($ecourseResponse['Ecourse']['requires_user_assignment']) {
					$data['EcourseUser']['user_id'] = $ecourseResponse['EcourseResponse']['user_id'];
					$data['EcourseUser']['ecourse_id'] = $ecourseResponse['Ecourse']['id'];
					$this->EcourseResponse->Ecourse->EcourseUser->save($data);
				}
				$data['success'] = true;
				$data['message'] = 'Response was reset successfully';
					// TODO: add user activity transaction
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to reset response at this time';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
}
