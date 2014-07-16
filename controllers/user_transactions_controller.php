<?php
class UserTransactionsController extends AppController {

    var $name = 'UserTransactions';

	var $helpers = array('Excel', 'Time');
	var $components = array('RequestHandler');

    function admin_index($user_id = NULL)
    {
		if($this->RequestHandler->isPost())
		{
			$this->autoRender = FALSE;
			$user_transactions = $this->UserTransaction->find('all', array(
				'conditions' => array(
					'user_id' => $user_id
				),
				'order' => array('created DESC'),
				'recursive' => -1
			));

			$user_transactions = Set::extract('/UserTransaction/.', $user_transactions);
			echo json_encode(array('success' => TRUE, 'output' => $user_transactions));
		}
		else
		{
			$this->layout = 'default_bootstrap';
		}
    }

    function admin_modules() {
    	$this->autoRender = false;
    	$modules = $this->UserTransaction->find('list', array(
			'fields' => array('UserTransaction.module'),
			'group' => array('UserTransaction.module')
		));

		echo json_encode(array('success' => TRUE, 'output' => $modules));
    }

	function admin_report($userId = FALSE)
	{
		if(!$userId)
		{
		    $this->Session->setFlash(__('Invalid user id.', true), 'flash_failure');
		    $this->redirect($this->referer());
		}

		// Handle get parameters that are passed to the url, some of these will always exist
		// but it's better to check `isset()` anyway
		$get = $this->params['url'];
		$conditions = array(
			'user_id' => $userId
		);
		$order = array();

		if(isset($get['smodule']))
		{
			$conditions['module'] = $get['smodule'];
		}

		if(isset($get['from']) && isset($get['to']))
		{
			$conditions['and']['UserTransaction.created >='] = date('Y-m-d H:i:s', strtotime($get['from']));
			$conditions['and']['UserTransaction.created <='] = date('Y-m-d H:i:s', strtotime($get['to']));
		}
		else if(isset($get['from']) && !isset($get['to']))
		{
			$conditions['UserTransaction.created >='] = date('Y-m-d H:i:s', strtotime($get['from']));
		}
		else if(!isset($get['from']) && isset($get['to']))
		{
			$conditions['UserTransaction.created <='] = date('Y-m-d H:i:s', strtotime($get['to']));
		}

		//Get what we are ordering by
		if(isset($get['order']))
		{
			$order = array('UserTransaction.' . $get['order']);
		}

		//We ascend by default
		if(isset($get['order']) && isset($get['asc']) && $get['asc'] == 'false')
		{
			$order[0] = $order[0] . ' DESC';
		}

		$this->UserTransaction->recursive = 0;
		$userTransactions = $this->UserTransaction->find('all', array(
			'conditions' => $conditions,
			'order' => $order
		));

		if($userTransactions)
		{
			foreach($userTransactions as $k => $v)
			{
                $report[$k]['Name'] 		= $v['User']['firstname'] . ' ' . $v['User']['lastname'];
			    $report[$k]['Location']     = $v['UserTransaction']['location'];
				$report[$k]['Module'] 	  = $v['UserTransaction']['module'];
				$report[$k]['Details']      = $v['UserTransaction']['details'];
				$report[$k]['Created']      =  date('m/d/Y h:i a', strtotime($v['UserTransaction']['created']));
			}
		}
		else
		{
		    $this->Session->setFlash(__('No results to generate a report.', true), 'flash_failure');
		    $this->redirect($this->referer());
		}

		if(!empty($userTransactions[0]['User']['lastname']))
		{
		    $title = 'Activity report for ' . $userTransactions[0]['User']['lastname'] . ', ' . $userTransactions[0]['User']['firstname'] ;
		}
		else
		{
		    $title = 'Activity report.';
		}

		$data = array('data' => $report, 'title' => $title);
        $this->layout = 'ajax';
        $this->set($data);
	}


}
