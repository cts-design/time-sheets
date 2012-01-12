<?php
App::import('Controller', 'Reports');
App::import('Lib', 'AtlasTestCase');
class TestReportsController extends ReportsController {
    var $autoRender = false;

    function redirect($url, $status = null, $exit = true) {
        $this->redirectUrl = $url;
    }
}

class ReportsControllerTestCase extends AtlasTestCase {
    function startTest() {
        $this->Reports =& new TestReportsController();
        $this->Reports->constructClasses();
        $this->Reports->params['controller'] = 'reports';
        $this->Reports->params['pass'] = array();
        $this->Reports->params['named'] = array();	
		$this->testController = $this->Reports;
    }

    function endTest() {
        unset($this->Reports);
        ClassRegistry::flush();
    }
    
    function testAdminTotalUnduplicatedIndividuals() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        
        // test hourly
        $data = array(
            'chartType' => 'hourly',
            'filters' => '{"admin":[],"fromDate":"2011-10-28","fromTime":"08:00:00","kiosk":[],"location":[3],"program":[],"toDate":"2011-10-28","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_unduplicated_individuals', array('form_data' => $data));
        $expected = array(
            '8am' => array(
                'Paddock' => 7
            ),
            '9am' => array(
                'Paddock' => 15
            ),
            '10am' => array(
                'Paddock' => 15
            ),
            '11am' => array(
                'Paddock' => 15
            ),
            '12pm' => array(
                'Paddock' => 7
            ),
            '1pm' => array(
                'Paddock' => 5
            ),
            '2pm' => array(
                'Paddock' => 6
            ),
            '3pm' => array(
                'Paddock' => 8
            ),
            '4pm' => array(
                'Paddock' => 2
            ),
        );

        $this->assertEqual(count($result['data']), 9);
        $this->assertEqual(array_keys($result['data']), array('8am', '9am', '10am', '11am', '12pm', '1pm', '2pm', '3pm', '4pm'));
        $this->assertEqual($result['data'], $expected);
        
        
        // test daily
        $data = array(
            'chartType' => 'daily',
            'filters' => '{"admin":[],"fromDate":"2011-10-24","fromTime":"08:00:00","kiosk":[],"location":[3],"program":[],"toDate":"2011-10-28","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_unduplicated_individuals', array('form_data' => $data));
        $expected = array(
            'Monday' => array(
                'Paddock' => 113
            ),
            'Tuesday' => array(
                'Paddock' => 163
            ),
            'Wednesday' => array(
                'Paddock' => 108
            ),
            'Thursday' => array(
                'Paddock' => 84
            ),
            'Friday' => array(
                'Paddock' => 81
            )
        );
        // FireCake::log($result);

        $this->assertEqual(count($result['data']), 5);
        $this->assertEqual(array_keys($result['data']), array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'));
        $this->assertEqual($result['data'], $expected);
        
        // test weekly
        $data = array(
            'chartType' => 'weekly',
            'filters' => '{"admin":[],"fromDate":"2011-10-03","fromTime":"08:00:00","kiosk":[],"location":[3],"program":[],"toDate":"2011-10-28","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_unduplicated_individuals', array('form_data' => $data));
        $expected = array(
            '10/03/11' => array(
                'Paddock' => 550
            ),
            '10/10/11' => array(
                'Paddock' => 483
            ),
            '10/17/11' => array(
                'Paddock' => 456
            ),
            '10/24/11' => array(
                'Paddock' => 549
            )
        );

        $this->assertEqual(count($result['data']), 4);
        $this->assertEqual(array_keys($result['data']), array('10/03/11', '10/10/11', '10/17/11', '10/24/11'));
        $this->assertEqual($result['data'], $expected);
        
        // test monthly
        $data = array(
            'chartType' => 'monthly',
            'filters' => '{"admin":[],"fromDate":"2011-10-01","fromTime":"08:00:00","kiosk":[],"location":[3],"program":[],"toDate":"2011-10-31","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_unduplicated_individuals', array('form_data' => $data));
        $expected = array(
            '10/01/11' => array(),
            '10/03/11' => array(
                'Paddock' => 550
            ),
            '10/10/11' => array(
                'Paddock' => 483
            ),
            '10/17/11' => array(
                'Paddock' => 456
            ),
            '10/24/11' => array(
                'Paddock' => 549
            )
        );

        $this->assertEqual(count($result['data']), 5);
        $this->assertEqual(array_keys($result['data']), array('10/01/11', '10/03/11', '10/10/11', '10/17/11', '10/24/11'));
        $this->assertEqual($result['data'], $expected);
        
        // test yearly
        $data = array(
            'chartType' => 'yearly',
            'filters' => '{"admin":[],"fromDate":"2011-01-01","fromTime":"08:00:00","kiosk":[],"location":[3],"program":[],"toDate":"2011-10-31","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_unduplicated_individuals', array('form_data' => $data));
        $expected = array(
            'January' => array(),
            'February' => array(),
            'March' => array(),
            'April' => array(),
            'May' => array(),
            'June' => array(
                'Paddock' => 1199
            ),
            'July' => array(
                'Paddock' => 1652
            ),
            'August' => array(
                'Paddock' => 2370
            ),
            'September' => array(
                'Paddock' => 2213
            ),
            'October' => array(
                'Paddock' => 2154
            ),
            'November' => array(),
            'December' => array()
        );

        $this->assertEqual(count($result['data']), 12);
        $this->assertEqual(array_keys($result['data']), 
            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'));
        $this->assertEqual($result['data'], $expected);
    }
    
    function testAdminTotalServices() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        
        // test hourly
        $data = array(
            'chartType' => 'hourly',
            'filters' => '{"admin":[],"fromDate":"2011-10-28","fromTime":"08:00:00","kiosk":[],"location":[],"program":[],"toDate":"2011-10-28","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_services', array('form_data' => $data));
        $expected = array(
            '8am' => array(
                'Cash Assistance (WTP)' => 1
            ),
            '9am' => array(
                'Cash Assistance (WTP)' => 2
            ),
            '10am' => array(),
            '11am' => array(),
            '12pm' => array(),
            '1pm' => array(),
            '2pm' => array(),
            '3pm' => array(),
            '4pm' => array()
        );

        $this->assertEqual(count($result['data']), 9);
        $this->assertEqual(array_keys($result['data']), array('8am', '9am', '10am', '11am', '12pm', '1pm', '2pm', '3pm', '4pm'));
        $this->assertEqual($result['data'], $expected);
        
        
        // test daily
        $data = array(
            'chartType' => 'daily',
            'filters' => '{"admin":[],"fromDate":"2011-10-24","fromTime":"08:00:00","kiosk":[],"location":[],"program":[],"toDate":"2011-10-28","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_services', array('form_data' => $data));
        $expected = array(
            'Monday' => array(
                'Register To Win A Kindle' => 1,
                'Cash Assistance (WTP)' => 14
            ),
            'Tuesday' => array(
                'Cash Assistance (WTP)' => 11,
                'Register To Win A Kindle' => 1
            ),
            'Wednesday' => array(
                'Cash Assistance (WTP)' => 21
            ),
            'Thursday' => array(
                'Cash Assistance (WTP)' => 25
            ),
            'Friday' => array(
                'Cash Assistance (WTP)' => 3
            )
        );

        $this->assertEqual(count($result['data']), 5);
        $this->assertEqual(array_keys($result['data']), array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'));
        $this->assertEqual($result['data'], $expected);
        
        // test weekly
        $data = array(
            'chartType' => 'weekly',
            'filters' => '{"admin":[],"fromDate":"2011-10-03","fromTime":"08:00:00","kiosk":[],"location":[],"program":[],"toDate":"2011-10-28","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_services', array('form_data' => $data));
        $expected = array(
            '10/03/11' => array(
                'Cash Assistance (WTP)' => 60,
                'Register To Win A Kindle' => 2
            ),
            '10/10/11' => array(
                'Cash Assistance (WTP)' => 55,
                'Register To Win A Kindle' => 10
            ),
            '10/17/11' => array(
                'Cash Assistance (WTP)' => 92,
                'Register To Win A Kindle' => 4
            ),
            '10/24/11' => array(
                'Register To Win A Kindle' => 2,
                'Cash Assistance (WTP)' => 74
            )
        );

        $this->assertEqual(count($result['data']), 4);
        $this->assertEqual(array_keys($result['data']), array('10/03/11', '10/10/11', '10/17/11', '10/24/11'));
        $this->assertEqual($result['data'], $expected);
        
        // test monthly
        $data = array(
            'chartType' => 'monthly',
            'filters' => '{"admin":[],"fromDate":"2011-10-01","fromTime":"08:00:00","kiosk":[],"location":[],"program":[],"toDate":"2011-10-31","toTime":"17:00:00"}'
        );
        

        $result = $this->testAction('/admin/reports/total_services', array('form_data' => $data));
        $expected = array(
            '10/01/11' => array(),
            '10/03/11' => array(
                'Cash Assistance (WTP)' => 60,
                'Register To Win A Kindle' => 2
            ),
            '10/10/11' => array(
                'Cash Assistance (WTP)' => 55,
                'Register To Win A Kindle' => 10
            ),
            '10/17/11' => array(
                'Cash Assistance (WTP)' => 92,
                'Register To Win A Kindle' => 4
            ),
            '10/24/11' => array(
                'Register To Win A Kindle' => 2,
                'Cash Assistance (WTP)' => 74
            )
        );

        $this->assertEqual(count($result['data']), 5);
        $this->assertEqual(array_keys($result['data']), array('10/01/11', '10/03/11', '10/10/11', '10/17/11', '10/24/11'));
        $this->assertEqual($result['data'], $expected);
        
        // test yearly
        $data = array(
            'chartType' => 'yearly',
            'filters' => '{"admin":[],"fromDate":"2011-01-01","fromTime":"08:00:00","kiosk":[],"location":[],"program":[],"toDate":"2011-10-31","toTime":"17:00:00"}'
        );
        
        $result = $this->testAction('/admin/reports/total_services', array('form_data' => $data));
        $expected = array(
            'January' => array(),
            'February' => array(),
            'March' => array(),
            'April' => array(),
            'May' => array(),
            'June' => array(
                'Register To Win A Kindle' => 8,
                'Cash Assistance (WTP)' => 67
            ),
            'July' => array(
                'Look For A Job' => 11,
                'Cash Assistance (WTP)' => 78,
                'Register To Win A Kindle' => 22
            ),
            'August' => array(
                'Cash Assistance (WTP)' => 334,
                'Register To Win A Kindle' => 24
            ),
            'September' => array(
                'Cash Assistance (WTP)' => 327,
                'Register To Win A Kindle' => 16
            ),
            'October' => array(
                'Cash Assistance (WTP)' => 291,
                'Register To Win A Kindle' => 21
            ),
            'November' => array(),
            'December' => array()
        );

        $this->assertEqual(count($result['data']), 12);
        $this->assertEqual(array_keys($result['data']), 
            array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'));
        $this->assertEqual($result['data'], $expected);
    }
    
     function testAdminGetAllLocations() {
         $result = $this->testAction('/admin/reports/get_all_locations', array('method' => 'get'));
         $expected = array(
             array(
                 'id' => 1,
                 'name' => 'Citrus',
                 'public_name' => ''
             ),
             array(
                 'id' => 2,
                 'name' => 'Levy',
                 'public_name' => ''                
             ),
             array(
                 'id' => 3,
                 'name' => 'Paddock',
                 'public_name' => ''    
             )
         );
     
         $this->assertEqual(count($result['data']['locations']), 3);
         $this->assertEqual($result['data']['locations'], $expected);
     }
     
     function testAdminGetAllPrograms() {
         $result = $this->testAction('/admin/reports/get_all_programs', array('method' => 'get'));
     
         $expected = array(
             'data' => array(
                 'programs' => array(
                     array(
                         'id' => 14,
                         'locale' => 'en_us',
                         'name' => 'Orientations'
                     ),
                     array(
                         'id' => 1,
                         'locale' => 'en_us',
                         'name' => 'Cash Assistance & Food Stamps'
                     ),
                     array(
                         'id' => 10,
                         'locale' => 'en_us',
                         'name' => 'Veteran Services'
                     ),
                     array(
                         'id' => 45,
                         'locale' => 'en_us',
                         'name' => 'Scan Documents'
                     ),
                     array(
                         'id' => 6,
                         'locale' => 'en_us',
                         'name' => 'Look For A Job'
                     ),
                     array(
                         'id' => 73,
                         'locale' => 'en_us',
                         'name' => 'Register To Win A Kindle'
                     ),
                     array(
                         'id' => 26,
                         'locale' => 'en_us',
                         'name' => 'Cash Assistance (WTP)'
                     )
                 )
             )
         );
     
         $this->assertEqual(count($result['data']['programs']), 7);
         $this->assertEqual($result, $expected);
     }
     
     function testAdminGetAllKiosks() {
         $result = $this->testAction('/admin/reports/get_all_kiosks', array('method' => 'get'));
         $expected = array(
             array(
                 'id' => 1,
                 'location_id' => 1,
                 'location_recognition_name' => 'Lorem ipsum dolor sit amet'
             ),
             array(
                 'id' => 2,
                 'location_id' => 1,
                 'location_recognition_name' => 'Lorem ipsum dolor sit amet'
             )
         );
     
         $this->assertEqual(count($result['data']['kiosks']), 2);
         $this->assertEqual($result['data']['kiosks'], $expected);
     }
     
     function testAdminGetAllAdmins() {
         $result = $this->testAction('/admin/reports/get_all_admins', array('method' => 'get'));
         $expected = array(
             array(
                 'id' => 1,
                 'firstname' => 'brandon',
                 'lastname' => 'cordell',
                 'fullname' => 'cordell, brandon'
             ),
             array(
                 'id' => 2,
                 'firstname' => 'Daniel',
                 'lastname' => 'Nolan',
                 'fullname' => 'Nolan, Daniel'
             ),
             array(
                 'id' => 20,
                 'firstname' => 'Sally',
                 'lastname' => 'Admin',
                 'fullname' => 'Admin, Sally'
             )
         );
     
         $this->assertEqual(count($result['data']['admins']), 3);
         $this->assertEqual($result['data']['admins'], $expected);
     }
     
     function testAdminGetAllKioskButtons() {
         $result = $this->testAction('/admin/reports/get_all_kiosk_buttons', array('method' => 'get'));
         $expected = array(
             array(
                 'id' => 14,
                 'locale' => 'en_us',
                 'name' => 'Orientations'
             ),
             array(
                 'id' => 1,
                 'locale' => 'en_us',
                 'name' => 'Cash Assistance & Food Stamps'
             ),
             array(
                 'id' => 10,
                 'locale' => 'en_us',
                 'name' => 'Veteran Services'
             ),
             array(
                 'id' => 45,
                 'locale' => 'en_us',
                 'name' => 'Scan Documents'   
             ),
             array(
                 'id' => 6,
                 'locale' => 'en_us',
                 'name' => 'Look For A Job'    
             ),
             array(
                 'id' => 73,
                 'locale' => 'en_us',
                 'name' => 'Register To Win A Kindle'
             ),
             array(
                 'id' => 26,
                 'locale' => 'en_us',
                 'name' => 'Cash Assistance (WTP)'    
             )
         );
     
         $this->assertEqual(count($result['data']['kiosk_buttons']), 7);
         $this->assertEqual($result['data']['kiosk_buttons'], $expected);
     }
}
