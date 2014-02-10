<?php 

class TopdfController extends AppController
{
	var $name = 'Topdf';
	var $uses = array();
	public function beforeFilter()
	{
		$this->Auth->allowedActions = array('convert', 'html');
	}

	public function convert($file = 'esign')
	{
		require( APP . 'vendors' . DS . 'MPDF54' . DS . 'mpdf.php' );

		$user_id = $this->Auth->user('id');
		$this->loadModel('User');
		$user = $this->User->find('first', array('conditions' => array('User.id' => 6)));
		
		$stylesheet = file_get_contents(APP . 'webroot' . DS . 'html' . DS . $file . '.css');
		$html = require( APP . 'webroot' . DS . 'html' . DS . $file . '.php' );

		$pdf = new mPDF();

		$pdf->WriteHtml($stylesheet, 1);
		$pdf->WriteHtml($html, 2);

		$pdf->Output();
	}

	public function html($file = 'esign')
	{
		$this->autoRender = FALSE;

		$user_id = $this->Auth->user('id');

		$this->loadModel('User');
		$user = $this->User->find('first', array('conditions' => array('User.id' => 6)));

		//$stylesheet = file_get_contents(APP . 'webroot' . DS . 'html' . DS . $file . '.css');
		$html = require( APP . 'webroot' . DS . 'html' . DS . $file . '.php' );

		echo $html;
	}
}