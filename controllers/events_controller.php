<?php
/**
 * @author Daniel Nolan 
 * @copyright Complete Technology Solutions 2012
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

class EventsController extends AppController {
	var $name = 'Events';
	var $paginate = array('order' => array('Event.start' => 'asc'), 'limit' => 5);
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view', 'index');
	}
	
	function index($month = null, $year = null) {
		$events = $this->Event->find('all', array(
			'conditions' => array(
				'Event.scheduled >' => date('Y-m-d H:i:s'),
				'Event.registered < Event.seats_available'
			)
		));
		debug($events);
		$title_for_layout = 'Calendar of Events';
		$categories = $this->Event->EventCategory->find('list', 
														array('fields' => array('EventCategory.id', 'EventCategory.name'),
														'recursive' => -1));
		array_unshift($categories, 'All Categories');
		
		if (isset($this->params['form']['event_categories_dropdown']) && !empty($this->params['form']['event_categories_dropdown'])) {
			if ($this->params['form']['event_categories_dropdown'] == 0) {
				$categories['selected'] = 0;
				$categoryConditions = null;
			} else {
				$categoryConditions = array('Event.event_category_id' => $this->params['form']['event_categories_dropdown']);
				$categories['selected'] = $this->params['form']['event_categories_dropdown'];	
			}
		} else {
			$categoryConditions = null;
		}

		if ($month && !$year) {
			$year = date('Y');
		}
		
		if (!$month) {
			$date = date('Y-m-d H:i:s');
			$month = date('m', strtotime($date));	
			$lastDayOfMonth = date('t', strtotime($date));
			$year = date('Y', strtotime($date));
			$endDate = date('Y-m-d H:i:s', strtotime("$month/$lastDayOfMonth/$year 23:59:59"));
		} else {
			$date = date('Y-m-d H:i:s', strtotime("$month/1/$year 00:00:01"));
			$lastDayOfMonth = date('t', strtotime($date));
			$endDate = date('Y-m-d H:i:s', strtotime("$month/$lastDayOfMonth/$year 23:59:59"));
		}
		
		$events = $this->paginate('Event', array('Event.start BETWEEN ? AND ?' => array($date, $endDate), $categoryConditions));
		
		$curMonth = date('F Y', strtotime($date));
		$prevMonth = date('m/Y', strtotime("-1 month", strtotime($date)));
		$nextMonth = date('m/Y', strtotime("+1 month", strtotime($date)));
		
		$this->set(compact('title_for_layout', 'categories', 'prevMonth', 'nextMonth', 'curMonth', 'events'));
	}
	
	public function view() {}

	public function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$events = $this->Event->find('all', array('order' => array('Event.scheduled DESC')));
			// TODO add condition to the find statement to exclude the events that have been held already
			$this->loadModel('Location');
			$locations = $this->Location->find('list');	
			$this->loadModel('DocumentFilingCategory');
			$cats = $this->DocumentFilingCategory->find('list');
			if($events) {
				$i = 0;
				foreach($events as $event) {
					$data['events'][$i] = $event['Event'];
					$data['events'][$i]['cat_1'] = $cats[$event['Event']['cat_1']];
					$data['events'][$i]['cat_2'] = $cats[$event['Event']['cat_2']];
					$data['events'][$i]['cat_3'] = $cats[$event['Event']['cat_3']];
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

	public function admin_add()	{
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['Event'] = json_decode($this->data['Event'], true);
				unset($this->data['Event']['registered']);
				unset($this->data['Event']['attended']);
				unset($this->data['Event']['created']);
				unset($this->data['Event']['modified']);
				$this->Event->create();
				if($this->Event->save($this->data)) {
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
					$data['success'] = true;
					$data['message'] = 'The event was updated successfully.';
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to update event, please try again.';
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
}
