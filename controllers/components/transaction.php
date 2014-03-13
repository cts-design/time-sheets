<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

class TransactionComponent extends Object {

    var $components = array('Cookie', 'Auth');

    function createUserTransaction($module, $userId=null, $locationId=null, $details=null) {

	$location = $this->setLocation($locationId);

	$UserTransaction = ClassRegistry::init('UserTransaction');
	if(!empty($userId)) {
	    $data['UserTransaction']['user_id'] = $userId;  
	}
	else $data['UserTransaction']['user_id'] = $this->Auth->user('id');

	if(!empty($details)) {
	    $data['UserTransaction']['details'] = $details;
	}
	else {
	    $data['UserTransaction']['details'] = $this->Cookie->read('details.1');
	    if($this->Cookie->read('details.2')  != '' ) {
		$data['UserTransaction']['details'] .=  ' - '. $this->Cookie->read('details.2');
	    }
	    if($this->Cookie->read('details.3')  != '' ) {
		$data['UserTransaction']['details'] .=  ' - '. $this->Cookie->read('details.3');
	    }
	    if($this->Cookie->read('details.other')  != '' ) {
		$data['UserTransaction']['details'] .=  ' - '. $this->Cookie->read('details.other');
	    }
	}
	if(!empty($location)) {
	    $data['UserTransaction']['location'] = $location;
	}
	elseif($this->Cookie->read('location')  != '' ) {
	    $data['UserTransaction']['location'] = $this->setLocation($this->Cookie->read('location'));
	}
	$data['UserTransaction']['module'] = $module;
	if(!empty($data['UserTransaction']['user_id'])) {
	    $UserTransaction->create();
	    if($UserTransaction->save($data)){
		if(empty($details)) {
		    $this->Cookie->delete('details.1');
		    $this->Cookie->delete('details.2');
		    $this->Cookie->delete('details.3');
		    $this->Cookie->delete('details.other');
		}
		$this->Cookie->delete('location');
		return true;
	    }
	    else return false;
	}
    }

    function setLocation($locationId) {
	$Location = ClassRegistry::init('Location');
	$locations = $Location->find('list');
	if($locationId == null && ($this->Auth->user('role_id') != 1 && !preg_match('/auditor/i', $this->Auth->User('role_name')))) {
	   
	   if($this->Auth->user('location_id') != NULL)
	   {
	   	  $location = $locations[$this->Auth->user('location_id')];
	   }
	   else
	   {
	   	  $location = 'Unknown';
	   }
	}
	elseif($locationId != null) {
	    $location = $locations[$locationId];
	}	
	if(isset($location)) {
	    return $location;
	}
	else return null;
    }
}
