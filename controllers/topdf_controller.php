<?php 

class TopdfController extends AppController
{
	var $name = 'Topdf';
	var $uses = array();
	public function beforeFilter()
	{
		$this->Auth->allowedActions = array('index');
	}

	public function index()
	{
		$file = $this->params['url']['pdf_name'];

		require( APP . 'vendors' . DS . 'MPDF54' . DS . 'mpdf.php' );

		$user_id = $this->Auth->user('id');
		$stylesheet = file_get_contents(APP . 'webroot' . DS . 'html' . DS . 'esign.css');
		$html = require( APP . 'webroot' . DS . 'html' . DS . $file . '.php' );

		$pdf = new mPDF();

		$pdf->WriteHtml($stylesheet, 1);
		$pdf->WriteHtml($html, 2);

		$pdf->Output();
	}
}