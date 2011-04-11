<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class SelfSignLogsController extends AppController {

    var $name = 'SelfSignLogs';
    var $uses = array('SelfSignLog', 'MasterKioskButton');
    var $statuses = array(0 => 'open', 1 => 'closed');

    function admin_index() {
	$data = array(
	   'buttons' =>  $this->_getParentMasterButtonNames(),
	    'locations' => $this->_getLocations(),
	    'statuses' => $this->statuses,
	    'title_for_layout' => 'Self Sign Queue'
	);
	$this->set($data);
    }

    function admin_get_logs_ajax() {
	if($this->RequestHandler->isAjax()) {
	    $date = date('Y-m-d') . ' 00:01:00';
	    if (isset($this->params['url']['location'], $this->params['url']['service']) &&
		    $this->params['url']['location'] != '' && $this->params['url']['service'] != '') {
		$serviceArr = explode(',', $this->params['url']['service']);
		$i = 0;
		foreach ($serviceArr as $key => $value) {
		    $ids[$i] = $value;
		    $i++;
		}
		$this->set('selfSignLogs', $this->SelfSignLog->find('all', array(
			    'conditions' => array(
				'SelfSignLog.created >' => $date,
				'SelfSignLog.location_id' => $this->params['url']['location'],
				'SelfSignLog.level_1' => $ids), 'order' => array('SelfSignLog.id' => 'DESC'))));
		$data = array(
		    'locationNames' => $this->_getLocations($this->params['url']['location']),
		    'buttonNames' => $this->_getAllMasterButtonNames()
		);
		$this->set($data);
	    }
	    elseif (isset($this->params['url']['location']) && $this->params['url']['location'] != '') {
		$this->set('selfSignLogs', $this->SelfSignLog->find('all', array(
			    'conditions' => array(
				'SelfSignLog.location_id' => $this->params['url']['location'],
				'SelfSignLog.created >' => $date),
			    'order' => array('SelfSignLog.id' => 'DESC'))));
		$data = array(
		    'locationNames' => $this->_getLocations($this->params['url']['location']),
		    'buttonNames' => $this->_getAllMasterButtonNames()
		);

		$this->set($data);
	    }
	    elseif (isset($this->params['url']['service']) && $this->params['url']['service'] != '') {
		$serviceArr = explode(',', $this->params['url']['service']);
		$i = 0;
		foreach ($serviceArr as $key => $value) {
		    $ids[$i] = $value;
		    $i++;
		}
		$selfSignLogs = $this->SelfSignLog->find('all', array(
			    'conditions' => array(
				'SelfSignLog.level_1' => $ids,
				'SelfSignLog.created >' => $date),
			    'order' => array('SelfSignLog.id' => 'DESC')));
		$locationIds = Set::extract($selfSignLogs, '/Kiosk/location_id');
		$uniqueLocationIds = array_unique($locationIds);
		$locationNames = $this->SelfSignLog->Location->find('list',
			array('conditions' => array('Location.id' => $uniqueLocationIds)));
		$data = array(
		    'buttonNames' => $this->_getAllMasterButtonNames(),
		    'selfSignLogs' => $selfSignLogs,
		    'locationNames' => $locationNames
		);
		$this->set($data);
	    }
	    else {
		$selfSignLogs = $this->SelfSignLog->find('all', array(
			    'conditions' => array('SelfSignLog.created >' => $date),
			    'order' => array('SelfSignLog.id' => 'DESC')));
		$locationIds = Set::extract($selfSignLogs, '/Kiosk/location_id');
		$uniqueLocationIds = array_unique($locationIds);
		$locationNames = $this->SelfSignLog->Location->find('list',
			array('conditions' => array('Location.id' => $uniqueLocationIds)));
		$data = array(
		    'buttonNames' => $this->_getAllMasterButtonNames(),
		    'selfSignLogs' => $selfSignLogs,
		    'locationNames' => $locationNames
		);
		$this->set($data);
	    }
	    $this->set('statuses', $this->statuses);
	    if(isset($this->params['url']['return']) && $this->params['url']['return'] == 'json') {
		$this->render('/self_sign_logs/admin_get_logs_ajax_json');
	    }
	}
    }

    function admin_get_logs_ajax_json(){
	if($this->RequestHandler->isAjax()) {   	    
	    $date = date('Y-m-d') . ' 00:01:00';
	    if (isset($this->params['url']['location'], $this->params['url']['services']) &&
		    $this->params['url']['location'] != '' && $this->params['url']['services'] != '') {
		$serviceArr = explode(',', $this->params['url']['services']);
		$i = 0;
		foreach ($serviceArr as $key => $value) {
		    $ids[$i] = $value;
		    $i++;
		}
		$conditions = array(
		    'SelfSignLog.created >' => $date,
		    'SelfSignLog.location_id' => $this->params['url']['location'],
		    'SelfSignLog.level_1' => $ids
		);
		$order = array('SelfSignLog.id' => 'DESC');
		$logs = $this->SelfSignLog->find('all', array(
		    'conditions' => $conditions,
		    'order' => $order));
		$buttons = $this->_getAllMasterButtonNames();
		if($logs) {
		    $selfSignLogs = array('success' => 'true', 'SelfSignLog' => array());
		    $i = 0;
		    foreach($logs as $log) {
			$selfSignLogs['SelfSignLog'][$i]['id'] = $log['SelfSignLog']['id'];
			if($log['SelfSignLog']['status'] == 0) {
			    $selfSignLogs['SelfSignLog'][$i]['status'] = 'Open';
			}
			elseif($log['SelfSignLog']['status'] == 1) {
			     $selfSignLogs['SelfSignLog'][$i]['status'] = 'Closed';
			}
			$selfSignLogs['SelfSignLog'][$i]['firstname'] = ucfirst($log['User']['firstname']);
			$selfSignLogs['SelfSignLog'][$i]['lastname'] = ucfirst($log['User']['lastname']);
			$selfSignLogs['SelfSignLog'][$i]['service'] = $buttons[$log['SelfSignLog']['level_1']];
			if(!empty($log['SelfSignLog']['level_2'])) {
			    $selfSignLogs['SelfSignLog'][$i]['service'] .= ' - ' . $buttons[$log['SelfSignLog']['level_2']];
			}
			if(!empty($log['SelfSignLog']['level_3'])) {
			    $selfSignLogs['SelfSignLog'][$i]['service'] .= ' - ' . $buttons[$log['SelfSignLog']['level_3']];
			}
			$selfSignLogs['SelfSignLog'][$i]['service'] .= ' - ' . $log['SelfSignLog']['other'];
			$selfSignLogs['SelfSignLog'][$i]['service'] = trim($selfSignLogs['SelfSignLog'][$i]['service'], ' - ');
			$selfSignLogs['SelfSignLog'][$i]['admin'] = trim($log['Admin']['lastname'] . ', ' . $log['Admin']['firstname'], ',');
			$selfSignLogs['SelfSignLog'][$i]['date'] = $log['SelfSignLog']['created'];
			$selfSignLogs['SelfSignLog'][$i]['kiosk'] = $log['Kiosk']['location_recognition_name'];
			$i++;
		    }
		}
		else {
		    $selfSignLogs = array('success' => 'true', 'SelfSignLog' => array());
		}
		$this->set(compact('selfSignLogs'));
	    }
	}
    }

    function admin_get_services_ajax() {
	if ($this->RequestHandler->isAjax()) {
	    $this->loadModel('KioskButton');
	    $masterParentButtonNameList = $this->_getParentMasterButtonNames();
	    if (isset($this->params['url']['id']) && $this->params['url']['id'] != '' ) {
		$kiosks = $this->SelfSignLog->Kiosk->find('list',
			array('conditions' => array('Kiosk.location_id' => $this->params['url']['id'])));
		$buttons = $this->KioskButton->find('all', array(
		    'conditions' =>array(
			'KioskButton.parent_id' => null,
			'KioskButton.kiosk_id' => $kiosks,
			'KioskButton.status' => 0 )));
		foreach ($buttons as $button) {
		    $options[$button['KioskButton']['id']] = $masterParentButtonNameList[$button['KioskButton']['id']];
		}
		if(isset($options)) {
		    $this->set('options', $options);
		}		
	    }
	    else {
		$this->set('options', $masterParentButtonNameList);
	    }
	}
    }

    function admin_get_services_ajax_air() {
	if ($this->RequestHandler->isAjax()) {
	    $this->loadModel('KioskButton');
	    $masterParentButtonNameList = $this->_getParentMasterButtonNames();
	    if (isset($this->params['url']['id']) && $this->params['url']['id'] != '' ) {
		$kiosks = $this->SelfSignLog->Kiosk->find('list',
			array('conditions' => array('Kiosk.location_id' => $this->params['url']['id'])));
		$options = array('services' => array());
		$buttons = $this->KioskButton->find('all', array(
		    'conditions' =>array(
			'KioskButton.parent_id' => null,
			'KioskButton.kiosk_id' => $kiosks,
			'KioskButton.status' => 0 )));
		$i = 0;
		foreach ($buttons as $button) {
		    $options['services'][$i]['id'] = $button['KioskButton']['id'];
		    $options['services'][$i]['name'] = $masterParentButtonNameList[$button['KioskButton']['id']];
		    $i++;
		}
		$this->set(compact('options'));
		
	    }
	}
    }

    function admin_toggle_status($id=null, $status=null) {
	if($this->RequestHandler->isAjax()) {
	    if ($id != null && $status != null) {
		if($status == 1) {
		    $closed = date('Y-m-d H:i:s');
		}
		else {
		    $closed = null;
		}
		$this->data['SelfSignLog']['id'] = $id;
		$this->data['SelfSignLog']['last_activity_admin_id'] = $this->Auth->user('id');
		$this->data['SelfSignLog']['status'] = $status;
		$this->data['SelfSignLog']['closed'] = $closed;
		$this->data['SelfSignLogArchive']['id'] = $id;
		$this->data['SelfSignLogArchive']['last_activity_admin_id'] = $this->Auth->user('id');
		$this->data['SelfSignLogArchive']['status'] = $status;
		$this->data['SelfSignLogArchive']['closed'] = $closed;
		if($this->SelfSignLog->save($this->data) &&
			$this->SelfSignLog->Kiosk->SelfSignLogArchive->save($this->data)) {
		    $log = $this->SelfSignLog->findById($id);
		    if($status == 1) {
			$details = 'Closed self sign queue record for ' .
				$log['User']['lastname'] . ', ' . substr($log['User']['ssn'],'5');
		    }
		    else {
			$details = 'Opened self sign queue record for '.
			    $log['User']['lastname'] . ', ' . substr($log['User']['ssn'],'5');
		    }
		    $this->Transaction->createUserTransaction('Self Sign', null, null, $details);
		}
		$this->autoRender = false;
	    }
	}
    }

    function _getLocations($id=null) {
	$this->loadModel('Kiosk');
	if ($id == null) {
	    return $this->Kiosk->Location->find('list');
	}

	if ($id != null) {
	    return $this->Kiosk->Location->find('list', array(
		'conditions' => array('Location.id' => $id)));
	}
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

}