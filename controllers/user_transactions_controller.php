<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class UserTransactionsController extends AppController {

    var $name = 'UserTransactions';
	
	var $helpers = array('Excel', 'Time');

    function admin_index($userId=null) {
		if(!$userId) {
		    $this->Session->setFlash(__('Invalid user id.', true), 'flash_failure');
		    $this->redirect($this->referer());
		}

		$conditions = array('UserTransaction.user_id' => $userId);

		//Checks if module param exists
		if( isset($this->params['url']['module']) && $this->params['url']['module'] != "" )
		{
			$selected_module = $this->params['url']['module'];
			$conditions['UserTransaction.module'] = $selected_module;
		}
		else
		{
			$selected_module = "";
		}
		$this->set('selected_module', $selected_module);

		//Checks if from-to date param exists
		if( isset($this->params['url']['from']) && $this->params['url']['from'] != "")
		{
			$human_from_date = $this->params['url']['from'];
			$from_date = date( "Y-m-d H:i:s", strtotime($human_from_date) );
			$conditions['UserTransaction.created >='] = $from_date;
		}
		else
		{
			$human_from_date = "";
			$from_date = "";
		}
		$this->set('human_from_date', $human_from_date);

		//Checks if to date param exists
		if( isset($this->params['url']['to']) && $this->params['url']['to'] != "")
		{
			$human_to_date = $this->params['url']['to'];
			$to_date = date( "Y-m-d H:i:s", strtotime($human_to_date) );
			$conditions['UserTransaction.created <='] = $to_date;
		}
		else
		{
			$human_to_date = "";
			$to_date = "";
		}
		$this->set('human_to_date', $human_to_date);

		//Get's available modules from table
		$modules = $this->UserTransaction->find('list', array(
			'fields' => array('UserTransaction.module'),
			'group' => array('UserTransaction.module')
		));
		$this->set('modules', $modules);

		$this->UserTransaction->recursive = 0;

		$this->paginate = array(
		    'conditions' => $conditions,
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