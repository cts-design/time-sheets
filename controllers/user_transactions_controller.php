<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class UserTransactionsController extends AppController {

    var $name = 'UserTransactions';
	
	var $helpers = array('Excel');

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
	
	function admin_report($userId=null) {
		if(!$userId) {
		    $this->Session->setFlash(__('Invalid user id.', true), 'flash_failure');
		    $this->redirect($this->referer());
		}
		$this->UserTransaction->recursive = 0;
		$userTransactions = $this->UserTransaction->findAllByUserId($userId, array(), 
			 array('UserTransaction.id DESC'), 1000);
		if($userTransactions) {
			foreach($userTransactions as $k => $v) {
				$report[$k]['Name'] = $v['User']['firstname'] . ' ' . $v['User']['lastname'];
				$report[$k]['Location'] = $v['UserTransaction']['location'];
				$report[$k]['Module'] = $v['UserTransaction']['module'];
				$report[$k]['Details'] = $v['UserTransaction']['details'];
				$report[$k]['Created'] =  date('m/d/Y h:i a', strtotime($v['UserTransaction']['created']));
			}
		}
		else {
		    $this->Session->setFlash(__('No results to generate a report.', true), 'flash_failure');
		    $this->redirect($this->referer());			
		}
		if(!empty($userTransactions[0]['User']['lastname'])) {
		    $title = 'Activity report for ' . $userTransactions[0]['User']['lastname'] . ', ' . $userTransactions[0]['User']['firstname'] ;
		}
		else {
		    $title = 'Activity report.';
		}
		$data = array('data' => $report,
			'title' => $title);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->set($data);
	}
}