<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2013
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

class EventRegistrationsController extends AppController {

	public $name = 'EventRegistrations';

	public $helpers = array('Excel');

	function beforeFilter() {
		parent::beforeFilter();
		if($this->Auth->user()) {
			if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Events/admin_index', '*')) {
				$this->Auth->allow('admin_index', 'admin_edit', 'admin_delete', 'admin_attendance_report', 'admin_register_customer');
			}
			if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Events/admin_archive', '*')) {
				$this->Auth->allow('admin_attendance_report');
			}
		}
	}

	public function admin_index() {
		$eventRegistrations = $this->EventRegistration->findAllByEventId($this->params['pass'][0]);
		if($eventRegistrations) {
			if(date('Y-m-d', strtotime($eventRegistrations[0]['Event']['scheduled'])) < date('Y-m-d')) {
				$this->Session->setFlash('Event has already been held', 'flash_failure');
				$this->redirect('/admin/events');
			}
		}
		if($this->RequestHandler->isAjax()) {
			$data['registrations'] = array();
			if($eventRegistrations) {
				$i = 0;
				foreach($eventRegistrations as $registration) {
					$data['registrations'][$i]['id'] = $registration['EventRegistration']['id'];
					$data['registrations'][$i]['user_id'] = $registration['User']['id'];
					$data['registrations'][$i]['firstname'] = $registration['User']['firstname'];
					$data['registrations'][$i]['lastname'] = $registration['User']['lastname'];
					$data['registrations'][$i]['last4'] = substr($registration['User']['ssn'], -4);
					$data['registrations'][$i]['registered'] = $registration['EventRegistration']['created'];
					$data['registrations'][$i]['present'] = $registration['EventRegistration']['present'];
					$i++;
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
		$title_for_layout = 'Event';
		if($eventRegistrations) {
			$title_for_layout = 'Event - ' . $eventRegistrations[0]['Event']['name']; 
		}
		$this->set(compact('title_for_layout'));	
	}

	public function admin_edit() {
		if($this->RequestHandler->isAjax()) {
            if(isset($this->data['EventRegistration'])) {
				$this->data['EventRegistration'] = json_decode($this->data['EventRegistration'], true);
				if($this->EventRegistration->saveAll($this->data['EventRegistration'])) {
					$eventId = $this->data['Event']['id'];
					$userIds = Set::Extract('/user_id', $this->data['EventRegistration']);
					$present = Set::Extract('/present', $this->data['EventRegistration']);
					$siteUrl = trim(Router::url('/', true),'/');
					if(count($userIds) > 1) {
						$message = 'Marked users ';
					}
					else {
						$message = 'Marked user ';
					}
					foreach($userIds as $k => $v) {
						$message .= "<a href='$siteUrl/admin/user_transactions/index/$v'>$v</a>,&nbsp;";
					}
					if($present[0]) {
						$message .= "present for event id: $eventId";
					}
					else {
						$message .= "absent for event id: $eventId";
					}
                    $this->Transaction->createUserTransaction(
                        'Events',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
						$message
					);
					$data['success'] = true;
					$data['message'] = 'Attendance was updated.';
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to update attendance at this time.';
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_delete() {
		if($this->RequestHandler->isAjax()) {
            if(isset($this->data['EventRegistration'])) {
				$this->data['EventRegistration'] = json_decode($this->data['EventRegistration'], true);
				$userIds = Set::Extract('/user_id', $this->data['EventRegistration']);
				$ids = Set::Extract('/id', $this->data['EventRegistration']);
				$conditions = array('EventRegistration.id' => $ids);
				if($this->EventRegistration->deleteAll($conditions, true, true)) {
					$data['success'] = true;
					$data['message'] = 'Deleted successfully';
					$message = 'Deleted ';
					if(count($ids) > 1) {
						$message .= 'registrations for users ';
					}
					else {
						$message .= 'registration for user ';
					}
					$siteUrl = trim(Router::url('/', true),'/');
					foreach($userIds as $k => $v) {
						$message .= "<a href='$siteUrl/admin/user_transactions/index/$v'>$v</a>,&nbsp;";
					}
                    $this->Transaction->createUserTransaction(
                        'Events',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        $message . ' for event, id: ' . $this->data['Event']['id']
					);
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to delete';
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_attendance_report() {
		$this->EventRegistration->Event->recursive = 2;
		if(isset($this->params['url']['id'])) {
			$event = $this->EventRegistration->Event->findById($this->params['url']['id']);	
		}
		$report = array();
		$title = 'Event Attendance Report ';
		if($event) {
			$title .= 'for ' . $event['Event']['name'] . ' held on ' .
				date('m/d/y h:i a', strtotime($event['Event']['scheduled'])) . ' at ' . $event['Location']['name']; 

			if(!empty($event['EventRegistration'])) {
				foreach($event['EventRegistration'] as $k => $v) {
					$report[$k]['First Name'] = $v['User']['firstname'];
					$report[$k]['Last Name'] = $v['User']['lastname'];
					$report[$k]['Last 4 SSN'] = substr($v['User']['ssn'], -4);
					$report[$k]['Registered'] = date('m/d/y', strtotime($v['created']));
					$report[$k]['Attended'] = ($v['present']) ? 'Yes' : 'No';
				}
			}
		}

		$this->Transaction->createUserTransaction(
			'Events',
			$this->Auth->user('id'),
			$this->Auth->user('location_id'),
			'Ran attendance report for event, id: ' . $event['Event']['id']
		);
		$data = array('data' => $report,
			'title' => $title);
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$this->set($data);
	}

	public function admin_register_customer() {
		if($this->RequestHandler->isAjax()) {
			$count = $this->EventRegistration->find('count', array(
				'conditions' => array(
					'EventRegistration.user_id' => $this->params['form']['user_id'],
					'EventRegistration.event_id' => $this->params['form']['event_id'])));
			$this->data['EventRegistration']['user_id'] = $this->params['form']['user_id'];
			$this->data['EventRegistration']['event_id'] = $this->params['form']['event_id'];
			if($count == 0 && $this->EventRegistration->save($this->data)) {
				$data['success'] = true;
				$data['message'] = 'Customer was registered successfully.';
				$this->Transaction->createUserTransaction(
					'Events',
					$this->Auth->user('id'),
					$this->Auth->user('location_id'),
					'Assigned user id: ' . $this->params['form']['user_id'] . ' to event id: ' . $this->params['form']['event_id']
				);
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to register customer at this time, or customer is already registered';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
}
