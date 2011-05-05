<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

class PermissionsController extends AppController {

    var $name = 'Permissions';
    var $uses = '';

    function admin_index($id=null, $model=null) {
	    $ModuleAccessControl =& ClassRegistry::init('ModuleAccessControl');
			
		if(!$id) {
		    $this->Session->setFlash(__('Invalid Id. Please try again.', true), 'flash_failure');
		    $this->redirect($this->referer());
		}
		if(isset($model) && $model == 'Role') {
		    $this->loadModel('Role');
		    $role = $this->Role->find('first', array('conditions' => array('Role.id' => $id)));
		    $title_for_layout = 'Editing permissions for role ' . $role['Role']['name'];
		}
	
		$perms = $this->Acl->Aro->find('threaded', array('conditions' => array('Aro.foreign_key' => $id, 'Aro.model' => $model)));
		if($model == 'User') {
		    $this->loadModel('User');
		    $user = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		    $title_for_layout = 'Editing permissions for ' . $user['User']['firstname'] . ' ' . $user['User']['lastname'];
		    $data['aroId'] = $perms[0]['Aro']['id'];
		    $parentPerms = $this->Acl->Aro->find('first', array('conditions' => array('Aro.id' => $perms[0]['Aro']['parent_id'])));
		}
		$acos = Set::extract('/Aco', $perms);
		if(isset($parentPerms)) {
		    $acos1 = Set::extract('/Aco', $parentPerms);
		    $acos = array_merge($acos1, $acos);
		}
		foreach($acos as $aco) {
		    if(isset($aco['Aco']['Permission']) && $aco['Aco']['Permission']['_create'] && $aco['Aco']['Permission']['_read'] &&
			    $aco['Aco']['Permission']['_update'] && $aco['Aco']['Permission']['_delete'] == 1) {
	
			if($aco['Aco']['parent_id'] == '' || $aco['Aco']['parent_id'] == 1) {
			    $data['controllers'][$aco['Aco']['alias']]['all'] = true;
			}
			else {
			   $parent = $this->Acl->Aco->find('first', array('conditions' => array('Aco.id' => $aco['Aco']['parent_id'])));
			   $data['controllers'][$parent['Aco']['alias']][$aco['Aco']['alias']] = true;
			}
		    }
		    if(isset($aco['Aco']['Permission']) && $aco['Aco']['Permission']['_create'] && $aco['Aco']['Permission']['_read'] &&
			    $aco['Aco']['Permission']['_update'] && $aco['Aco']['Permission']['_delete'] == '-1') {
	
			if($aco['Aco']['parent_id'] == '' || $aco['Aco']['parent_id'] == 1) {
			    $data['controllers'][$aco['Aco']['alias']]['all'] = false;
			}
			else {
			   $parent = $this->Acl->Aco->find('first', array('conditions' => array('Aco.id' => $aco['Aco']['parent_id'])));
			   $data['controllers'][$parent['Aco']['alias']][$aco['Aco']['alias']] = false;
			}
		    }
		}
		$data['disabledModules'] = $ModuleAccessControl->find('list',
															  array('fields' => array('ModuleAccessControl.name'),
															  		'conditions' => array('permission' => 1)));
		$data['title_for_layout'] = $title_for_layout;
		$data['id'] = $id;
		$data['model'] = $model;
		$this->set($data);
    }

    function admin_set_permissions() {
	if(!empty($this->data)) {
	    $id = $this->data['permission']['id'];
	    $model = $this->data['permission']['model'];
	    foreach($this->data as $key => $value) {
		if($key == 'permission') {
		    continue;
		}
		foreach($value as $k => $v ) {
		    if($k == 'all') {
			$node = $key;
		    }
		    else {
			$node = $key.'/'.$k;
		    }
		    if($v === '1') {
			if(!$this->Acl->allow(array('model' => $model, 'foreign_key' => $id), $node, '*')) {
			    $bool[] = false;
			}
		    }
		    elseif($v === '0') {
			if(!$this->Acl->deny(array('model' => $model, 'foreign_key' => $id), $node, '*')) {
			    $bool[] = false;
			}
		    }
		}
	    }	    
	    if(!empty($bool) && in_array(false, $bool)) {
		$this->Session->setFlash(__('An error occured, please try again.', true), 'flash_failure');
		$this->redirect($this->referer());
	    }
	    else {
		$this->Session->setFlash(__('Permissions have been set.', true), 'flash_success');
		if($model == 'User') {
		   $this->loadModel('User');
		   $user = $this->User->findById($id);
		   $details = 'Edited permissions for admin ' . $user['User']['username'];
		}
		elseif($model == 'Role') {
		    $this->loadModel('Role');
		    $role = $this->Role->findById($id);
		    $details = 'Edited permissions for role '. $role['Role']['name'];
		}
		$this->Transaction->createUserTransaction('Permissions', null, null, $details);
		$this->redirect($this->referer());
	    }
	}
    }

    function admin_delete_permissions($id=null, $userId=null) {
	if(!$id) {
	    $this->Session->setFlash(__('Invalid Id. Please try again.', true), 'flash_failure');
	    $this->redirect($this->referer());
	}

	if($this->Acl->Aro->Permission->deleteAll(array('Aro.id' => $id))) {
	    $this->Session->setFlash(__('Permissions reset.', true), 'flash_success');
	   $this->loadModel('User');
	   $user = $this->User->findById($userId);
	   $details = 'Reset permissions for admin ' . $user['User']['username'];
	   $this->Transaction->createUserTransaction('Permissions', null, null, $details);
	    $this->redirect($this->referer());
	}
	else {
	    $this->Session->setFlash(__('Permissions were not reset. Please try again.', true), 'flash_failure');
	    $this->redirect($this->referer());
	}
    }
}
