<?php
class HelpDeskTicketsController extends AppController {
	
	public $components = array('Email');

	public function admin_index() {
		if(!empty($this->data)) {
			$this->HelpDeskTicket->set($this->data);
			if($this->HelpDeskTicket->validates()) {
				$this->Email->delivery = 'mail';
				$this->Email->from = $this->data['HelpDeskTicket']['first_name'] . 
					' ' . $this->data['HelpDeskTicket']['last_name'] . '<' . $this->data['HelpDeskTicket']['email'] . '>';
				$this->Email->to = 'helpdesk@atlasforworkforce.com';
				$this->Email->subject = $this->data['HelpDeskTicket']['title'];
				$message = 'First Name: ' . $this->data['HelpDeskTicket']['first_name'] . " <br />\r\n";
				$message .= 'Last Name: ' . $this->data['HelpDeskTicket']['last_name'] . " <br />\r\n";
				$message .= 'Email: ' . $this->data['HelpDeskTicket']['email'] . " <br />\r\n";
				$message .= 'URL: ' . $this->data['HelpDeskTicket']['url'] . " <br />\r\n";
				$message .= 'Browser: ' . $this->data['HelpDeskTicket']['browser'] . " <br />\r\n";
				$message .= 'Operating System: ' . $this->data['HelpDeskTicket']['operating_system'] . " <br />\r\n";
				$message .= 'Issue: ' . $this->data['HelpDeskTicket']['issue'] . " <br />\r\n";
				if(isset($this->data['HelpDeskTicket']['screen_shot']['name'])) {
					$this->Email->attachments = array($this->data['HelpDeskTicket']['screen_shot']['name'] => $this->data['HelpDeskTicket']['screen_shot']['tmp_name']);
				}
				if($this->Email->send($message)) {
					$this->Session->setFlash('Ticket was sent successfully.', 'flash_success');
					$this->redirect(array('action' => 'index', 'admin' => true));
				}
				else {
					$this->Session->setFlash('Unable to send ticket at this time.', 'flash_failure');
					$this->redirect(array('action' => 'index', 'admin' => true));
				}
			}
			else {
				$this->Session->setFlash('Help desk ticket form has errors.', 'flash_failure');
			}
		}
	}	
}
