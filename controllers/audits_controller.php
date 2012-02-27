<?php

App::import('Vendor', 'chromephp/chromephp');
class AuditsController extends AppController {
    public $name = 'Audits';

    public function beforeFilter() {
        parent::beforeFilter();
    }

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
        App::import('Vendor', 'chromephp/chromephp');
        $params = $this->params['form']['audits'];

        $this->data['Audit'] = $params;
        if ($this->Audit->save($this->data)) {
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
}

