<?php

/**
 * Description of time_sheets_controller
 *
 * @author shawnsandy
 */
class TimeSheetsController extends AppController {
 var $name = 'TimeSheets';
    //dont use a database
    var $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        //$this->layout = "time_sheet";
        $this->Auth->allow('index');
    }

    public function index() {
        //list user timesheets for current logged in supervisor
        //if user is staff list timesheets for all users
    }
    
    public function add_supervisor() {
        //check if logged in user is staff
        //add/manage supervisors        
    }
    
    public function add_user() {
        //supervisors login to add/manage users
    }
    
    public function add_time(){
        //add user time
        //restict to curent week       
    }
    
    public function timesheet_report() {
        
    }

}
