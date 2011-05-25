<style>
	#UserPassword {width: 30px}
	#ChildLoginForm { width: 350px;}
	#ChildLoginForm label { margin-right: 10px;}
	#ChildLoginForm .input {margin-bottom: 10px;}
</style>
<div id="ChildLoginForm">
	<p>
		Welcome to the <?php Configure::read('Company.name') ?> Online Services System. To
		begin, please log in with your child's last name and the last 4 digits of
		your child's social security number.
	</p>
	<br />	
	<fieldset>
		<legend>Login</legend>
		<?php
		    echo $form->create('User', array('action' => 'login'));
		    echo $form->input('username', array('label' => 'Child\'s Lastname', 'between' => '<br />'));
		    echo '<br class="clear"/>';
		    echo $form->input('password', array(
		    	'label' => 'Child\'s Last 4 SSN',
		    	'maxlength' => 4,
		    	'between' => '<br />'
		    ));
			echo $form->hidden('User.login_type', array('value' => 'child_website', ));
		    echo '<br class="clear"/>';
		    
		  ?>
	  </fieldset>
	  <br />
	 <?php echo $form->end('Login'); ?>
</div>