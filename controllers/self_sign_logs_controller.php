<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class SelfSignLogsController extends AppController {

    var $name = 'SelfSignLogs';
    var $uses = array('SelfSignLog', 'MasterKioskButton');
    var $statuses = array(0 => 'Open', 1 => 'Closed', 2 => 'Not Helped');

    function admin_index() {
    	if($this->RequestHandler->isAjax()){
    		$date = date('Y-m-d') . ' 00:0:01';
			$locationIds = $this->_parseLocationIds();
		    if ($locationIds) {							
				$conditions = array(
					'SelfSignLog.created >' => $date,
					'SelfSignLog.location_id' => $locationIds
					);
			}
			else {
				$conditions = array('SelfSignLog.created >' => $date);
			}
			$serviceIds = null;
		    if(isset($this->params['url']['services']) && $this->params['url']['services'] != '' ) {
				if(strpos($this->params['url']['services'], ',')) {
					$servicesArr = explode(',', $this->params['url']['services']);
					$i = 0;
					foreach ($servicesArr as $key => $value) {
					    $serviceIds[$i] = $value;
					    $i++;
					}			
				}
				else {
					$serviceIds = $this->params['url']['services'];
				}
			}
			if($serviceIds){
				$conditions['SelfSignLog.level_1'] = $serviceIds;
			}
			$status = 0;
			if(isset($this->params['url']['status']) && $this->params['url']['status'] != '' ) {
				$status = $this->params['url']['status'];
			}
			$conditions['SelfSignLog.status'] = $status;				
			
			$selfSignLogs = $this->SelfSignLog->find('all', array(
				'conditions' => $conditions,
				'order' => array('SelfSignLog.created DESC')));
			$i = 0;

			$this->log(var_export($selfSignLogs, true));

			$masterKioskButtonList = $this->_getAllMasterButtonNames();
			$data = array();
			$data['results'] = $this->SelfSignLog->find('count', array('conditions' => $conditions));
			$data['success'] = true;
			$data['logs'] = array();
			if($selfSignLogs){
				$kiosks = $this->_getKioskNames();
				foreach($selfSignLogs as $selfSignLog) {
					$data['logs'][$i]['id'] = $selfSignLog['SelfSignLog']['id'];
					$data['logs'][$i]['status'] = $this->statuses[$selfSignLog['SelfSignLog']['status']]; 
					$data['logs'][$i]['lastname'] = ucfirst($selfSignLog['User']['lastname']);		 
					$data['logs'][$i]['firstname'] = ucfirst($selfSignLog['User']['firstname']);
					$data['logs'][$i]['last4'] = substr($selfSignLog['User']['ssn'], -4);
					$data['logs'][$i]['userId'] = $selfSignLog['User']['id'];
					$data['logs'][$i]['veteran'] = $selfSignLog['User']['veteran'];
					$data['logs'][$i]['disability'] = $selfSignLog['User']['disability'];
					$level2 = null;
					$level3 = null;
					$other = null;
					if(!empty($selfSignLog['SelfSignLog']['level_2'])) {
						$level2 = ' - ' . $masterKioskButtonList[$selfSignLog['SelfSignLog']['level_2']];
					}
					if(!empty($selfSignLog['SelfSignLog']['level_3'])) {
						$level3 = ' - ' .  $masterKioskButtonList[$selfSignLog['SelfSignLog']['level_3']];
					}
					if(!empty($selfSignLog['SelfSignLog']['other'])) {
						$other = ' - ' . $selfSignLog['SelfSignLog']['other'];
					}	
					$data['logs'][$i]['service'] = 	trim($masterKioskButtonList[$selfSignLog['SelfSignLog']['level_1']] .
						' ' . $level2 . ' ' . $level3 . ' ' . $other, ' -');
	 				$data['logs'][$i]['created'] = $selfSignLog['SelfSignLog']['created'];
					$data['logs'][$i]['admin'] = trim(ucfirst($selfSignLog['Admin']['lastname']) . ', ' .
						ucfirst($selfSignLog['Admin']['firstname']), ',');
					$data['logs'][$i]['location'] = $selfSignLog['Location']['name'];
					$data['logs'][$i]['locationId'] = $selfSignLog['Location']['id'];
					$data['logs'][$i]['kioskId'] = $selfSignLog['SelfSignLog']['kiosk_id'];
					$data['logs'][$i]['kiosk'] = $kiosks[$selfSignLog['SelfSignLog']['kiosk_id']];		
					$i++;	
				}				
			}
			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');	
    	}
		$title_for_layout = 'Self Sign Queue';
		$this->set(compact('title_for_layout'));
    }

    function admin_update_status($id=null, $status=null) {
		if($this->RequestHandler->isAjax()) {
		    if ($id != null && $status != null) {
				if($status == 1 || $status == 2) {
				    $closed = date('Y-m-d H:i:s');
				}
				else {
				    $closed = null;
				}
				$this->data['SelfSignLog']['id'] = $id;
				$this->data['SelfSignLog']['last_activity_admin_id'] = $this->Auth->user('id');
				$this->data['SelfSignLog']['status'] = $status;
				$this->data['SelfSignLog']['closed'] = $closed;
				$this->data['SelfSignLogArchive'] = $this->data['SelfSignLog'];

				if($this->SelfSignLog->save($this->data) &&
					$this->SelfSignLog->Kiosk->SelfSignLogArchive->save($this->data)) {
				    $log = $this->SelfSignLog->findById($id);
					$customer = ucfirst($log['User']['lastname']) . ', ' . 
						ucfirst($log['User']['firstname']) . ' - ' . 
						substr($log['User']['ssn'], -4);
				    if($status == 1) {
						$details = 'Closed self sign queue record for ' . $customer;
				    }
					elseif($status == 2) {
						$details = 'Marked self sign queue record as not helped for ' . $customer;					
					}
				    else {
						$details = 'Opened self sign queue record for ' . $customer;
				    }
				    $this->Transaction->createUserTransaction('Self Sign', null, null, $details);
				}
				$this->autoRender = false;
		    }
		}
    }

	function admin_reassign() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				foreach($this->data['SelfSignLog'] as $k => $v) {
					if(empty($v)) {
						$this->data['SelfSignLog'][$k] = null;
 					}
				}				
				$this->data['SelfSignLog']['last_activity_admin_id'] = $this->Auth->user('id');
				$this->data['SelfSignLogArchive'] = $this->data['SelfSignLog'];
				if($this->SelfSignLog->save($this->data['SelfSignLog']) && 
					$this->SelfSignLog->Kiosk->SelfSignLogArchive->save($this->data['SelfSignLogArchive'])){
					$data['success'] = true;
					$data['message'] = 'Record reassigned successfully.';
					$log = $this->SelfSignLog->findById($this->data['SelfSignLog']['id']);
					$details = 'Reassigned self sign queue record for ' .
						ucfirst($log['User']['lastname']) . ', ' . 
						ucfirst($log['User']['firstname']) . ' - ' . 
						substr($log['User']['ssn'], -4); 
					$this->Transaction->createUserTransaction('Self Sign', null, null, $details);					
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to reassign record at this time, please try again.';
				}
			}
			
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_new_record() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				foreach($this->data['SelfSignLog'] as $k => $v) {
					if(empty($v)) {
						$this->data['SelfSignLog'][$k] = null;
 					}
				}
				$this->data['SelfSignLog']['last_activity_admin_id'] = $this->Auth->user('id');
				$this->data['SelfSignLogArchive'] = $this->data['SelfSignLog'];
				$this->SelfSignLog->create();
				if($this->SelfSignLog->save($this->data['SelfSignLog'], false)){
					$this->SelfSignLog->Kiosk->SelfSignLogArchive->create();
					$this->data['SelfSignLogArchive']['id'] = $this->SelfSignLog->getLastInsertId();
					$this->SelfSignLog->Kiosk->SelfSignLogArchive->save($this->data['SelfSignLogArchive']);	
					$data['success'] = true;
					$data['message'] = 'New sign in created successfully.';
					$log = $this->SelfSignLog->findById($this->SelfSignLog->getLastInsertId());
					$details = 'Created new self sign queue record for ' .
						ucfirst($log['User']['lastname']) . ', ' . 
						ucfirst($log['User']['firstname']) . ' - ' . 
						substr($log['User']['ssn'], -4); 
					$this->Transaction->createUserTransaction('Self Sign', null, null, $details);
					$buttons = $this->_getAllMasterButtonNames();
					$details = 'Sign in created by admin. ';
					if($log['SelfSignLog']['level_1']) {
						$details .= $buttons[$log['SelfSignLog']['level_1']] . ' - ';
					}
					if($log['SelfSignLog']['level_2']) {
						$details .= $buttons[$log['SelfSignLog']['level_2']] . ' - ';
					}
					if($log['SelfSignLog']['level_3']) {
						$details .= $buttons[$log['SelfSignLog']['level_3']] . ' - ';
					}
					if($log['SelfSignLog']['other']) {
						$details .= $log['SelfSignLog']['other'];
					}
					$details = trim($details, ' -');
					$this->Transaction->createUserTransaction('Self Sign', 
						$log['User']['id'], $log['SelfSignLog']['location_id'], $details);	
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to create new sign in at this time, please try again.';
				}
			}
			
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

    function admin_get_services() {
		if ($this->RequestHandler->isAjax()) {
		    $this->loadModel('KioskButton');
		    $masterParentButtonNameList = $this->_getParentMasterButtonNames();
			$data = array('services' => array());
		    $locationIds = $this->_parseLocationIds();
		    if($locationIds) {				
				$kiosks = $this->SelfSignLog->Kiosk->find('list',
					array('conditions' => array('Kiosk.location_id' => $locationIds)));		
				$data['services'] = array();
				if($kiosks) {
					$buttons = $this->KioskButton->find('all', array(
					    'conditions' =>array(
						'KioskButton.parent_id' => null,
						'KioskButton.kiosk_id' => $kiosks,
						'KioskButton.status' => 0 )));	
					$i = 0;
					if($buttons) {
						foreach ($buttons as $button) {
							if($masterParentButtonNameList[$button['KioskButton']['id']] != 'Scan Documents') {
							    $data['services'][$i]['id'] = $button['KioskButton']['id'];
							    $data['services'][$i]['name'] = $masterParentButtonNameList[$button['KioskButton']['id']];
							    $i++;								
							}

						}						
					}
					
				}
		    }
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
    }
	
	function admin_get_kiosk_buttons($kioskId, $parentId=null) {
		if($this->RequestHandler->isAjax()) {
			$masterButtonList = $this->_getAllMasterButtonNames();
			if($parentId) {				
				$buttons = $this->MasterKioskButton->KioskButton->find('list', array(
					'conditions' => array(
						'KioskButton.kiosk_id' => $kioskId,
						'KioskButton.parent_id' => $parentId,
						'KioskButton.status' => 0),
					'fields' => array('button_id', 'id')));
			}
			else {			
				$buttons = $this->MasterKioskButton->KioskButton->find('list', array(
					'conditions' => array(
						'KioskButton.kiosk_id' => $kioskId,
						'KioskButton.parent_id' => null,
						'KioskButton.status' => 0),
					'fields' => array('button_id', 'id')));
			}
			$data['buttons'] = array();
			if($buttons) {
				$i = 0;
				foreach($buttons as $k => $v) {
					if(! in_array($v, $data['buttons']) && $masterButtonList[$v] != 'Scan Documents') {
						$data['buttons'][$i]['id'] = $v;
						$data['buttons'][$i]['name'] = $masterButtonList[$v];
						$i++;						
					}

				}				
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');	
		}	
		
	}

	function _parseLocationIds() {
	    if(isset($this->params['url']['locations']) && $this->params['url']['locations'] != '' ) {
			if(strpos($this->params['url']['locations'], ',')) {
				$locationsArr = explode(',', $this->params['url']['locations']);
				$i = 0;
				foreach ($locationsArr as $key => $value) {
				    $locationIds[$i] = $value;
				    $i++;
				}
				return $locationIds;					
			}
			else {
				$locationIds = $this->params['url']['locations'];
				return $locationIds;
			}
		}
		else return false;			
	}

    function _getParentMasterButtonNames() {
		return $this->MasterKioskButton->find('list', array(
		    'fields' => array('MasterKioskButton.id', 'MasterKioskButton.name'),
		    'conditions' => array('MasterKioskButton.parent_id' => null, 'MasterKioskButton.deleted' => 0)));
    }

    function _getAllMasterButtonNames() {
		return $this->MasterKioskButton->find('list', array(
		    'fields' => array('MasterKioskButton.id', 'MasterKioskButton.name')));
    }
	
	function _getKioskNames(){
		$this->loadModel('Kiosk');
		return $this->Kiosk->find('list', array(
		    'fields' => array('Kiosk.id', 'Kiosk.location_description'),
		    'conditions' => array('Kiosk.deleted' => 0)));		
	}

}