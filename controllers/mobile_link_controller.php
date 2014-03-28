<?php

/*
	This controller handels all pages assocated with upload_docs
*/

class MobileLinkController extends AppController {

	public $name = 'MobileLink';
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('show', 'upload', 'wait', 'test_mail', 'success', 'show_code', 'confirm');
		$this->layout = 'mobile';
	}

	public function show() {
		$this->layout = 'default';
		if( $this->RequestHandler->isPost() )
		{
			$user_id = $this->Auth->user('id');

			if(!$user_id)
			{
				$user_id = 1;
			}

			$link['MobileLink']['passcode'] = rand(1000, 9999);
			$link['MobileLink']['user_id'] = $user_id;
			$link['MobileLink']['provider'] = $this->params['form']['provider'];
			$link['MobileLink']['phone_number'] = $this->params['form']['phone_number'];

			$this->MobileLink->create();
			$this->MobileLink->save( $link );

			$mobile_link_id = $this->MobileLink->getLastInsertID();

			//Send email to users's phone
			$to 		= $link['MobileLink']['phone_number'] . $link['MobileLink']['provider'];
			$headers	= "From: " . Configure::read('System.email') . "\r\n";
			$sms_link	= Router::url('/mobile_link/confirm/' . $mobile_link_id, true);
			$message 	= "To upload your document, click the link and follow the instructions on your phone: " . $sms_link;
			$is_sent 	= mail($to, "", $message, $headers);

			$this->Session->setFlash('You should recieve a text message shortly, if you do not recieve one, re-enter your phone number', null, null, 'flash_success');
			$this->redirect('/mobile_link/show_code/' . $mobile_link_id);
		}
	}

	public function show_code($mobile_link_id = NULL) {
		$link = $this->MobileLink->findById($mobile_link_id);
		$this->set(compact('link'));
	}

	public function upload($id = NULL) {

		$this->loadModel('MobileLink');

		if($id == NULL)
		{
			$this->Session->setFlash('The identification for this user was not found in the url, resend the mobile picture upload link', NULL, NULL, 'flash_error');
		}

		$mobile_link = $this->MobileLink->find('first', array('conditions' => array(
			'MobileLink.id' => $id
		)));

		if(!$mobile_link)
		{
			$this->set('mobile_link_id', NULL);
			$this->Session->setFlash('That identification was not found, resend your personalized text message to your phone and repeat the process', NULL, NULL, 'flash_error');
		}
		else
		{
			$this->set('mobile_link_id', $mobile_link['MobileLink']['id']);
		}

		if($this->RequestHandler->isPost() && !empty($this->data))
		{
			$this->loadModel('QueuedDocument');
			$is_saved = $this->QueuedDocument->quickQueueDocument($mobile_link['MobileLink']['user_id'], $this->data['MobileLink']['submittedfile']);
			

			if($is_saved)
			{
				$this->redirect('/mobile_link/success');
			}
			else
			{
				$this->Session->setFlash('The Document Failed to upload, please try again', null, null, 'flash_error');
			}
		}

	}

	public function success() {

	}

	public function confirm($mobile_link_id = null) {
		$link = $this->MobileLink->findById($mobile_link_id);

		if( $this->RequestHandler->isPost() )
		{
			$passcode = $this->params['form']['passcode'];
			if($passcode != $link['MobileLink']['passcode'])
			{
				$this->Session->setFlash('That passcode is incorrect, please try again', null, null, 'flash_error');
			}
			else
			{
				$this->redirect('/mobile_link/upload/' . $mobile_link_id);
			}
		}
		else
		{
			$passcode = "";
		}

		$this->set('passcode', $passcode);
	}

	public function wait($slug = NULL) {

	}
}