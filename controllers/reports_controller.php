<?php
class ReportsController extends AppController {
    public $name = 'Reports';
    public $uses = array();

    protected $filters = array();
    protected $chartType = null;
	
    public function beforeFilter() {
		parent::beforeFilter();
		
        if (!empty($this->params['form']['filters'])) {
            $this->filters = json_decode($this->params['form']['filters'], true);
        }
        
        if (!empty($this->params['form']['chartType'])) {
            $this->chartType = $this->params['form']['chartType'];
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

                foreach ($selfSignLogs as $record) {
                    $locationId = $record['self_sign_log_archives']['location_id'];
                    $locationName = $locations[$locationId];
                    $key = date('ga', strtotime($record['self_sign_log_archives']['created']));

                    if (empty($data[$key][$locationName])) {
                        $data[$key][$locationName] = 1;
                    } else {
                        $data[$key][$locationName] += 1;
                    }
                }

                break;
                
            case 'daily':
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                
                $curDate = $from;
                $toDateCheck = date('Y-m-d', strtotime($to));
                
                for ($i=0; $i < 5; $i++) {
                    $dayOfWeek = strtotime(date('Y-m-d', strtotime($curDate)) . " +{$i} days");
                    $dayOfWeek = date('l', $dayOfWeek);
                    $data[$dayOfWeek] = array();
                }
                
                foreach ($selfSignLogs as $record) {
                    $locationId = $record['self_sign_log_archives']['location_id'];
                    $locationName = $locations[$locationId];
                    $key = date('l', strtotime($record['self_sign_log_archives']['created']));
                    
                    if (empty($data[$key][$locationName])) {
                        $data[$key][$locationName] = 1;
                    } else {
                        $data[$key][$locationName] += 1;
                    }
                }
                
                break;
            
            case 'weekly':
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                
                $nextWeek = date('m/d/y', strtotime($from));
                $data[$nextWeek] = array();
                $finish = date('m/d/y', strtotime($to));
                
                $finished = false;
                do {
                    $nextWeek = strtotime(date('m/d/y', strtotime($nextWeek)) . " next Monday");
                    $nextWeek = date('m/d/y', $nextWeek);
                    
                    if (strtotime($finish) <= strtotime($nextWeek)) {
                        $finished = true;
                    } else {
                        $data[$nextWeek] = array();
                    }
                } while (!$finished);
                
                foreach ($selfSignLogs as $record) {
                    $locationId = $record['self_sign_log_archives']['location_id'];
                    $locationName = $locations[$locationId];
                    $locationKey = date('W', strtotime($record['self_sign_log_archives']['created']));
                    
                    foreach ($data as $key => $value) {
                        $weekNum = date('W', strtotime($key));
                        if ($locationKey === $weekNum) {
                            if (empty($data[$key][$locationName])) {
                                $data[$key][$locationName] = 1;
                            } else {
                                $data[$key][$locationName] += 1;
                            }
                        }
                    }
                }
                
                break;
                
            case 'monthly':
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
            
                $nextWeek = date('m/d/y', strtotime($from));
                $data[$nextWeek] = array();
                $finish = date('m/d/y', strtotime($to));
            
                $finished = false;
                do {
                    $nextWeek = strtotime(date('m/d/y', strtotime($nextWeek)) . " next Monday");
                    $nextWeek = date('m/d/y', $nextWeek);
                
                    if (strtotime($finish) <= strtotime($nextWeek)) {
                        $finished = true;
                    } else {
                        $data[$nextWeek] = array();
                    }
                } while (!$finished);
            
                foreach ($selfSignLogs as $record) {
                    $locationId = $record['self_sign_log_archives']['location_id'];
                    $locationName = $locations[$locationId];
                    $locationKey = date('W', strtotime($record['self_sign_log_archives']['created']));
                
                    foreach ($data as $key => $value) {
                        $weekNum = date('W', strtotime($key));
                        if ($locationKey === $weekNum) {
                            if (empty($data[$key][$locationName])) {
                                $data[$key][$locationName] = 1;
                            } else {
                                $data[$key][$locationName] += 1;
                            }
                        }
                    }
                }
                
                break;
                
            case 'yearly':
                $data = array(
                    'January' => array(),
                    'February' => array(),
                    'March' => array(),
                    'April' => array(),
                    'May' => array(),
                    'June' => array(),
                    'July' => array(),
                    'August' => array(),
                    'September' => array(),
                    'October' => array(),
                    'November' => array(),
                    'December' => array()
                );
                
                foreach ($selfSignLogs as $record) {
                    $locationId = $record['self_sign_log_archives']['location_id'];
                    $locationName = $locations[$locationId];
                    $locationKey = date('F', strtotime($record['self_sign_log_archives']['created']));
                    
                    if (empty($data[$locationKey][$locationName])) {
                        $data[$locationKey][$locationName] = 1;
                    } else {
                        $data[$locationKey][$locationName] += 1;
                    }
                }                
                break;
                
            default:
                # code...
                break;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_total_services() {
        $this->loadModel('SelfSignLogArchive');
        $this->loadModel('MasterKioskButton');

        $data = null;
                
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

                    $key = date('ga', strtotime($record['self_sign_log_archives']['created']));

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

                break;
                
            case 'daily':
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                
                $curDate = $from;
                $toDateCheck = date('Y-m-d', strtotime($to));
                
                for ($i=0; $i < 5; $i++) {
                    $dayOfWeek = strtotime(date('Y-m-d', strtotime($curDate)) . " +{$i} days");
                    $dayOfWeek = date('l', $dayOfWeek);
                    $data[$dayOfWeek] = array();
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

                    $key = date('l', strtotime($record['self_sign_log_archives']['created']));

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
                
                break;
            
            case 'weekly':
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
                
                $nextWeek = date('m/d/y', strtotime($from));
                $data[$nextWeek] = array();
                $finish = date('m/d/y', strtotime($to));
                
                $finished = false;
                do {
                    $nextWeek = strtotime(date('m/d/y', strtotime($nextWeek)) . " next Monday");
                    $nextWeek = date('m/d/y', $nextWeek);
                    
                    if (strtotime($finish) <= strtotime($nextWeek)) {
                        $finished = true;
                    } else {
                        $data[$nextWeek] = array();
                    }
                } while (!$finished);
                
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

                    $created = date('W', strtotime($record['self_sign_log_archives']['created']));

                    foreach ($data as $key => $value) {
                        $weekNum = date('W', strtotime($key));
                        
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
                }
                
                break;
                
            case 'monthly':
                $data = array();
                $from = $this->filters['fromDate'] . ' ' . $this->filters['fromTime'];
                $to   = $this->filters['toDate'] . ' ' . $this->filters['toTime'];
            
                $nextWeek = date('m/d/y', strtotime($from));
                $data[$nextWeek] = array();
                $finish = date('m/d/y', strtotime($to));
            
                $finished = false;
                do {
                    $nextWeek = strtotime(date('m/d/y', strtotime($nextWeek)) . " next Monday");
                    $nextWeek = date('m/d/y', $nextWeek);
                
                    if (strtotime($finish) <= strtotime($nextWeek)) {
                        $finished = true;
                    } else {
                        $data[$nextWeek] = array();
                    }
                } while (!$finished);
            
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

                    $created = date('W', strtotime($record['self_sign_log_archives']['created']));

                    foreach ($data as $key => $value) {
                        $weekNum = date('W', strtotime($key));
                        
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
                }

                break;
                
            case 'yearly':
                $data = array(
                    'January' => array(),
                    'February' => array(),
                    'March' => array(),
                    'April' => array(),
                    'May' => array(),
                    'June' => array(),
                    'July' => array(),
                    'August' => array(),
                    'September' => array(),
                    'October' => array(),
                    'November' => array(),
                    'December' => array()
                );
                
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

                    $key = date('F', strtotime($record['self_sign_log_archives']['created']));

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
                            
                break;
                
            default:
                # code...
                break;
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
    
    public function admin_create_filters() {

        $data = array();
        
        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_read_filters() {

        $data = array();
        
        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_destroy_filters() {

        $data = array();
        
        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }
}

