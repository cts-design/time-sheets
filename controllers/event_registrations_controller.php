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

	public function admin_index() {
		$eventRegistrations = $this->EventRegistration->findAllByEventId($this->params['pass'][0]);
		if($this->RequestHandler->isAjax()) {
			$data['registrations'] = array();
			if($eventRegistrations) {
				$i = 0;
				foreach($eventRegistrations as $registration) {
					$data['registrations'][$i]['id'] = $registration['EventRegistration']['id'];
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

	public function admin_toggle_present() {
		if($this->RequestHandler->isAjax()) {
            if(isset($this->data['EventRegistration'])) {
				$this->data['EventRegistration'] = json_decode($this->data['EventRegistration'], true);
				if($this->EventRegistration->saveAll($this->data['EventRegistration'])) {
                    $this->Transaction->createUserTransaction(
                        'Events',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        'Performed attendance for event, id: ' . $this->data['Event']['id']
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

	public function admin_delete_registration() {
		// TODO: add ability for admins to delete registrations
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
}
