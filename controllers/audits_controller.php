<?php

App::import('Vendor', 'chromephp/chromephp');
class AuditsController extends AppController {
    public $name = 'Audits';

    public $invalidUsers = array();

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_index() {}

    public function admin_read() {
        $audits = $this->Audit->find('all');
        $data = array();

        foreach ($audits as $key => $value) {
            $data['audits'][] = $value['Audit'];
        }

        $data['success'] = true;

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_create() {
        $params = $this->params['form']['audits'];

        // parse customer list
        $auditCustomers = $this->parseCustomerList($params['customers']);
        unset($params['customers']);

        $this->data['Audit'] = $params;
        $this->data['User'] = $auditCustomers;

        if ($this->Audit->saveAll($this->data)) {
            $id = $this->Audit->getLastInsertId();
            $data['success'] = true;
            $data['message'] = 'Audit added successfully';
            $this->Transaction->createUserTransaction(
                'Audits',
                $this->Auth->user('id'),
                $this->Auth->user('location_id'),
                'Added Audit ' . $this->data['Audit']['name'] . ', id: ' . $id
            );
        } else {
            $data['success'] = false;
            $data['message'] = 'Unable to add audit, please try again';
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    private function createAuditors($amount) {
        
    }

    private function parseCustomerList($list) {
        $customers = array();
        $customerSSNs = explode('\n', $list);

        // we don't want to pull every associated model
        $this->Audit->User->recursive = -1;

        foreach ($customerSSNs as $customerSSN) {
            $customer = $this->Audit->User->find('first', array(
                'conditions' => array('User.ssn' => $customerSSN),
                'fields' => array('User.id')
            ));

            if ($customer) {
                array_push($customers, $customer['User']['id']);
            } else {
                array_push($this->invalidUsers, $customerSSN);
            }
        }

        return $customers;
    }
}

