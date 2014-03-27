<?php

/*
	This controller handels all pages assocated with upload_docs
*/

class MobileLinkController extends AppController {

	public $name = 'MobileLink';
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('show', 'upload', 'wait', 'test_mail');
	}

	public function show() {

		if( $this->RequestHandler->isPost() )
		{
			$user_id = $this->Auth->user('id');

			if(!$user_id)
			{
				$user_id = 1;
			}

			$link['MobileLink']['slug'] = uniqid();
			$link['MobileLink']['user_id'] = $user_id;
			$link['MobileLink']['provider'] = $this->params['form']['provider'];
			$link['MobileLink']['phone_number'] = $this->params['form']['phone_number'];

			$this->MobileLink->create();
			$this->MobileLink->save( $link );

			//Send email to users's phone
			$to 		= $link['MobileLink']['phone_number'] . $link['MobileLink']['provider'];
			$headers	= "From: Joseph Shering <flynnarite@gmail.com>\r\n";

			$sms_link	= Router::url('/mobile_links/upload/' . $link['MobileLink']['slug'], true);
			$message 	= "To upload your document, click the link and follow the instructions on your phone: " . $sms_link;

			$is_sent = mail($to, "", $message, $headers);

			var_dump($to);
			//$this->redirect('/mobile_link/wait');
		}
	}

	public function upload() {

	}

	public function wait($slug = null) {

	}

	public function test_mail() {
		$this->autoRender = false;
		$is_sent = mail('3522476824@vtext.com', '', 'This is the message', "From: flynnarite@gmail.com\r\n");

		var_dump($is_sent);
	}
}