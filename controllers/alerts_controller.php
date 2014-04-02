<?php

/**
 * Alerts Controller
 *
 * @package AtlasV3
 * @author  Daniel Nolan
 * @copyright 2012 Complete Technology Solutions
 */

class AlertsController extends AppController {

    public $name = 'Alerts';

    public function beforeFilter() {
        parent::beforeFilter();
        if($this->Acl->check(array(
            'model' => 'User',
            'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_index', '*')) {
                $this->Auth->allow(
                'admin_toggle_email',
                'admin_toggle_disabled',
                'admin_get_alert_types',
                'admin_delete');
        }
		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_add_self_sign_alert', '*')) {
			$this->Auth->allow('admin_update_self_sign_alert');
		}
		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_add_self_scan_alert', '*')) {
			$this->Auth->allow('admin_update_self_scan_alert');
		}
		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_add_customer_details_alert', '*')) {
			$this->Auth->allow('admin_update_customer_details_alert');
		}
		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_add_customer_login_alert', '*')) {
			$this->Auth->allow('admin_update_customer_login_alert');
		}
		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_add_cus_filed_doc_alert', '*')) {
			$this->Auth->allow('admin_update_cus_filed_doc_alert');
		}
		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_add_staff_filed_document_alert', '*')) {
			$this->Auth->allow('admin_update_staff_filed_document_alert');
		}
		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_add_self_scan_category_alert', '*')) {
			$this->Auth->allow('admin_update_self_scan_category_alert');
		}
		if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_add_program_response_status_alert', '*')) {
			$this->Auth->allow('admin_update_program_response_status_alert');
		}
    }

    public function admin_index() {
        if($this->RequestHandler->isAjax()) {
            $alerts = $this->Alert->find('all', array(
                'conditions' => array('Alert.user_id' => $this->Auth->user('id'))));
            if($alerts) {
                $i = 0;
                $bools = array(0 => false, 1 => true);
                foreach($alerts as $alert) {
                    $data['alerts'][$i] = $alert['Alert'];
                    $data['alerts'][$i]['type'] = Inflector::humanize($data['alerts'][$i]['type']);
                    $data['alerts'][$i]['send_email'] = $bools[$data['alerts'][$i]['send_email']];
                    $data['alerts'][$i]['disabled'] = $bools[$data['alerts'][$i]['disabled']];
                    $i++;
                }
            }
            else {
                $data['alerts'] = array();
            }
            $data['success'] = true;
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_add_self_sign_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'self_sign';
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(!empty($this->params['form']['location_id'])) {
                $this->data['Alert']['location_id'] = $this->params['form']['location_id'];
            }
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if(!isset($this->params['form']['level2']) && !isset($this->params['form']['level3'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['level1'];
            }
            elseif(isset($this->params['form']['level2']) && !isset($this->params['form']['level3'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['level2'];
            }
            elseif(isset($this->params['form']['level2']) && isset($this->params['form']['level3'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['level3'];
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert added successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Added Self Sign alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

	public function admin_update_self_sign_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['id'] = $this->params['form']['id'];
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'self_sign';
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(!empty($this->params['form']['location_id'])) {
                $this->data['Alert']['location_id'] = $this->params['form']['location_id'];
            }
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
			else {
				$this->data['Alert']['send_email'] = 0;
			}
            if(!isset($this->params['form']['level2']) && !isset($this->params['form']['level3'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['level1'];
            }
            elseif(isset($this->params['form']['level2']) && !isset($this->params['form']['level3'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['level2'];
            }
            elseif(isset($this->params['form']['level2']) && isset($this->params['form']['level3'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['level3'];
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert updated successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Updated Self Sign alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_add_customer_details_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'customer_details';
            if(!empty($this->params['form']['location_id'])) {
                $this->data['Alert']['location_id'] = $this->params['form']['location_id'];
            }
            $this->data['Alert']['detail'] = $this->params['form']['detail'];
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert added successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Added Customer Details alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_update_customer_details_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['id'] = $this->params['form']['id'];
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'customer_details';
            if(!empty($this->params['form']['location_id'])) {
                $this->data['Alert']['location_id'] = $this->params['form']['location_id'];
            }
            $this->data['Alert']['detail'] = $this->params['form']['detail'];
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert updated successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Updated Customer Details alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to updated alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_add_queued_document_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'queued_document';
            $this->data['Alert']['location_id'] = $this->params['form']['location'];
            $this->data['Alert']['watched_id'] = $this->params['form']['queue_cat'];
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert added successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Added Queued Document alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_add_self_scan_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'self_scan';
            if(!empty($this->params['form']['firstname'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['firstname'];
            }
            if(!empty($this->params['form']['ssn'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['ssn'];
            }

            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert added successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Added Self Scan alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_update_self_scan_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['id'] = $this->params['form']['id'];
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'self_scan';
            if(!empty($this->params['form']['firstname'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['firstname'];
            }
            if(!empty($this->params['form']['ssn'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['ssn'];
            }

            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
			else {
				$this->data['Alert']['send_email'] = 0;
			}
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert updated successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Updated Self Scan alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to update alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_add_self_scan_category_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'self_scan_category';
            if(!empty($this->params['form']['self_scan_category_id'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['self_scan_category_id'];
            }
			if(!empty($this->params['form']['location_id'])) {
				$this->data['Alert']['location_id'] = $this->params['form']['location_id'];
			}

            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
			else {
				$this->data['Alert']['send_email'] = 0;
			}
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert updated successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Added Self Scan Category alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_update_self_scan_category_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['id'] = $this->params['form']['id'];
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'self_scan_category';
            if(!empty($this->params['form']['self_scan_category_id'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['self_scan_category_id'];
            }
			if(!empty($this->params['form']['location_id'])) {
				$this->data['Alert']['location_id'] = $this->params['form']['location_id'];
			}

            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
			else {
				$this->data['Alert']['send_email'] = 0;
			}
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert updated successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Updated Self Scan Category alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to update alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }
	
    public function admin_add_customer_login_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'customer_login';
            if(!empty($this->params['form']['location_id'])) {
                $this->data['Alert']['location_id'] = $this->params['form']['location_id'];
            }
            if(!empty($this->params['form']['firstname'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['firstname'];
            }
            if(!empty($this->params['form']['ssn'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['ssn'];
            }

            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert added successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Added Cusomter Login alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_update_customer_login_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['id'] = $this->params['form']['id'];
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'customer_login';
            if(!empty($this->params['form']['location_id'])) {
                $this->data['Alert']['location_id'] = $this->params['form']['location_id'];
            }
            if(!empty($this->params['form']['firstname'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['firstname'];
            }
            if(!empty($this->params['form']['ssn'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['ssn'];
            }

            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
			else {
                $this->data['Alert']['send_email'] = 0;
			}
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert updated successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Updated Cusomter Login alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to updated alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_add_cus_filed_doc_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'customer_filed_document';
            if(!empty($this->params['form']['firstname'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['firstname'];
            }
            if(!empty($this->params['form']['ssn'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['ssn'];
            }
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert added successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Added Customer Filed Document alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }


    public function admin_add_program_response_status_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'program_response_status';
			$this->data['Alert']['watched_id'] = $this->params['form']['program_id'];
			$this->data['Alert']['detail'] = $this->params['form']['response_status'];
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert added successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Added Program Response Status alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to add alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

	
    public function admin_update_program_response_status_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['id'] = $this->params['form']['id'];
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'program_response_status';
			$this->data['Alert']['watched_id'] = $this->params['form']['program_id'];
			$this->data['Alert']['detail'] = $this->params['form']['response_status'];
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert updated successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Updated Program Response Status alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to update alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_add_staff_filed_document_alert() {
        if($this->RequestHandler->isAjax()) {
			$this->data['Alert']['watched_id'] = $this->params['form']['admin_id'];
			$this->data['Alert']['name'] = $this->params['form']['name'];
			$this->data['Alert']['user_id'] = $this->Auth->user('id');
			$this->data['Alert']['type'] = 'staff_filed_document';
			if(isset($this->params['form']['send_email'])) {
				$this->data['Alert']['send_email'] = 1;
			}
			else {
				$this->data['Alert']['send_email'] = 0;
			}
			if($this->Alert->save($this->data)) {
				$alert = $this->Alert->read(null, $this->params['form']['id']);
				$data['success'] = true;
				$data['message'] = 'Alert added successfully';
				$this->Transaction->createUserTransaction(
					'Alerts',
					$this->Auth->user('id'),
					$this->Auth->user('location_id'),
                    'Added Staff Filed Document alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
			}
			else {
				$data['message'] = 'Unable to add alert, please try again.';
				$data['success'] = false;
			}
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
	}

    public function admin_update_staff_filed_document_alert() {
        if($this->RequestHandler->isAjax()) {
			$this->data['Alert']['id'] = $this->params['form']['id'];
			$this->data['Alert']['watched_id'] = $this->params['form']['admin_id'];
			$this->data['Alert']['name'] = $this->params['form']['name'];
			$this->data['Alert']['user_id'] = $this->Auth->user('id');
			$this->data['Alert']['type'] = 'staff_filed_document';
			if(isset($this->params['form']['send_email'])) {
				$this->data['Alert']['send_email'] = 1;
			}
			else {
				$this->data['Alert']['send_email'] = 0;
			}
			if($this->Alert->save($this->data)) {
				$alert = $this->Alert->read(null, $this->params['form']['id']);
				$data['success'] = true;
				$data['message'] = 'Alert updated successfully';
				$this->Transaction->createUserTransaction(
					'Alerts',
					$this->Auth->user('id'),
					$this->Auth->user('location_id'),
                    'Updated Staff Filed Document alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
			}
			else {
				$data['message'] = 'Unable to update alert, please try again.';
				$data['success'] = false;
			}
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
	}
 
    public function admin_update_cus_filed_doc_alert() {
        if($this->RequestHandler->isAjax()) {
            $this->data['Alert']['id'] = $this->params['form']['id'];
            $this->data['Alert']['name'] = $this->params['form']['name'];
            $this->data['Alert']['type'] = 'customer_filed_document';
            if(!empty($this->params['form']['firstname'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['firstname'];
            }
            if(!empty($this->params['form']['ssn'])) {
                $this->data['Alert']['watched_id'] = $this->params['form']['ssn'];
            }
            $this->data['Alert']['user_id'] = $this->Auth->user('id');
            if(isset($this->params['form']['send_email'])) {
                $this->data['Alert']['send_email'] = 1;
            }
			else {
				$this->data['Alert']['send_email'] = 0;
			}
            if($this->Alert->save($this->data)) {
                $id = $this->Alert->getLastInsertId();
                $data['success'] = true;
                $data['message'] = 'Alert updated successfully';
                $this->Transaction->createUserTransaction(
                    'Alerts',
                    $this->Auth->user('id'),
                    $this->Auth->user('location_id'),
                    'Updated Customer Filed Document alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id
                );
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Unable to update alert, please try again.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

	
    public function admin_add_filed_document_alert() {

    }

    public function admin_toggle_email() {
        if($this->RequestHandler->isAjax()) {
            if(isset($this->params['form']['id'])) {
                $this->data['Alert']['id'] = $this->params['form']['id'];
                if($this->params['form']['send_email'] === 'true') {
                    $this->data['Alert']['send_email'] = 1;
                }
                else {
                    $this->data['Alert']['send_email'] = 0;
                }
                if($this->Alert->save($this->data)) {
                    $alert = $this->Alert->read(null, $this->params['form']['id']);
                    $data['success'] = true;
                    $status = 'Disabled';
                    if($alert['Alert']['send_email']) {
                        $status = 'Enabled';
                    }
                    $this->Transaction->createUserTransaction(
                        'Alerts',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        $status . ' email for alert, name: ' . $alert['Alert']['name'] . ' id: ' . $alert['Alert']['id']
                    );

                }
                else $data['success'] = false;
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_toggle_disabled() {
        if($this->RequestHandler->isAjax()) {
            if(isset($this->params['form']['id'])) {
                $this->data['Alert']['id'] = $this->params['form']['id'];
                if($this->params['form']['disabled'] === 'true') {
                    $this->data['Alert']['disabled'] = 1;
                }
                else {
                    $this->data['Alert']['disabled'] = 0;
                }
                if($this->Alert->save($this->data)) {
                    $alert = $this->Alert->read(null, $this->params['form']['id']);
                    $data['success'] = true;
                    $status = 'Disabled';
                    if(!$alert['Alert']['disabled']) {
                        $status = 'Enabled';
                    }
                    $this->Transaction->createUserTransaction(
                        'Alerts',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        $status . ' Self Sign alert, name: ' . $alert['Alert']['name'] . ' id: ' . $alert['Alert']['id']
                    );
                }
                else $data['success'] = false;
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }


    public function admin_get_alert_types() {
        if($this->RequestHandler->isAjax()) {
            // if adding a new alert add action to controller,
            // it needs to be added to the available types array
            $avaliableTypes = array(
                array(
                    'action' => 'Alerts/admin_add_self_sign_alert',
                    'label' => 'Self Sign',
                    'id' => 'selfSignAlertFormPanel'),
                array(
                    'action' => 'Alerts/admin_add_customer_details_alert',
                    'label' => 'Customer Details',
                    'id' => 'customerDetailsAlertFromPanel'),
                array(
                    'action' => 'Alerts/admin_add_queued_document_alert',
                    'label' => 'Queued Document',
                    'id' => 'queuedDocumentAlertFormPanel'),
                array(
                    'action' => 'Alerts/admin_add_staff_filed_document_alert',
                    'label' => 'Staff Filed Document',
                    'id' => 'staffFiledDocumentAlertFormPanel'),
                array(
                    'action' => 'Alerts/admin_add_self_scan_alert',
                    'label' => 'Self Scan',
                    'id' => 'selfScanAlertFormPanel'),
                array(
                    'action' => 'Alerts/admin_add_self_scan_category_alert',
                    'label' => 'Self Scan Category',
                    'id' => 'selfScanCategoryAlertFormPanel'),
                array(
                    'action' => 'Alerts/admin_add_cus_filed_doc_alert',
                    'label' => 'Customer Filed Document',
                    'id' => 'cusFiledDocAlertFormPanel'
                ),
                array(
                    'action' => 'Alerts/admin_add_program_response_status_alert',
                    'label' => 'Program Response Status',
                    'id' => 'programResponseStatusAlertFormPanel'
				),
                array(
                    'action' => 'Alerts/admin_add_customer_login_alert',
                    'label' => 'Customer Login',
                    'id' => 'customerLoginAlertFormPanel'
                ));
            $data['types'] = array();
            foreach($avaliableTypes as $k => $v) {
                if($this->Acl->check(array(
                    'model' => 'User',
                    'foreign_key' => $this->Auth->user('id')), $v['action'], '*')) {
                        $data['types'][] = array('label' => $v['label'], 'id' => $v['id']);
                }
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_delete() {
        if($this->RequestHandler->isAjax()) {
            if(isset($this->params['form']['id'])) {
                if($this->Alert->delete($this->params['form']['id'])){
                    $data['success'] = true;
                    $data['message'] = 'Alert deleted successfully';
                    $this->Transaction->createUserTransaction(
                        'Alerts',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        'Deleted Self Sign alert, id: ' . $this->params['form']['id']
                    );
                }
                else {
                    $data['success'] = false;
                    $data['message'] = 'Unable to delete alert at this time.';
                }
            }
            else {
                $data['success'] = false;
                $data['message'] = 'Invalid alert id.';
            }
            $this->set(compact('data'));
            $this->render(null, null, '/elements/ajaxreturn');
        }
    }

    public function admin_download() {
        $this->view = 'Media';
        $params = array(
            'id' => 'Atlas Alerts.msi',
            'mimeType' => array('msi' => 'application/x-msi'),
            'name' => 'Atlas Alerts',
            'download' => true,
            'extension' => 'msi',
            'path' => APP . 'storage' . DS
        );
        $this->set($params);
    }
}
