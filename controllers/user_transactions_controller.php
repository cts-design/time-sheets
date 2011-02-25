<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class UserTransactionsController extends AppController {

    var $name = 'UserTransactions';

    function admin_index($userId=null) {
	if(!$userId) {
	    $this->Session->setFlash(__('Invalid user id.', true), 'flash_failure');
	    $this->redirect($this->referer());
	}
	$this->UserTransaction->recursive = 0;
	$this->paginate = array(
	    'conditions' => array(
		'UserTransaction.user_id' => $userId
	    ),
	    'order' => array('UserTransaction.id' => 'desc')
	);
	$userTransactions = $this->paginate();
	$user = $this->UserTransaction->User->read(null, $userId);
	if(!empty($user['User']['lastname'])) {
	    $title_for_layout = 'Activity for ' . $user['User']['lastname'] . ', ' . $user['User']['firstname'] ;
	}
	else {
	    $title_for_layout = 'Activity';
	}
	$this->set(compact('userTransactions', 'title_for_layout'));
    }
}
