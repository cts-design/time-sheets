<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2012
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

class EventsController extends AppController {
	public $name = 'Events';

	public $components = array('Notifications');

	public $helpers = array('Excel');

	public $paginate = array('order' => array('Event.scheduled' => 'asc'), 'limit' => 5);

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view', 'index', 'workshop');
	}

	public function index() {
		$this->Event->Behaviors->attach('Containable');
		$this->Event->EventCategory->recursive = -1;

		$title_for_layout = 'Upcoming Events';
		$selectedCategory = 0;

		$categories = $this->Event->EventCategory->find('list', array(
			'fields' => array(
				'EventCategory.id',
				'EventCategory.name'
			),
			'recursive' => -1
		));
		array_unshift($categories, 'All Categories');

		if (isset($this->params['form']['event_categories_dropdown']) && !empty($this->params['form']['event_categories_dropdown'])) {
			if ($this->params['form']['event_categories_dropdown'] == 0) {
				$selectedCategory = 0;
				$categoryConditions = null;
			} else {
				$categoryConditions = array('Event.event_category_id' => $this->params['form']['event_categories_dropdown']);
				$selectedCategory = $this->params['form']['event_categories_dropdown'];
			}
		} else {
			$categoryConditions = null;
		}

		// setup date stuffs
		$date = $this->parse_date();
		$bow = $this->get_beginning_of_week($date);
		$eow = $this->get_end_of_week($date);
		$time_conditions = $this->get_schedule_time_conditions($date, $bow, $eow);

		// setup for the next/previous buttons
		$nextMonday = date('Y-m-d', strtotime('next monday', strtotime($eow)));
		if (date('w', strtotime($date)) != 1) {
			$thisMonday = date('Y-m-d', strtotime('last monday', strtotime($date)));
			$prevMonday = date('Y-m-d', strtotime('last monday', strtotime($thisMonday)));
		} else {
			$prevMonday = date('Y-m-d', strtotime('last monday', strtotime($date)));
		}

		$conditions = array_merge($time_conditions, array(
			'Event.event_registration_count < Event.seats_available'
		));

		if ($categoryConditions) {
			$conditions = array_merge($conditions, $categoryConditions);
		}

		$events = $this->paginate(
			'Event',
			$conditions
		);

		$userEventRegistrations = array();
		if ($this->Auth->user()) {
			$this->loadModel('User');
			$this->User->recursive = -1;
			$this->User->Behaviors->attach('Containable');
			$this->User->contain('EventRegistration');
			$user = $this->User->findById($this->Auth->user('id'));

			if (isset($user['EventRegistration']) && !empty($user['EventRegistration'])) {
				foreach ($user['EventRegistration'] as $key => $value) {
					array_push($userEventRegistrations, $value['event_id']);
				}
			}
		}

		$this->set(
			compact(
				'title_for_layout',
				'selectedCategory',
				'categories',
				'bow',
				'eow',
				'nextMonday',
				'prevMonday',
				'events',
				'userEventRegistrations'
			)
		);
	}

	public function workshop($month = null, $year = null) {
		$this->Event->Behaviors->attach('Containable');
		$this->Event->EventCategory->recursive = -1;

		$workshopCategory = $this->Event->EventCategory->findByName('Workshop', array('fields' => 'EventCategory.id'));

		$title_for_layout = 'Upcoming Workshops';

		if ($month && !$year) {
			$year = date('Y');
		}

		if (!$month) {
			$date = date('Y-m-d H:i:s');
			$month = date('m', strtotime($date));
			$year = date('Y', strtotime($date));
			$lastDayOfMonth = date('t', strtotime($date));
			$endDate = date('Y-m-d H:i:s', strtotime("$month/$lastDayOfMonth/$year 23:59:59"));
		} else {
			$date = date('Y-m-d H:i:s', strtotime("$month/1/$year 00:00:01"));
			$lastDayOfMonth = date('t', strtotime($date));
			$endDate = date('Y-m-d H:i:s', strtotime("$month/$lastDayOfMonth/$year 23:59:59"));
		}

		$conditions = array(
			'Event.scheduled >' => date('Y-m-d H:i:s'),
			'Event.event_registration_count < Event.seats_available',
		);

		$conditions = array_merge(
			$conditions,
			array(
				'Event.scheduled BETWEEN ? AND ?' => array($date, $endDate)
			)
		);

		$events = $this->paginate(
			'Event',
			$conditions
		);

		$curMonth = date('F Y', strtotime($date));
		$prevMonth = date('m/Y', strtotime("-1 month", strtotime($date)));
		$nextMonth = date('m/Y', strtotime("+1 month", strtotime($date)));

		$userEventRegistrations = array();
		if ($this->Auth->user()) {
			$this->loadModel('User');
			$this->User->recursive = -1;
			$this->User->Behaviors->attach('Containable');
			$this->User->contain('EventRegistration');
			$user = $this->User->findById($this->Auth->user('id'));

			if (isset($user['EventRegistration']) && !empty($user['EventRegistration'])) {
				foreach ($user['EventRegistration'] as $key => $value) {
					array_push($userEventRegistrations, $value['event_id']);
				}
			}
		}

		$this->set(compact('title_for_layout', 'categories', 'prevMonth', 'nextMonth', 'curMonth', 'events', 'userEventRegistrations'));
	}

	public function attend($eventId, $eventType = null) {
		$this->loadModel('User');
		$this->User->recursive = -1;
		$this->User->Behaviors->attach('Containable');
		$this->User->contain('EventRegistration.event_id = ' . $eventId);
		$this->Event->Behaviors->attach('Containable');
		$this->Event->recursive = -1;

		$event = $this->Event->find('first', array(
			'conditions' => array(
				'Event.id' => $eventId
			),
			'contain' => array(
				'Location'
			)
		));
		$user = $this->User->findById($this->Auth->user('id'));

		$redirectAction = ($eventType === 'workshop') ? 'workshop' : 'index';

		if (isset($user['EventRegistration']) && !empty($user['EventRegistration'])) {
			$this->Session->setFlash(__('You are already registered for this event', true), 'flash_failure');
			$this->redirect(array('action' => $redirectAction));
		} else if ($event['Event']['event_registration_count'] >= $event['Event']['seats_available']){
			$this->Session->setFlash(__('We\'re sorry, but this event is full', true), 'flash_failure');
			$this->redirect(array('action' => $redirectAction));
		} else {
			$data = array(
				'user_id' => $this->Auth->user('id'),
				'event_id' => $eventId,
				'present' => 0
			);

			if ($this->Event->EventRegistration->save($data)) {
				$this->Notifications->sendEventRegistrationEmail($event, $user);
				$this->Transaction->createUserTransaction(
					'EventRegistrations',
					null,
					null,
					'Registered for event ID ' . $event['Event']['id']
				);
				$this->Session->setFlash(__('You\'ve successfully registered for this event', true), 'flash_success');
				$this->redirect(array('action' => $redirectAction));
			} else {
				$this->Session->setFlash(__('Something went wrong while registering you for this event. Please try again', true), 'flash_failure');
				$this->redirect(array('action' => $redirectAction));
			}
		}
	}

	public function cancel($eventId, $eventType = null) {
		$this->loadModel('User');
		$this->User->recursive = -1;
		$this->User->Behaviors->attach('Containable');
		$this->User->contain('EventRegistration.event_id = ' . $eventId);
		$this->Event->recursive = -1;

		$event = $this->Event->findById($eventId);
		$user = $this->User->findById($this->Auth->user('id'));

		if (isset($user['EventRegistration']) && !empty($user['EventRegistration'])) {
			$this->Transaction->createUserTransaction(
				'EventRegistrations',
				null,
				null,
				'Cancelled registration for event ID ' . $event['Event']['id']
			);
			$this->Notifications->sendEventCancellationEmail($event, $user);
			$this->Event->EventRegistration->delete($user['EventRegistration'][0]['id']);
			$this->Session->setFlash(__('You\'ve successfully un-registered for this event', true), 'flash_success');
		}

		$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
	}

	public function view() {}

	public function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$date = date('Y-m-d H:i:s', strtotime('today'));
			$events = $this->Event->find('all', array(
				'order' => array('Event.scheduled DESC'),
				'conditions' => array('Event.scheduled >= ' => $date)));
			$this->loadModel('Location');
			$locations = $this->Location->find('list');
			$this->loadModel('DocumentFilingCategory');
			$cats = $this->DocumentFilingCategory->find('list');
			if($events) {
				$i = 0;
				foreach($events as $event) {
					$data['events'][$i] = $event['Event'];
					if($event['Event']['cat_1']) {
						$data['events'][$i]['cat_1'] = $cats[$event['Event']['cat_1']];
					}
					if($event['Event']['cat_2']) {
						$data['events'][$i]['cat_2'] = $cats[$event['Event']['cat_2']];
					}
					if($event['Event']['cat_3']) {
						$data['events'][$i]['cat_3'] = $cats[$event['Event']['cat_3']];
					}
					$data['events'][$i]['category'] = $event['EventCategory']['name'];
					if($locations) {
						if($event['Event']['location_id'] == 0) {
							$data['events'][$i]['location'] = 'Other';
						}
						else {
							$data['events'][$i]['location'] = $locations[$event['Event']['location_id']];
						}
					}
					else {
						$data['events'][$i]['location'] = '';
					}
					$i++;
				}
			}
			else {
				$data['events'] = array();
			}
			$data['success'] = true;
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_view() {
		$eventRegistrations = $this->Event->EventRegistration->findAllByEventId($this->params['pass'][0]);
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

	public function admin_add()	{
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['Event'] = json_decode($this->data['Event'], true);
				unset($this->data['Event']['registered']);
				unset($this->data['Event']['event_registration_count']);
				unset($this->data['Event']['created']);
				unset($this->data['Event']['modified']);
				$this->Event->create();
				if($this->Event->save($this->data)) {
                    $this->Transaction->createUserTransaction(
                        'Events',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        'Added event, id: ' . $this->data['Event']['id']
                    );
					$data['success'] = true;
					$data['message'] = 'The event was saved successfully.';
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to save event here, please try again.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to save event, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_edit()	{
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['Event'] = json_decode($this->data['Event'], true);
				$event = $this->Event->findById($this->data['Event']['id']);
				if($event['Event']['registered']) {
					$data['success'] = false;
					$data['message'] = 'Cannot edit event that already has registrants.';
				}
				else {
					if(!isset($this->data['Event']['cat_2'])) {
						$this->data['Event']['cat_2'] = NULL;
					}
					if(!isset($this->data['Event']['cat_3'])) {
						$this->data['Event']['cat_3'] = NULL;
					}
					if($this->data['Event']['location_id']) {
						$this->data['Event']['other_location'] = NULL;
						$this->data['Event']['address'] = NULL;
					}
					if($this->Event->save($this->data)) {
						$this->Transaction->createUserTransaction(
							'Events',
							$this->Auth->user('id'),
							$this->Auth->user('location_id'),
							'Edited event, id: ' . $this->data['Event']['id']
						);
						$data['success'] = true;
						$data['message'] = 'The event was updated successfully.';
					}
					else {
						$data['success'] = false;
						$data['message'] = 'Unable to update event, please try again.';
					}
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to update event, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_delete() {
        if($this->RequestHandler->isAjax()) {
            if(isset($this->data['Event'])) {
				$this->data['Event'] = json_decode($this->data['Event'], true);
                if($this->Event->delete($this->data['Event']['id'])){
                    $data['success'] = true;
                    $data['message'] = 'Event deleted successfully';
                    $this->Transaction->createUserTransaction(
                        'Events',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        'Deleted event, id: ' . $this->data['Event']['id']
                    );
                }
                else {
                    $data['success'] = false;
                    $data['message'] = 'Unable to delete event at this time.';
                }
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Invalid event id.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

	public function admin_toggle_present() {
		if($this->RequestHandler->isAjax()) {
            if(isset($this->data['EventRegistration'])) {
				$this->data['EventRegistration'] = json_decode($this->data['EventRegistration'], true);
				if($this->Event->EventRegistration->saveAll($this->data['EventRegistration'])) {
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
		$this->Event->recursive = 2;
		if(isset($this->params['url']['id'])) {
			$event = $this->Event->findById($this->params['url']['id']);	
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

	public function admin_archive() {
		if($this->RequestHandler->isAjax()) {
			$this->paginate = array('order' => array('Event.scheduled' => 'Desc'));
			if(isset($this->params['url']['search'])) {
				$params = json_decode($this->params['url']['search'], true);
				if(isset($params['fromDate'], $params['toDate'])){
					$from = date('Y-m-d H:i:m', strtotime($params['fromDate'] . '12:00 AM'));
					$to = date('Y-m-d H:i:m', strtotime($params['toDate'] . '11:59 PM'));
					$conditions['Event.scheduled BETWEEN ? AND ?'] = array($from, $to);
				}
				if(isset($params['location_id'])){
					$conditions['Event.location_id'] = $params['location_id'];
				}	
				if(isset($params['event_category_id'])){
					$conditions['Event.event_category_id'] = $params['event_category_id'];
				}	
				$this->paginate['conditions'] = $conditions;
			}
			$events = $this->Paginate('Event');
			$data['events'] = array();
			if($events) {
				$i = 0;
				foreach($events as $event) {
					$data['events'][$i]['id'] = intval($event['Event']['id']);
					$data['events'][$i]['name'] = $event['Event']['name'];
					$data['events'][$i]['category'] = $event['EventCategory']['name'];
					if(!$event['Event']['location_id']) {
						$data['events'][$i]['location'] = 'Other';
					}
					else {
						$data['events'][$i]['location'] = $event['Location']['name'];
					}
					$data['events'][$i]['scheduled'] = $event['Event']['scheduled'];
					$data['events'][$i]['registered'] = $event['Event']['event_registration_count'];
					$data['events'][$i]['attended'] = $this->Event->EventRegistration->countAttended($event['Event']['id']);
					$i++;
				}	
			}
			$data['totalCount'] = $this->params['paging']['Event']['count'];
			$data['success'] = true;
			$this->set(compact('data'));	
			$this->render(null, null, '/elements/ajaxreturn');
		}		
	}

	public function admin_get_event_category_list() {
	    if ($this->RequestHandler->isAjax()) {
	    	$this->Event->EventCategory->recursive = -1;			
			$categories = $this->Event->EventCategory->find('all',
				array('fileds' => array('EventCategory.id', 'EventCategory.name')));
			foreach($categories as $category) {
			    $data['categories'][] = $category['EventCategory'];
			}
			if(!empty($data)){
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
			}
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');			
	    }
	}

	private function parse_date() {
		// If there isn't a date passed, then use today
		if (isset($this->params['url']['date']) && $this->params['url']['date']) {
			$date = $this->params['url']['date'];
			return date('Y-m-d H:i:s', strtotime($date));
		} else {
			return date('Y-m-d H:i:s');
		}
	}

	private function get_beginning_of_week($date) {
		// if the date passed in is a mondey, pass 'today' to strtotime
		// otherwise it will return the previous monday and give us a
		// two week date range
		if (date('w', strtotime($date)) == 1) {
			return date('Y-m-d H:i:s', strtotime('today', strtotime($date)));
		} else {
			return date('Y-m-d H:i:s', strtotime('this week last monday', strtotime($date)));
		}
	}

	private function get_end_of_week($date) {
		return date('Y-m-d H:i:s', strtotime('this week next sunday +23 hours 59 minutes 59 seconds', strtotime($date)));
	}

	private function get_schedule_time_conditions($date, $beginning_of_week, $end_of_week) {
		return array(
			'AND' => array(
				array('Event.scheduled >=' => $beginning_of_week),
				array('Event.scheduled >' => $date),
				array('Event.scheduled >' => date('Y-m-d H:i:s')),
				array('Event.scheduled <' => $end_of_week)
			)
		);
	}
}
