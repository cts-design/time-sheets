<?php
 
App::import('Component', 'Email');
            
class EmailLogShell extends Shell {
	var $components = array('Email');
	function main() {
		$this->Email  =& new EmailComponent();
		$this->Email->to = 'daniel@ctsfla.com';
		$this->Email->from = Configure::read('System.email');
		$this->Email->subject = 'CAKEPHP Logs for ' . Configure::read('Company.name');
	    $this->Email->attachments = array(
	    	LOGS . 'debug.log',
	    	LOGS . 'error.log'
	    );
		$message = 'Here are the cake logs you requested master.' . "\r\n\r\n" . '--CakeSlave';
		if($this->Email->send($message)) {
			echo 'Logs mailed' . "\n";
		}
		else {
			echo 'Unable to mail logs at this time.' . "\n";
		}
	}
}