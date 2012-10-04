<?php
class EventsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index() {
		$events = $this->Event->find('all', array(
			'conditions' => array(
				'Event.scheduled >' => date('Y-m-d H:i:s'),
				'Event.registered < Event.seats_available'
			)
		));
		debug($events);
	}

	public function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$events = $this->Event->find('all', array('order' => array('Event.scheduled DESC')));
			$this->loadModel('Location');
			$locations = $this->Location->find('list');	
			$this->loadModel('DocumentFilingCategory');
			$cats = $this->DocumentFilingCategory->find('list');
			if($events) {
				$i = 0;
				foreach($events as $event) {
					$data['events'][$i]['id'] = $event['Event']['id'];
					$data['events'][$i]['name'] = $event['Event']['name'];
					$data['events'][$i]['description'] = $event['Event']['description'];
					$data['events'][$i]['scheduled'] = $event['Event']['scheduled'];
					$data['events'][$i]['seats_available'] = $event['Event']['seats_available'];
					$data['events'][$i]['registered'] = $event['Event']['registered'];
					$data['events'][$i]['attended'] = $event['Event']['attended'];
					// TODO add if to make sure cats are set
					$data['events'][$i]['cat_1'] = $cats[$event['Event']['cat_1']];
					$data['events'][$i]['cat_2'] = $cats[$event['Event']['cat_2']];
					$data['events'][$i]['cat_3'] = $cats[$event['Event']['cat_3']];
					if($locations) {
						$data['events'][$i]['location'] = $locations[$event['Event']['location_id']];
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
				$this->data['Event']['scheduled'] = date("Y-m-d H:i:s", strtotime('10/09/2012 12:30 am'));
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
				$this->data['Event']['scheduled'] = date("Y-m-d H:i:s", strtotime('10/09/2012 12:30 am'));
				$this->log($this->data, 'debug');
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
