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
}

