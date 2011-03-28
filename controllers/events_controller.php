<?php
class EventsController extends AppController {
	var $name = 'Events';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view');
	}
	
	function view() {}
	
	function index($dateRange = null) {
		$title_for_layout = 'Calendar of Events';
		
		FireCake::log($this->params);
		FireCake::log($this->passedArgs);
		
		if (!$dateRange) {
			$from = date('Y-m-d H:i:s');
			$to   = date('Y-m-d H:i:s', strtotime('+6 months 23:59:59'));
			$conditions = array('Event.start BETWEEN ? AND ?' => array($from, $to));
			$events = $this->paginate = array('conditions' => $conditions, 'order' => 'Event.start ASC');
		} else {
			
		}
		
		FireCake::log($events);
		
		$this->set(compact('title_for_layout'));
		$this->set('events', $this->paginate());
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
				'all_day' => $params['all_day']
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