<?php
class EventsController extends AppController {
	var $name = 'Events';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view');
	}
	
	function view() {}
	
	function admin_index() {}
	function admin_view() {}
	function admin_edit() {}
	function admin_delete() {}
	
	function admin_get_all_events() {
		App::import('Vendor', 'DebugKit.FireCake');
		FireCake::log($this->params);
		$this->Event->recursive = '-1';
		$params = $this->params['form'];
		$useDate = null;
		
		FireCake::log($this->params);
		
		switch ($params['xaction']) {
			case 'create':
				
				break;
			case 'read':
				if (!empty($params['start']) && !empty($params['end'])) {
					$startDate = date('Y-m-d H:i:s', strtotime($params['start'] . ' 00:00:01'));
					$endDate = date('Y-m-d H:i:s', strtotime($params['end'] . ' 23:59:59'));
					$useDate = true;
				}
				
				if ($useDate) {
					$conditions = array('Event.start BETWEEN ? AND ?' => array($startDate, $endDate));
					$allEvents = $this->Event->find('all', array('conditions' => $conditions));
				} else {
					$allEvents = $this->Event->find('all');
				}
				
				if (count($allEvents) > 0) {
					$events = array('total_events' => count($allEvents));
					
					foreach ($allEvents as $key => $value) {
						foreach ($value as $k => $v) {
							$v['start'] = date('D M d o h:i:s A', strtotime($v['start']));
							$v['end'] = date('D M d o h:i:s A', strtotime($v['end']));
							$events['events'][] = $v;
						}
					}
					
					$events['success'] = true;
				} else {
					$events['success'] = false;
				}
				
				break;
			
			case 'update':
				$event = json_decode($params['events'], true);
				$this->data = array();
				
				foreach ($event as $key => $value) {
					$this->data['Event'][$key] = $value;
				}
				
				FireCake::log($this->Event->save($this->data));
				
				if ($this->Event->save($this->data)) {
					$events['success'] = true;
				} else {
					$events['success'] = false;
					$events['message'] = 'Could not update the event record';
				}
				
				break;
		}

		$this->set(compact('events'));
	}
}
?>