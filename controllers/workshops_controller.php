<?php
class WorkshopsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function index() {
		$workshops = $this->Workshop->find('all', array(
			'conditions' => array(
				'Workshop.scheduled >' => date('Y-m-d H:i:s'),
				'Workshop.registered < Workshop.seats_available'
			)
		));
		debug($workshops);
	}

	public function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$workshops = $this->Workshop->find('all', array('order' => array('Workshop.scheduled DESC')));
			$this->loadModel('Location');
			$locations = $this->Location->find('list');	
			$this->loadModel('DocumentFilingCategory');
			$cats = $this->DocumentFilingCategory->find('list');
			if($workshops) {
				$i = 0;
				foreach($workshops as $workshop) {
					$data['workshops'][$i]['id'] = $workshop['Workshop']['id'];
					$data['workshops'][$i]['name'] = $workshop['Workshop']['name'];
					$data['workshops'][$i]['description'] = $workshop['Workshop']['description'];
					$data['workshops'][$i]['scheduled'] = $workshop['Workshop']['scheduled'];
					$data['workshops'][$i]['seats_available'] = $workshop['Workshop']['seats_available'];
					$data['workshops'][$i]['registered'] = $workshop['Workshop']['registered'];
					$data['workshops'][$i]['attended'] = $workshop['Workshop']['attended'];
					// TODO add if to make sure cats are set
					$data['workshops'][$i]['cat_1'] = $cats[$workshop['Workshop']['cat_1']];
					$data['workshops'][$i]['cat_2'] = $cats[$workshop['Workshop']['cat_2']];
					$data['workshops'][$i]['cat_3'] = $cats[$workshop['Workshop']['cat_3']];
					if($locations) {
						$data['workshops'][$i]['location'] = $locations[$workshop['Workshop']['location_id']];
					}
					else {
						$data['workshops'][$i]['location'] = '';
					}
					$i++;
				}
			}
			else {
				$data['workshops'] = array();
			}
			$data['success'] = true;
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_add()	{
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['Workshop'] = json_decode($this->data['Workshop'], true);
				unset($this->data['Workshop']['registered']);
				unset($this->data['Workshop']['attended']);
				unset($this->data['Workshop']['created']);
				unset($this->data['Workshop']['modified']);
				$this->data['Workshop']['scheduled'] = date("Y-m-d H:i:s", strtotime('10/09/2012 12:30 am'));
				$this->Workshop->create();
				if($this->Workshop->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'The workshop was saved successfully.';
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to save workshop here, please try again.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to save workshop, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_edit()	{
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['Workshop'] = json_decode($this->data['Workshop'], true);
				$this->data['Workshop']['scheduled'] = date("Y-m-d H:i:s", strtotime('10/09/2012 12:30 am'));
				$this->log($this->data, 'debug');
				if($this->Workshop->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'The workshop was updated successfully.';
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to update workshop, please try again.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to update workshop, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
}
