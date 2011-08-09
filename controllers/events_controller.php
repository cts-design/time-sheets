<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
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
	
	function view() {}
	
	function index($month = null, $year = null) {
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
	
	function admin_index() {
	}

	function admin_create() {
		$params = json_decode($this->params['form']['events'], true);
		$this->log($params, 'debug');

		$this->data = array(
			'Event' => array(
				'event_category_id' => $params['event_category_id'],
				'title' => $params['title'],
				'start' => date('Y-m-d H:i:s', strtotime($params['start'])),
				'end' => date('Y-m-d H:i:s', strtotime($params['end'])),
                'all_day' => $params['all_day'],
                'description' => (isset($params['description'])) ? $params['description'] : '',
                'location' => (isset($params['location'])) ? $params['location'] : '',
                'address' => (isset($params['address'])) ? $params['address'] : '',
                'sponsor' => (isset($params['sponsor'])) ? $params['sponsor'] : '',
                'sponsor_url' => (isset($params['sponsor_url'])) ? $params['sponsor_url'] : '',
                'url' => (isset($params['url'])) ? $params['url'] : '',
			)
		);

		$this->Event->create();
		$event = $this->Event->save($this->data);

		if ($event) {
            $this->Transaction->createUserTransaction('Events', null, null,
                    'Created event ID ' . $this->Event->id);
			$newEvent['success'] = true;
			$newEvent['events'] = $event['Event'];
			$newEvent['events']['id'] = $this->Event->getLastInsertId();	
		}

		$this->set('data', $newEvent);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
			
	function admin_read() {
		$this->Event->recursive = -1;
		$params = $this->params['url'];
		$conditions = null;

		if (!empty($params['start']) && 1==0 && !empty($params['end'])) {
			$params['start'] = $params['start'] . ' 00:00:01';
			$params['end'] = $params['end'] . ' 23:59:59';
			$start = date('Y-m-d H:i:s', strtotime($params['start']));
			$end = date('Y-m-d H:i:s', strtotime($params['end']));
			$conditions = array('Event.start BETWEEN ? and ?' => array($start, $end));
		}

		$events = $this->Event->find('all', array('conditions' => $conditions));

		foreach ($events as $key => $value) {
			foreach ($value as $k => $v) {
				$allEvents['events'][] = $v;
			}
		}
		$this->set('data', $allEvents);
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	function admin_update() {
		$params = $this->params['form'];
		$event = json_decode($params['events'], true);
		$this->data = array();
		
		foreach ($event as $key => $value) {
			$this->data['Event'][$key] = $value;
		}
		
		$updatedEvent = $this->Event->save($this->data);
		
		if ($updatedEvent) {
            $this->Transaction->createUserTransaction('Events', null, null,
                    'Updated event ID ' . $this->Event->id);
			$events['success'] = true;
		} else {
			$events['success'] = false;
			$events['message'] = 'Could not update the event record';
		}

		$this->set('data', $events);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
	
	function admin_destroy() {
		$params = $this->params['form'];
		$event = json_decode($params['events'], true);
		
		if ($this->Event->delete($event['id'])) {
			$events['success'] = true;
			$this->Transaction->createUserTransaction('Events', null, null,
                                        'Deleted event ID ' . $event['id']);
		} else {
			$events['success'] = false;
		}
		
		$this->set('data', $events);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
}
?>
