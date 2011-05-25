<style>
	#UserPassword {width: 30px}
	#ChildLoginForm { width: 350px;}
	#ChildLoginForm label { margin-right: 10px;}
</style>
<div id="ChildLoginForm">
	<fieldset>
		<legend>Login</legend>
		<?php
		    echo $form->create('User', array('action' => 'login'));
		    echo $form->input('username', array('label' => 'Child\'s Lastname'));
		    echo '<br class="clear"/>';
		    echo $form->input('password', array(
		    	'label' => 'Child\'s Last 4 SSN',
		    	'maxlength' => 4
		    ));
			echo $form->hidden('User.login_type', array('value' => 'child_website'));
		    echo '<br class="clear"/>';
		    
		  ?>
	  </fieldset>
	  <br />
	 <?php echo $form->end('Login'); ?>
</div>