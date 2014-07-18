<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class SelfSignLogArchivesController extends AppController {

	var $name = 'SelfSignLogArchives';
	var $helpers = array('Excel');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;
	}

	function admin_index() {
		$this->SelfSignLogArchive->recursive = 0;
		if(!empty($this->data['SelfSignLogArchive']['search'])) {
			$conditions = $this->_setConditions();
			$locations = '';
			foreach($this->data['SelfSignLogArchive'] as $k => $v) {
				if($k == 'date_from' || $k == 'date_to' && !empty($v)) {
					$this->passedArgs[$k] = date('Y-m-d', strtotime($v));
				}
				if($k == 'locations' && $v != '') {
					foreach($v as $key => $value) {
						$locations .= $value . ',';
					}
				} elseif($k != 'locations' && $k != 'date_from' && $k != 'date_to') {
					$this->passedArgs[$k] = $v;
				}
			}
			$this->passedArgs['locations'] = trim($locations, ',');
		}
		if(!empty($this->passedArgs['locations'])) {
			$conditions['SelfSignLogArchive.location_id'] = explode(',', $this->passedArgs['locations']);
		}
		if(!empty($this->passedArgs['button_1'])) {
			$conditions['SelfSignLogArchive.level_1'] = $this->passedArgs['button_1'];
		}
		if(!empty($this->passedArgs['button_2'])) {
			$conditions['SelfSignLogArchive.level_2'] = $this->passedArgs['button_2'];
		}
		if(!empty($this->passedArgs['button_3'])) {
			$conditions['SelfSignLogArchive.level_3'] = $this->passedArgs['button_3'];
		}
		if(isset($this->passedArgs['status']) && $this->passedArgs['status'] != null) {
			$conditions['SelfSignLogArchive.status'] = $this->passedArgs['status'];
		}
		if(!empty($this->passedArgs['date_from']) && !empty($this->passedArgs['date_to'])) {
			$from = date('Y-m-d H:i:m', strtotime($this->passedArgs['date_from'] . " 12:00 am"));
			$to = date('Y-m-d H:i:m', strtotime($this->passedArgs['date_to'] . " 11:59 pm"));
			$conditions['SelfSignLogArchive.created BETWEEN ? AND ?'] = array($from,
				$to);
		}
		if(isset($conditions)) {
			$this->paginate = array('conditions' => $conditions,
				'limit' => Configure::read('Pagination.selfSignLogArchive.limit'),
				'order' => array('SelfSignLogArchive.id' => 'desc'));
		}
		else {
			$this->paginate = array('limit' => Configure::read('Pagination.selfSignLogArchive.limit'),
				'order' => array('SelfSignLogArchive.id' => 'desc'));
		}
		$selfSignLogArchives = $this->paginate('SelfSignLogArchive');
		$masterButtonList = $this->SelfSignLogArchive->Kiosk->KioskButton->MasterKioskButton->find('list', array(
			'fields' => array('MasterKioskButton.name')
		));
		$data = array('title_for_layout' => 'Self Sign Archives',
			'selfSignLogArchives' => $selfSignLogArchives,
			'masterButtonList' => $this->SelfSignLogArchive->Kiosk->KioskButton->MasterKioskButton->find('list', array(
				'fields' => array('MasterKioskButton.name')
			)),
			'statuses' => array('0' => 'Open',
				'1' => 'Closed', '2' => 'Not Helped'),
			'locations' => $this->SelfSignLogArchive->Kiosk->Location->find('list'));
		$this->set($data);
		if($this->RequestHandler->isAjax()) {
			$this->render('/elements/self_sign_log_archives/index_table');
		}
	}

	function admin_report()
	{
		if(!empty($this->data['SelfSignLogArchive']['locations']) && $this->data['SelfSignLogArchive']['locations'] != 'null')
		{
			$this->data['SelfSignLogArchive']['locations'] = explode(',', $this->data['SelfSignLogArchive']['locations']);
		}

		if(!empty($this->data['SelfSignLogArchive']['search']))
		{
			$conditions = $this->_setConditions($this->data);
		}
		$this->SelfSignLogArchive->recursive = 0;

		if(isset($conditions))
		{

			$count = $this->SelfSignLogArchive->find('count', array('conditions' => $conditions));

			if($count <= 20000)
			{
				$data = $this->SelfSignLogArchive->find('all', array('conditions' => $conditions));
			}
			else
			{
				$this->Session->setFlash(__('Your results exceed 20000 records, please filter results further.', true), 'flash_failure');
				$this->redirect( array('action' => 'index'));
			}
		}
		else
		{
			$this->Session->setFlash(__('You must filter results to generate a report.', true), 'flash_failure');
			$this->redirect( array('action' => 'index'));
		}

		$statuses 	= array('Open', 'Closed', 'Not Helped');
		$locations 	= $this->SelfSignLogArchive->Kiosk->Location->find('list');
		$buttons 	= $this->SelfSignLogArchive->Kiosk->KioskButton->MasterKioskButton->find('list', 
			array(
				'fields' => array('MasterKioskButton.name')
			)
		);
		
		$closedInTimes = array();

		foreach($data as $k => $v)
		{
			$report[$k]['Last 4 SSN'] = substr($v['User']['ssn'], -4);
			$report[$k]['First Name'] = $v['User']['firstname'];
			$report[$k]['Last Name'] = $v['User']['lastname'];
			if($this->data['SelfSignLogArchive']['include_user_info']) {
				$report[$k]['Middle Initial'] = $v['User']['middle_initial'];
				$report[$k]['Surname'] = $v['User']['surname'];
				$report[$k]['Address'] = $v['User']['address_1'];
				$report[$k]['City'] = $v['User']['city'];
				$report[$k]['County'] = $v['User']['county'];
				$report[$k]['State'] = $v['User']['state'];
				$report[$k]['Zip'] = $v['User']['zip'];
				$report[$k]['Phone'] = $v['User']['phone'];
				$report[$k]['Gender'] = $v['User']['gender'];
				$report[$k]['DOB'] = $v['User']['dob'];
				$report[$k]['Language'] = $v['User']['language'];
				$report[$k]['Ethnicity'] = $v['User']['ethnicity'];
				$report[$k]['Race'] = $v['User']['race'];
				$report[$k]['Veteran'] = ($v['User']['veteran']) ? 'Yes' : 'No';
				$report[$k]['Email'] = $v['User']['email'];
			}
			$report[$k]['Location'] = $v['Location']['name'];
			if(!empty($v['SelfSignLogArchive']['level_1'])) {
				$report[$k]['Button 1'] = $buttons[$v['SelfSignLogArchive']['level_1']];
			}
			else
				$report[$k]['Button 1'] = '';

			if(!empty($v['SelfSignLogArchive']['level_2'])) {
				$report[$k]['Button 2'] = $buttons[$v['SelfSignLogArchive']['level_2']];
			}
			else
				$report[$k]['Button 2'] = '';

			if(!empty($v['SelfSignLogArchive']['level_3'])) {
				$report[$k]['Button 3'] = $buttons[$v['SelfSignLogArchive']['level_3']];
			}
			else
				$report[$k]['Button 3'] = '';

			$report[$k]['Other'] = $v['SelfSignLogArchive']['other'];
			if(!empty($v['Admin']['lastname'])) {
				$report[$k]['Last Activity Admin'] = 
					$v['Admin']['lastname'] . ', ' . $v['Admin']['firstname'];				
			}
			else {
				$report[$k]['Last Activity Admin'] = '';
			}
			$report[$k]['Status'] = $statuses[$v['SelfSignLogArchive']['status']];
			$report[$k]['Created'] = $v['SelfSignLogArchive']['created'];
			$report[$k]['Closed'] = $v['SelfSignLogArchive']['closed'];
			$report[$k]['Closed In'] = $v['SelfSignLogArchive']['closed_in'];
			
			// get the time to close in seconds
			if ($v['SelfSignLogArchive']['closed_in']) {
    			$created = strtotime(str_replace("-", "", $v['SelfSignLogArchive']['created']));
    			$closed  = strtotime(str_replace("-", "", $v['SelfSignLogArchive']['closed']));
                $closedInTimes[] = $closed - $created;
			}
		}
		
		$averageTimeInSeconds = $this->_calculateAverageTimeInSeconds($closedInTimes);
		$average = $this->SelfSignLogArchive->_time_duration($averageTimeInSeconds);
		if(isset($report)) {
			 $report[(count($report) + 3)] = array(
				'Name' => '',
				'Location' => '',
				'Button 1' => '',
				'Button 2' => '',
				'Button 3' => '',
				'Other' => '',
				'Last Activity Admin' => '',
				'Status' => '',
				'Created' => '',
				'Closed' => '',
				'Closed In' => "Average close time: {$average}"
			);
		}
		
		if(empty($report[0])) {
		    $this->Session->setFlash(__('There are no results to generate a report', true), 'flash_failure');
		    $this->redirect(array('action' => 'index'));
		}
		$title = "Self Sign Report for ";

		if(empty($this->data['SelfSignLogArchive']['locations']) || $this->data['SelfSignLogArchive']['locations'] == 'null') {
			$title .= 'All Locations' . ' ' . date('m-d-Y');
		}
		else {
			foreach($this->data['SelfSignLogArchive']['locations'] as $k => $v) {
				$title .= $locations[$v] . ', ';
			}
			$title .= ' ' . date('m-d-Y');
		}

		$data = array('data' => $report,
			'title' => $title);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->set($data);
	}

	function admin_get_parent_buttons_ajax() {
		if($this->RequestHandler->isAjax()) {
			$masterButtonList = $this->SelfSignLogArchive->Kiosk->KioskButton->MasterKioskButton->find('list', array(
				'fields' => array('MasterKioskButton.name')
			));
			if(!empty($this->params['url']['ids']) && $this->params['url']['ids'] != 'null') {
				$conditions = array('SelfSignLogArchive.location_id' => $this->params['url']['ids'],
					'SelfSignLogArchive.level_1 !=' => null);
			}
			else {
				$conditions = array('SelfSignLogArchive.level_1 !=' => null);
			}
			$buttons = null;
			$buttonList = $this->SelfSignLogArchive->find('list', array(
				'fields' => array('SelfSignLogArchive.id', 'SelfSignLogArchive.level_1'),
				'conditions' => $conditions,
				'group' => 'level_1'));
			if(isset($buttonList)) {
				foreach($buttonList as $k => $v) {
					if($v) {
						$buttons[$v] = $masterButtonList[$v];
					}			
				}
				if($buttons) {
					asort($buttons);
					$this->set('buttons', $buttons);
				}
			}
			$this->render('admin_get_buttons_ajax');
		}
	}

	function admin_get_child_buttons_ajax() {
		if($this->RequestHandler->isAjax()) {
			$masterButtonList = $this->SelfSignLogArchive->Kiosk->KioskButton->MasterKioskButton->find('list', array(
				'fields' => array('MasterKioskButton.name')
			));
			if(empty($this->params['url']['location'])) {
				$conditions = array('SelfSignLogArchive.level_2 !=' => null);
			} elseif(!empty($this->params['url']['id']) && !empty($this->params['url']['location'])) {
				$conditions = array('SelfSignLogArchive.location_id' => $this->params['url']['location'],
					'SelfSignLogArchive.level_2 !=' => null);
			}
			$possibleButtons = $this->SelfSignLogArchive->find('list', array('fields' => array('id',
				'level_2'),
				'conditions' => $conditions,
				'group' => 'level_2'));
			if(isset($possibleButtons)) {
				$possibleMasterButtons = 
					$this->SelfSignLogArchive->Kiosk->KioskButton->MasterKioskButton->find('list', array(
					'conditions' => array('MasterKioskButton.parent_id' => $this->params['url']['id']),
					'fields' => array('id', 'name')));
				foreach($possibleButtons as $k => $v) {
					if($v && array_key_exists($v, $possibleMasterButtons)) {
						$buttonList[$v] = $masterButtonList[$v];
					}
				}
			}
			if(isset($buttonList)) {
				asort($buttonList);
				$this->set('buttons', $buttonList);
			}
			$this->render('admin_get_buttons_ajax');
		}
	}

	function admin_get_grand_child_buttons_ajax() {
		if($this->RequestHandler->isAjax()) {
			$masterButtonList = $this->SelfSignLogArchive->Kiosk->KioskButton->MasterKioskButton->find('list', array(
				'fields' => array('MasterKioskButton.name')
			));
			if(empty($this->params['url']['location'])) {
				$conditions = array('SelfSignLogArchive.level_3 !=' => null);
			} elseif(!empty($this->params['url']['id']) && !empty($this->params['url']['location'])) {
				$conditions = array('SelfSignLogArchive.location_id' => $this->params['url']['location'],
					'SelfSignLogArchive.level_3 !=' => null);
			}
			$possibleButtons = $this->SelfSignLogArchive->find('list', array('fields' => array('id',
				'level_3'),
				'conditions' => $conditions,
				'group' => 'level_3'));
			if(isset($possibleButtons)) {
				$possibleMasterButtons = 
					$this->SelfSignLogArchive->Kiosk->KioskButton->MasterKioskButton->find('list', array(
						'conditions' => array('MasterKioskButton.parent_id' => $this->params['url']['id']),
						'fields' => array('id', 'name')));
				foreach($possibleButtons as $k => $v) {

					if($v && array_key_exists($v, $possibleMasterButtons)) {
						$buttonList[$v] = $masterButtonList[$v];
					}
				}
			}
			if(isset($buttonList)) {
				asort($buttonList);
				$this->set('buttons', $buttonList);
			}
			$this->render('admin_get_buttons_ajax');
		}
	}

	function _setConditions() {
		if(!empty($this->data['SelfSignLogArchive']['locations']) && $this->data['SelfSignLogArchive']['locations'] != 'null') {
			$conditions['SelfSignLogArchive.location_id'] = $this->data['SelfSignLogArchive']['locations'];
		}
		if(!empty($this->data['SelfSignLogArchive']['button_1'])) {
			$conditions['SelfSignLogArchive.level_1'] = $this->data['SelfSignLogArchive']['button_1'];
		}
		if(!empty($this->data['SelfSignLogArchive']['button_2'])) {
			$conditions['SelfSignLogArchive.level_2'] = $this->data['SelfSignLogArchive']['button_2'];
		}
		if(!empty($this->data['SelfSignLogArchive']['button_3'])) {
			$conditions['SelfSignLogArchive.level_3'] = $this->data['SelfSignLogArchive']['button_3'];
		}
		if(isset($this->data['SelfSignLogArchive']['status']) && $this->data['SelfSignLogArchive']['status'] != null) {
			$conditions['SelfSignLogArchive.status'] = $this->data['SelfSignLogArchive']['status'];
		}
		if(!empty($this->data['SelfSignLogArchive']['date_from']) && !empty($this->data['SelfSignLogArchive']['date_to'])) {
			$from = date('Y-m-d H:i:m', strtotime($this->data['SelfSignLogArchive']['date_from'] . " 12:00am"));
			$to = date('Y-m-d H:i:m', strtotime($this->data['SelfSignLogArchive']['date_to'] . " 11:59pm"));
			$conditions['SelfSignLogArchive.created BETWEEN ? AND ?'] = array($from,
				$to);
		}
		if(isset($conditions)) {
			return $conditions;
		}
	}
	
	function _calculateAverageTimeInSeconds($arrayOfTimesInSeconds) {
        $count = count($arrayOfTimesInSeconds);
        $sum   = array_sum($arrayOfTimesInSeconds);
		$averageInSeconds = 0;
       	if($count > 0 && $sum > 0) {
       		$averageInSeconds = ($sum / $count);
       	}      
        return $averageInSeconds;
	}

}
