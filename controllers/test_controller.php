<?php

class TestController extends AppController
{
	var $uses = array();
	var $name = 'Test';

	public function beforeFilter()
	{
		$this->Auth->allowedActions = array('sign', 'esign_document', 'pdf');
	}

	public function sign()
	{
		$this->layout = 'test';
	}

	public function pdf()
	{
		$this->loadModel('User');
		$this->loadModel('QueuedDocument');
		$user = $this->User->findById( $this->Auth->user('id') );

		//Finds ESIGN Queue Category in the system
		$this->loadModel('DocumentQueueCategory');
		$esign_category = $this->DocumentQueueCategory->findByName('ESIGN');
		$no_category = $this->DocumentQueueCategory->findByName('Unassigned');

		var_dump($esign_category);

		$db_data = array(
			'user_id' => $this->Auth->user('id'),
			'entry_method' => 'esign'
		);
		if(!$esign_category && !$no_category)
		{
			$db_data['queue_category_id'] = 0;
		}
		else if(!$esign_category && $no_category)
		{
			$db_data['queue_category_id'] = $no_category['DocumentQueueCategory']['id'];
		}
		else
		{
			$db_data['queue_category_id'] = $esign_category['DocumentQueueCategory']['id'];
		}

		$this->QueuedDocument->createEsignPDF( $user, $db_data );
		exit();
	}

	public function pass_to_scan()
	{
		$this->layout = 'default_bootstrap';
	}

	public function esign_document()
	{
		$this->autoRender = false;

		$this->loadModel('User');

		$user = $this->User->findById( $this->Auth->user('id') );

		if( !isset($_POST['lines']) )
		{
			$message = array(
				'output' => 'lines is not set',
				'success' => FALSE
			);

			echo json_encode($message);
			exit();
		}
		else
		{
			$lines = $_POST['lines'];
		}

		$lines = json_decode($lines, true);
		$lines = $lines['lines'];

		//header( 'Content-Type: image/png' );
		
		$width = 400;
		$height = 100;
		$img = imagecreatetruecolor($width, $height);

		$bg = imagecolorallocate($img, 255, 255, 255);
		imagefill($img, 0, 0, $bg);
		$color = imagecolorallocate($img, 0, 0, 0);

		imagesetthickness($img, 4);

		for($j = 0; $j < count($lines); $j += 1)
		{
			$row = $lines[$j];
			for($i = 0; $i < count($row); $i += 1)
			{
				$col = $row[$i];
				$last_col = array();
				if($i == 0 && $j == 0)
				{
					continue;
				}
				else if($i == 0 AND $j > 0)
				{
					$last_row = $lines[$j - 1]; //Get's the last col in the last row
					$last_col = $last_row[ (count($last_row) - 1) ];
				}
				else
				{
					$last_col = $row[$i - 1];
				}

				$x1 = $last_col[0];
				$y1 = $last_col[1];

				$x2 = $col[0];
				$y2 = $col[1];

				imageline($img, $x1, $y1, $x2, $y2, $color);

			}
			
		}

		$save_directory = APP . 'storage' . DS . 'signatures' . DS . $this->Auth->user('id');
		$web_directory = 'storage/signatures/' . $this->Auth->user('id') . '/';

		if( !is_dir($save_directory) )
		{
			$is_made = mkdir( $save_directory, 0777, true );

			if( !$is_made )
			{
				$message = array('output' => 'Could not make user signatures directory at: ' . $save_directory, 'success' => FALSE);
				echo json_encode($message);
				exit();
			}
		}

		imagepng($img, $save_directory . DS . 'signature.png');
		imagedestroy($img);

		//Updates that the user has submitted an e-signature
		$this->loadModel('User');

		$this->User->create();
		$this->User->id = $this->Auth->user('id');
		$this->User->saveField('signature', 1);
		
		$message = array(
			'output' => $web_directory . 'signature.png',
			'success' => TRUE
		);
		echo json_encode($message);
		exit();
	}
}