<div id="ChildLoginForm">
	<p>
        <?php printf(__("Welcome to the %s Online Services System. To begin, please log in
                         with your child's last name and the last 4 digits of your child's 
                         social security number", true), Configure::read('Company.name')) ?>
	</p>
	<br />	
	<fieldset>
		<legend>Login</legend>
		<?php
		    echo $form->create('User', array('action' => 'login'));
		    echo $form->input('username', array(
		    	'label' => 'Child\'s Lastname', 
		    	'between' => '<br />',
				'after' => '<br />'));
		    echo '<br class="clear"/>';
		    echo $form->input('password', array(
		    	'label' => __('Child\'s 9 Digit SSN', true),
		    	'maxlength' => 9,
		    	'between' => '<br />',
		    	'after' => '<br />'
		    ));
			echo $form->hidden('User.login_type', array('value' => 'child_website', ));
		    echo '<br class="clear"/>';
		    
		  ?>
	  </fieldset>
	  <br />
	 <?php echo $form->end(__('Login', true)); ?>
</div>
