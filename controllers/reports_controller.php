<?php
class ReportsController extends AppController {
    public $name = 'Reports';
    public $components = array('RequestHandler');

    protected $filters = array();
    protected $chartType = null;
    protected $groupBy = null;
	
    public function beforeFilter() {
    		parent::beforeFilter();
		
        if (!empty($this->params['form']['filters'])) {
            $this->filters = json_decode($this->params['form']['filters'], true);
        }

        if (!empty($this->params['form']['chartType'])) {
            $this->chartType = $this->params['form']['chartType'];
        }

        if (!empty($this->params['form']['groupBy'])) {
            $this->groupBy = $this->params['form']['groupBy'];
        }
    }

    public function admin_index() {
        $user = $this->Session->read('Auth.User');
        $this->Acl->Aro->Behaviors->attach('Containable');
        $reportAco = $this->Acl->Aco->find('first', array(
            'conditions' => array('Aco.alias' => 'Reports'),
            'fields' => array('id'),
            'recursive' => -1
        ));

        $accessByRole = $this->Acl->Aro->find('all', array(
            'conditions' => array(
                'Aro.foreign_key' => $user['role_id'],
                'Aro.model' => 'Role'
            ),
            'contain' => array(
                'Aco' => array(
                    'conditions' => array('Aco.parent_id' => $reportAco['Aco']['id'])
                )
            )
        ));

        $accessByUser = $this->Acl->Aro->find('all', array(
            'conditions' => array(
                'Aro.foreign_key' => $user['id'],
                'Aro.model' => 'User'
            )
        ));
    }

    public function admin_total_unduplicated_individuals() {
        $this->loadModel('SelfSignLogArchive');
        $this->loadModel('Location');

        $data = null;

        // REMOVE
        if (!$this->RequestHandler->isAjax()) {
            $this->chartType = 'yearly';
            $this->groupBy = 'month';
            $this->filters = array(
                'fromDate' => '2010-01-01',
                'fromTime' => '08:00:00',
                'toDate' => '2011-12-31',
                'toTime' => '17:00:00'
            );
        }
        // REMOVE

        $locations = $this->Location->find('list', array(
            'fields' => array('Location.id', 'Location.name'),
            'recursive' => -1
        ));

        $query = "
            SELECT `location_id`, `level_1`, `level_2`, `level_3`, `created`, COUNT(DISTINCT(`user_id`)) 
            AS 'Total' 
            FROM `self_sign_log_archives`
            WHERE (`created` BETWEEN '{$this->filters['fromDate']}  {$this->filters['fromTime']}' 
            AND '{$this->filters['toDate']} {$this->filters['toTime']}')
        ";

        if (!empty($this->filters['location'])) {
            $tmpLocation = join(',', $this->filters['location']);
            $query .= "AND (`location_id` IN ({$tmpLocation}))";
        }

        if (!empty($this->filters['kiosk'])) {
            $tmpKiosk = join(',', $this->filters['kiosk']);
            $query .= "AND (`kiosk_id` IN ({$tmpKiosk}))";
        }

        if (!empty($this->filters['admin'])) {
            $tmpAdmin = join(',', $this->filters['admin']);
            $query .= "AND (`last_activity_admin_id` IN ({$tmpAdmin}))";
        }

        if (!empty($this->filters['program'])) {
            $tmpProgram = join(',', $this->filters['program']);
            $query .= "AND ((`level_1` IN ({$tmpProgram})) OR (`level_2` IN ({$tmpProgram})) OR OR (`level_2` IN ({$tmpProgram})))";
        }

        $query .= " GROUP BY `created`";

        $selfSignLogs = $this->SelfSignLogArchive->query($query);

        switch ($this->chartType) {
            case 'hourly':
                if ($this->groupBy === 'day') {
                    $keyString = 'ga m/d';
                    $data = array();
                    $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                    $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];

                    $days_between = $this->days_between($from, $to);

                    for ($i=0; $i < $days_between; $i++) {
                        $nextDay = strtotime(date('Y-m-d', strtotime($from)) . " +{$i} days");
                        $shortDate = date('m/d', $nextDay);
                        $dayOfWeek = date('l', $nextDay);

                        if ($dayOfWeek !== 'Saturday' && $dayOfWeek !== 'Sunday') {
                            $data["8am {$shortDate}"] = array();
                            $data["9am {$shortDate}"] = array();
                            $data["10am {$shortDate}"] = array();
                            $data["11am {$shortDate}"] = array();
                            $data["12pm {$shortDate}"] = array();
                            $data["1pm {$shortDate}"] = array();
                            $data["2pm {$shortDate}"] = array();
                            $data["3pm {$shortDate}"] = array();
                            $data["4pm {$shortDate}"] = array();
                        }
                    }
                } else if ($this->groupBy === 'hour') {
                    $keyString = 'ga';
                    $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                    $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                    $data = array(
                        '8am' => array(),
                        '9am' => array(),
                        '10am' => array(),
                        '11am' => array(),
                        '12pm' => array(),
                        '1pm' => array(),
                        '2pm' => array(),
                        '3pm' => array(),
                        '4pm' => array()
                    );
                }

                break;

            case 'daily':
                $keyString = 'l m/d';
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];

                $days_between = $this->days_between($from, $to);

                for ($i=0; $i < $days_between; $i++) {
                    $nextDay = strtotime(date('Y-m-d', strtotime($from)) . " +{$i} days");
                    $shortDate = date('l m/d', $nextDay);
                    $dayOfWeek = date('l', $nextDay);

                    if ($dayOfWeek !== 'Saturday' && $dayOfWeek !== 'Sunday') {
                        $data[$shortDate] = array();
                    }
                }

                break;

            case 'weekly':
                $keyString = 'm/d/y';
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];

                $fromDayOfWeek = date('w', strtotime($from));

                // check to see if the from date is sunday (0), monday (1), or saturday (6)
                if (!in_array($fromDayOfWeek, array(0, 1, 6))) {
                    $firstWeek = strtotime(date('m/d/y', strtotime($from)) . " last Monday");
                    $firstWeek = date('m/d/y', $firstWeek);

                    // if last monday isn't in the same month, we need to jump to the 1st
                    if (date('m', strtotime($firstWeek)) !== date('m', strtotime($from))) {
                        $numberOfDaysTillFirst = date('t', strtotime($firstWeek)) - date('j', strtotime($firstWeek));
                        $numberOfDaysTillFirst += 1;

                        $firstWeek = strtotime(date('m/d/y', strtotime($firstWeek . " + {$numberOfDaysTillFirst} days")));
                        $firstWeek = date('m/d/y', $firstWeek);
                    }

                } else {
                    $firstWeek = date('m/d/y', strtotime($from));
                }

                $nextWeek = $firstWeek;
                $finish = date('m/d/y', strtotime($to));

                $finished = false;
                do {
                    if (strtotime($finish) <= strtotime($nextWeek)) {
                        $finished = true;
                    } else {
                        $data[$nextWeek] = array();
                    }

                    $nextWeek = strtotime(date('m/d/y', strtotime($nextWeek)) . " next Monday");
                    $nextWeek = date('m/d/y', $nextWeek);
                } while (!$finished);

                break;

            case 'monthly':
                if ($this->groupBy === 'month') {
                    $keyString = 'F Y';
                    $data = array();
                    $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                    $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                    $months_between = $this->months_between($from, $to);

                    foreach ($months_between as $key => $value) {
                        $data[$value] = array();
                    }
                }

                break;

            case 'yearly':
                $keyString = 'Y';
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                break;
        }

        foreach ($selfSignLogs as $record) {
            $locationId = $record['self_sign_log_archives']['location_id'];
            $locationName = $locations[$locationId];

            $key = date($keyString, strtotime($record['self_sign_log_archives']['created']));

            if ($this->chartType === 'weekly') {
                $startOfWeek = date('m/d/y', strtotime($record['self_sign_log_archives']['created'] . " last Monday"));
                $key = $startOfWeek;
            }

            if (empty($data[$key][$locationName])) {
                $data[$key][$locationName] = 1;
            } else {
                $data[$key][$locationName] += 1;
            }
        }

        if (!$this->RequestHandler->isAjax()) {
            debug($data);die;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_total_services() {
        $this->loadModel('SelfSignLogArchive');
        $this->loadModel('MasterKioskButton');

        $data = null;

        // REMOVE
        if (!$this->RequestHandler->isAjax()) {
            $this->chartType = 'hourly';
            $this->groupBy = 'hour';
            $this->filters = array(
                'fromDate' => '2011-06-06',
                'fromTime' => '08:00:00',
                'toDate' => '2011-06-10',
                'toTime' => '17:00:00',
                'program' => array(103, 106, 105)
            );
        }
        // REMOVE

        $buttons = $this->MasterKioskButton->find('list', array(
            'fields' => array('MasterKioskButton.id', 'MasterKioskButton.name'),
            'recursive' => -1
        ));

        $query = "
            SELECT `location_id`, `kiosk_id`, `level_1`, `level_2`, `level_3`, `created` 
            FROM `self_sign_log_archives`
            WHERE (`created` BETWEEN '{$this->filters['fromDate']} {$this->filters['fromTime']}' 
            AND '{$this->filters['toDate']} {$this->filters['toTime']}')
        ";

        if (!empty($this->filters['location'])) {
            $tmpLocation = join(',', $this->filters['location']);
            $query .= "AND (`location_id` IN ({$tmpLocation}))";
        }

        if (!empty($this->filters['kiosk'])) {
            $tmpKiosk = join(',', $this->filters['kiosk']);
            $query .= "AND (`kiosk_id` IN ({$tmpKiosk}))";
        }

        if (!empty($this->filters['admin'])) {
            $tmpAdmin = join(',', $this->filters['admin']);
            $query .= "AND (`last_activity_admin_id` IN ({$tmpAdmin}))";
        }

        if (!empty($this->filters['program'])) {
            $tmpProgram = join(',', $this->filters['program']);
            $query .= "AND ((`level_1` IN ({$tmpProgram})) OR (`level_2` IN ({$tmpProgram})) OR (`level_3` IN ({$tmpProgram})))";
        }

        $selfSignLogs = $this->SelfSignLogArchive->query($query);

        switch ($this->chartType) {
            case 'hourly':
                if ($this->groupBy === 'hour') {
                    $keyString = 'ga';
                    $data = array(
                        '8am'  => array(),
                        '9am'  => array(),
                        '10am' => array(),
                        '11am' => array(),
                        '12pm' => array(),
                        '1pm'  => array(),
                        '2pm'  => array(),
                        '3pm'  => array(),
                        '4pm'  => array()
                    );
                } else if ($this->groupBy === 'day') {
                    $keyString = 'l m/d';
                    $data = array();
                    $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                    $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];

                    $days_between = $this->days_between($from, $to);

                    for ($i=0; $i < $days_between; $i++) {
                        $nextDay = strtotime(date('Y-m-d', strtotime($from)) . " +{$i} days");
                        $shortDate = date('l m/d', $nextDay);
                        $dayOfWeek = date('l', $nextDay);

                        if ($dayOfWeek !== 'Saturday' && $dayOfWeek !== 'Sunday') {
                            $data[$shortDate] = array();
                        }
                    }
                }

                break;

            case 'daily':
                $keyString = 'l m/d';
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];

                $days_between = $this->days_between($from, $to);

                for ($i=0; $i < $days_between; $i++) {
                    $nextDay = strtotime(date('Y-m-d', strtotime($from)) . " +{$i} days");
                    $shortDate = date('l m/d', $nextDay);
                    $dayOfWeek = date('l', $nextDay);

                    if ($dayOfWeek !== 'Saturday' && $dayOfWeek !== 'Sunday') {
                        $data[$shortDate] = array();
                    }
                }

                break;

            case 'weekly':
                //$data = array();
                //$from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                //$to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];

                //$nextWeek = date('m/d/y', strtotime($from));
                //$data[$nextWeek] = array();
                //$finish = date('m/d/y', strtotime($to));

                //$finished = false;
                //do {
                    //$nextWeek = strtotime(date('m/d/y', strtotime($nextWeek)) . " next Monday");
                    //$nextWeek = date('m/d/y', $nextWeek);

                    //if (strtotime($finish) <= strtotime($nextWeek)) {
                        //$finished = true;
                    //} else {
                        //$data[$nextWeek] = array();
                    //}
                //} while (!$finished);


                $keyString = 'm/d/y';
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];

                $fromDayOfWeek = date('w', strtotime($from));

                // check to see if the from date is sunday (0), monday (1), or saturday (6)
                if (!in_array($fromDayOfWeek, array(0, 1, 6))) {
                    $firstWeek = strtotime(date('m/d/y', strtotime($from)) . " last Monday");
                    $firstWeek = date('m/d/y', $firstWeek);

                    // if last monday isn't in the same month, we need to jump to the 1st
                    if (date('m', strtotime($firstWeek)) !== date('m', strtotime($from))) {
                        $numberOfDaysTillFirst = date('t', strtotime($firstWeek)) - date('j', strtotime($firstWeek));
                        $numberOfDaysTillFirst += 1;

                        $firstWeek = strtotime(date('m/d/y', strtotime($firstWeek . " + {$numberOfDaysTillFirst} days")));
                        $firstWeek = date('m/d/y', $firstWeek);
                    }

                } else {
                    $firstWeek = date('m/d/y', strtotime($from));
                }

                $nextWeek = $firstWeek;
                $finish = date('m/d/y', strtotime($to));

                $finished = false;
                do {
                    if (strtotime($finish) <= strtotime($nextWeek)) {
                        $finished = true;
                    } else {
                        $data[$nextWeek] = array();
                    }

                    $nextWeek = strtotime(date('m/d/y', strtotime($nextWeek)) . " next Monday");
                    $nextWeek = date('m/d/y', $nextWeek);
                } while (!$finished);

                break;

            case 'monthly':
                //$data = array();
                //$from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                //$to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];

                //$nextWeek = date('m/d/y', strtotime($from));
                //$data[$nextWeek] = array();
                //$finish = date('m/d/y', strtotime($to));

                //$finished = false;
                //do {
                    //$nextWeek = strtotime(date('m/d/y', strtotime($nextWeek)) . " next Monday");
                    //$nextWeek = date('m/d/y', $nextWeek);

                    //if (strtotime($finish) <= strtotime($nextWeek)) {
                        //$finished = true;
                    //} else {
                        //$data[$nextWeek] = array();
                    //}
                //} while (!$finished);

                if ($this->groupBy === 'month') {
                    $keyString = 'F Y';
                    $data = array();
                    $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                    $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                    $months_between = $this->months_between($from, $to);

                    foreach ($months_between as $key => $value) {
                        $data[$value] = array();
                    }
                }

                break;

            case 'yearly':
                $keyString = 'Y';
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                break;
        }

        foreach ($selfSignLogs as $record) {
            $level1 = $record['self_sign_log_archives']['level_1'];
            $level2 = $record['self_sign_log_archives']['level_2'];
            $level3 = $record['self_sign_log_archives']['level_3'];

            if ($this->filters['program']) {
                $level1 = (in_array($level1, $this->filters['program'])) ? $level1 : null;
                $level2 = (in_array($level2, $this->filters['program'])) ? $level2 : null;
                $level3 = (in_array($level3, $this->filters['program'])) ? $level3 : null;
            }

            $programName1 = ($level1) ? $buttons[$level1] : null;
            $programName2 = ($level2) ? $buttons[$level2] : null;
            $programName3 = ($level3) ? $buttons[$level3] : null;

            if ($this->chartType === 'weekly') {
                $created = date($keyString, strtotime($record['self_sign_log_archives']['created']));

                foreach ($data as $key => $value) {
                    $weekNum = date($keyString, strtotime($key));

                    if ($programName1 && $created === $weekNum) {
                        if (empty($data[$key][$programName1])) {
                            $data[$key][$programName1] = 1;
                        } else {
                            $data[$key][$programName1] += 1;
                        }
                    }

                    if ($programName2 && $created === $weekNum) {
                        if (empty($data[$key][$programName2])) {
                            $data[$key][$programName2] = 1;
                        } else {
                            $data[$key][$programName2] += 1;
                        }
                    }

                    if ($programName3 && $created === $weekNum) {
                        if (empty($data[$key][$programName3])) {
                            $data[$key][$programName3] = 1;
                        } else {
                            $data[$key][$programName3] += 1;
                        }
                    }
                }
            } else {
                $key = date($keyString, strtotime($record['self_sign_log_archives']['created']));

                if ($programName1) {
                    if (empty($data[$key][$programName1])) {
                        $data[$key][$programName1] = 1;
                    } else {
                        $data[$key][$programName1] += 1;
                    }
                }

                if ($programName2) {
                    if (empty($data[$key][$programName2])) {
                        $data[$key][$programName2] = 1;
                    } else {
                        $data[$key][$programName2] += 1;
                    }
                }

                if ($programName3) {
                    if (empty($data[$key][$programName3])) {
                        $data[$key][$programName3] = 1;
                    } else {
                        $data[$key][$programName3] += 1;
                    }
                }
            }
        }

        if (!$this->RequestHandler->isAjax()) {
            debug($data);die;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_get_all_locations() {
        $this->loadModel('Location');

        $locations = $this->Location->find('all', array(
            'fields' => array(
                'Location.id', 'Location.name', 'Location.public_name'
            ),
            'recursive' => -1
        ));

        foreach ($locations as $location) {
            $data['locations'][] = $location['Location'];
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_get_all_programs() {
        $this->loadModel('MasterKioskButton');

        $programs = $this->MasterKioskButton->find('all', array(
            'conditions' => array(
                'MasterKioskButton.deleted <>' => '1'
            ),
            'fields' => array(
                'MasterKioskButton.id', 'MasterKioskButton.name'
            ),
            'recursive' => 1
        ));

        foreach ($programs as $program) {
            $data['programs'][] = $program['MasterKioskButton'];
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_get_all_kiosks() {
        $this->loadModel('Kiosk');

        $kiosks = $this->Kiosk->find('all', array(
            'fields' => array(
                'Kiosk.id', 'Kiosk.location_id', 'Kiosk.location_recognition_name'
            ),
            'recursive' => -1
        ));

        foreach ($kiosks as $kiosk) {
            $data['kiosks'][] = $kiosk['Kiosk'];
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_get_all_admins() {
        $this->loadModel('User');

        $admins = $this->User->find('all', array(
            'conditions' => array(
                'User.role_id' => array(2, 3)
            ),
            'fields' => array(
                'User.id', 'User.firstname', 'User.lastname'
            ),
            'recursive' => -1
        ));

        foreach ($admins as $admin) {
            $admin['User']['fullname'] = "{$admin['User']['lastname']}, {$admin['User']['firstname']}";
            $data['admins'][] = $admin['User'];
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_get_all_kiosk_buttons() {
        $this->loadModel('MasterKioskButton');

        $buttons = $this->MasterKioskButton->find('all', array(
            'conditions' => array(
                'MasterKioskButton.deleted' => 0
            ),
            'fields' => array(
                'MasterKioskButton.id', 'MasterKioskButton.name'
            ),
            'recursive' => -1
        ));

        foreach ($buttons as $button) {
            $data['kiosk_buttons'][] = $button['MasterKioskButton'];
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_create_filter() {
        $this->loadModel('ReportFilter');
        $data = array();
        $params = json_decode($this->params['form']['report_filters'], true);

        $filterData = array(
            'name' => $params['name'],
            'chart_breakdown' => $params['chart_breakdown'],
            'date_range' => $params['date_range'],
            'group_by' => $params['group_by'],
            'display_as' => $params['display_as'],
            'admin' => (is_array($params['admin'])) ? implode(', ', $params['admin']) : '',
            'kiosk' => (is_array($params['kiosk'])) ? implode(', ', $params['kiosk']) : '',
            'location' => (is_array($params['location'])) ? implode(', ', $params['location']) : '',
            'program' => (is_array($params['program'])) ? implode(', ', $params['program']) : ''
        );

        if ($this->ReportFilter->save($filterData)) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_read_filters() {
        $this->loadModel('ReportFilter');
        $data = array();

        $filters = $this->ReportFilter->find('all');

        foreach ($filters as $key => $value) {
            $data['report_filters'][] = $value['ReportFilter'];
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_update_filter() {
        $this->loadModel('ReportFilter');
        $data = array();
        $params = json_decode($this->params['form']['report_filters'], true);

        $filterData = array(
            'name' => $params['name'],
            'chart_breakdown' => $params['chart_breakdown'],
            'date_range' => $params['date_range'],
            'group_by' => $params['group_by'],
            'display_as' => $params['display_as'],
            'admin' => (is_array($params['admin'])) ? implode(', ', $params['admin']) : '',
            'kiosk' => (is_array($params['kiosk'])) ? implode(', ', $params['kiosk']) : '',
            'location' => (is_array($params['location'])) ? implode(', ', $params['location']) : '',
            'program' => (is_array($params['program'])) ? implode(', ', $params['program']) : ''
        );

        $this->ReportFilter->read(null, $params['id']);
        $this->ReportFilter->set($filterData);

        if ($this->ReportFilter->save()) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_destroy_filter() {
        $this->loadModel('ReportFilter');
        $data = array();
        $params = json_decode($this->params['form']['report_filters'], true);

        if ($this->ReportFilter->delete($params['id'])) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    // expects $from and $to to be valid date
    // strtotime will accept
    private function days_between($from, $to) {
        return ceil(abs(strtotime($to) - strtotime($from)) / 86400);
    }

    // expects $from and $to to be valid date
    // strtotime will accept
    private function months_between($from, $to) {
        $fromTime  = strtotime($from); 
        $toTime  = strtotime($to); 
        $my     = date('mY', $toTime); 

        $months = array(date('F Y', $fromTime)); 

        while($fromTime < $toTime) { 
            $fromTime = strtotime(date('Y-m-d', $fromTime).' +1 month'); 
            if(date('mY', $fromTime) != $my && ($fromTime < $toTime)) 
                $months[] = date('F Y', $fromTime); 
        } 

        $months[] = date('F Y', $toTime); 
        return $months; 
    }
}

