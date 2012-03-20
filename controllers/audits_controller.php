<?php

class AuditsController extends AppController {
    public $name = 'Audits';

    public $components = array('Excel');

    public $auditors = array();
    public $invalidCustomers = array();
    public $validCustomers = array();

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_index() {}

    public function admin_read() {
        $this->Audit->Behaviors->attach('Containable');
        $audits = $this->Audit->find('all', array(
            'fields' => array('Audit.*'),
            'contain' => array(
                'AuditsAuditor',
                'User' => array(
                    'fields' => array('User.id', 'User.ssn', 'User.firstname', 'User.lastname')
                )
            )
        ));
        $data = array();

        if ($audits) {
            $data['success'] = true;

            foreach ($audits as $key => $value) {
                $data['audits'][] = $value['Audit'];

                if (!empty($value['User'])) {
                    $data['audits'][$key]['users'] = $value['User'];
                }

                if (!empty($value['Auditor'])) {
                    $data['audits'][$key]['auditors'] = $value['Auditor'];
                }
            }
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_create() {
        $params = json_decode($this->params['form']['audits'], true);

        // parse customer list
        $auditCustomers = $this->parseCustomerList($params['customers']);
        $auditors = $this->createAuditors($params['number_of_auditors']);
        $params['number_of_customers'] = count($auditCustomers) + count($this->invalidCustomers);
        unset($params['customers']);

        $this->data['Audit'] = $params;
        $this->data['Auditor'] = $auditors;
        $this->data['User'] = $auditCustomers;

        $this->data['Audit']['start_date'] = date('Y-m-d', strtotime($params['start_date']));
        $this->data['Audit']['end_date'] = date('Y-m-d', strtotime($params['end_date']));

        $saved = $this->Audit->save($this->data);

        if ($saved) {
            $id = $this->Audit->getLastInsertId();
            $data['success'] = true;
            $data['message'] = 'Audit added successfully';
            $this->Transaction->createUserTransaction(
                'Audits',
                $this->Auth->user('id'),
                $this->Auth->user('location_id'),
                'Added Audit ' . $this->data['Audit']['name'] . ', id: ' . $id
            );
            $this->createExcelReports($id);
        } else {
            $data['success'] = false;
            $data['message'] = 'Unable to add audit, please try again';
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_update() {
        $params = json_decode($this->params['form']['audits'], true);

        $this->Audit->read(null, $params['id']);
        $this->Audit->set($params);

        if ($this->Audit->save()) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_users() {
        $auditId = $this->params['url']['audit_id'];
        $data = array();

        $this->Audit->Behaviors->attach('Containable');
        $audit = $this->Audit->find('all', array(
            'fields' => array('Audit.*'),
            'conditions' => array('Audit.id' => $auditId),
            'contain' => array(
                'User' => array(
                    'fields' => array('User.id', 'User.ssn', 'User.firstname', 'User.lastname'),
                )
            )
        ));

        if ($audit) {
            $data['success'] = true;

            foreach ($audit as $a) {
                foreach ($a['User'] as $user) {
                    $data['users'][] = $user;
                }
            }
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_filed_docs() {
        $customerId = $this->params['url']['customer_id'];
        $data = array();

        $this->Audit->User->Behaviors->attach('Containable');
        $user = $this->Audit->User->find('first', array(
            'fields' => array('User.id'),
            'conditions' => array('User.id' => $customerId),
            'contain' => array(
                'FiledDocument' => array(
                    'fields' => array(
                        'FiledDocument.id',
                        'FiledDocument.filename',
                        'FiledDocument.cat_1',
                        'FiledDocument.cat_2',
                        'FiledDocument.cat_3',
                        'FiledDocument.description',
                        'FiledDocument.created'
                    ),
                    'Cat1',
                    'Cat2',
                    'Cat3'
                )
            )
        ));

        if ($user) {
            $data['success'] = true;

            foreach ($user['FiledDocument'] as $doc) {
                $doc['cat_1'] = ($doc['cat_1'] != 0) ? $doc['Cat1']['name'] : null;
                $doc['cat_2'] = ($doc['cat_2'] != 0) ? $doc['Cat2']['name'] : null;
                $doc['cat_3'] = ($doc['cat_3'] != 0) ? $doc['Cat3']['name'] : null;
                unset($doc['Cat1'], $doc['Cat2'], $doc['Cat3']);
                $data['filed_docs'][] = $doc;
            }
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_user_transactions() {
        $customerId = $this->params['url']['customer_id'];
        $data = array();

        $this->Audit->User->Behaviors->attach('Containable');
        $user = $this->Audit->User->find('first', array(
            'fields' => array('User.id'),
            'conditions' => array('User.id' => $customerId),
            'contain' => array(
                'UserTransaction' => array(
                    'fields' => array(
                        'UserTransaction.id',
                        'UserTransaction.location',
                        'UserTransaction.module',
                        'UserTransaction.details',
                        'UserTransaction.created'
                    )
                )
            )
        ));

        if ($user) {
            $data['success'] = true;

            foreach ($user['UserTransaction'] as $trans) {
                $data['user_transactions'][] = $trans;
            }
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    private function createAuditors($count) {
        $auditorRole = $this->Audit->User->Role->query("SELECT * FROM `roles` WHERE `name` REGEXP 'auditor'");
        $auditorRoleId = $auditorRole[0]['roles']['id'];

        $this->Audit->User->setValidation('auditor');
        $maxId = $this->Audit->User->find('first', array(
            'fields' => array('MAX(User.id) as max')
        ));
        $id = $maxId[0]['max'];
        $auditorIds = array();

        for ($i = 0; $i < $count; $i++) {
            $id = $id + 1;
            $username = 'auditor' . $id;
            $password = $this->generatePassword();

            $this->Audit->User->create();
            $userData = array(
                'User' => array(
                    'role_id' => $auditorRoleId,
                    'username' => $username,
                    'firstname' => $username,
                    'pass' => $password
                )
            );

            $user = $this->Audit->User->save($userData);

            if ($user) {
                $lastId = $this->Audit->User->getLastInsertId();
                array_push($this->auditors, array($username, $password));
                array_push($auditorIds, $lastId);
            } else {
                FireCake::log($this->Audit->User->invalidFields());
                $i--;
            }
        }

        FireCake::log($this->auditors);
        return $auditorIds;
    }

    private function parseCustomerList($list) {
        $customers = array();
        $customerSSNs = preg_split("/[\s]+/", $list);

        // we don't want to pull every associated model
        $this->Audit->User->recursive = -1;

        foreach ($customerSSNs as $customerSSN) {
            $customer = $this->Audit->User->find('first', array(
                'conditions' => array('User.ssn' => $customerSSN),
                'fields' => array('User.id', 'User.firstname', 'User.lastname')
            ));

            if ($customer) {
                $doc_count = $this->Audit->User->FiledDocument->find('count', array(
                    'conditions' => array('FiledDocument.user_id' => $customer['User']['id'])
                ));

                $activity_count = $this->Audit->User->UserTransaction->find('count', array(
                    'conditions' => array('UserTransaction.user_id' => $customer['User']['id'])
                ));

                array_push($this->validCustomers, array(
                    'ssn' => $customerSSN,
                    'lastname' => $customer['User']['lastname'],
                    'firstname' => $customer['User']['firstname'],
                    'document_count' => $doc_count,
                    'activity_count' => $activity_count
                ));
                array_push($customers, $customer['User']['id']);
            } else {
                array_push($this->invalidCustomers, array('ssn' => $customerSSN));
            }
        }

        return $customers;
    }

    public function createExcelReports($auditId) {
        $this->Excel->saveDirectory(Configure::read('Document.storage.path') . 'audit_reports');

        // auditor report
        $this->Excel->create("Auditors for audit id: $auditId");
        $this->Excel->setWorksheetTitle('Auditors');
        $this->Excel->setHeaders(array('Username', 'Password'));
        $this->Excel->setData($this->auditors);
        $this->Excel->save("audit_auditors_$auditId");

        // customer report - customers
        $this->Excel->create("Customers for audit id: $auditId");
        $this->Excel->setWorksheetTitle('Customers');
        $this->Excel->setHeaders(array(
            'SSN',
            'Last Name',
            'First Name',
            'Document Count',
            'Activity Count'
        ));
        $this->Excel->setData($this->validCustomers);

        // add not found worksheet
        $this->Excel->addWorksheet('NOT FOUND');
        $this->Excel->setHeaders(array('SSN'));
        $this->Excel->setData($this->invalidCustomers);
        $this->Excel->save("audit_customers_$auditId");
    }

    private function generatePassword($length = 8) {
        $charList = '23456789bcdfghjkmnpqrtvwxyzBCDFGHJKMNPQRTVWXYZ';
        $maxLength = strlen($charList);
        $password = '';

        if ($length > $maxLength) {
            $length = $maxLength;
        }

        $i = 0;
        while ($i < $length) {
            $char = substr($charList, mt_rand(0, $maxLength - 1), 1);

            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }

        return $password;
    }
}

