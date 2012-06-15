<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div id="UserLoginForm">
	<p>
        <?php printf(__("Welcome to the %s Online Services System. To begin, please log in with
                         your last name and the last 4 digits of your social security
                         number", true), Configure::read('Company.name')) ?>
	</p>
	<?php if(isset($instructions)) : ?>
		<p><?php echo __($instructions) ?></p>
	<?php endif ?>
	<br />	
	<fieldset>
		<legend>Login</legend>
		<?php
		    echo $form->create('User');
			echo $form->input('username', array(
				'label' => 'Lastname',
				'after' => '<br />',
			));
		    echo '<br class="clear"/>';
		    echo $form->input('password', array(
				'label' => __('Last 4 SSN', true),
				'after' => '<br />',
				'maxlength' => 4
		    ));
			echo $form->hidden('User.login_type', array('value' => $loginType));
			if(isset($this->params['pass'][0]) && $this->params['pass'][0] === 'program') {
				echo $form->hidden('User.program_id', array('value' => $this->params['pass'][1]));
			}
		    echo '<br class="clear"/>';
		    echo $form->end(__('Login', true));
		  ?>
	  </fieldset>
</div>
