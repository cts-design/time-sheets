<p>Congratulations your certificate for program name here has been issued. Please download certificate below.</p>

<?php echo $html->link('Download Cert', 
	array('action' => 'view_cert', $this->params['pass'][0]), array('target' => '_blank')); ?>