<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div>
	<p>
        <?php printf(__("Welcome to the %s Online Services System. To begin, please log in with
                         your last name and the last 4 digits of your social security
                         number", true), Configure::read('Company.name')) ?>
	</p>
	<br />	
	<fieldset>
		<legend>Login</legend>
		<?php
		    echo $form->create('User');
			echo $form->input('username', array(
				'label' =>__('Lastname', true),
				'between' => '<br />',
				'after' => '<br />'));
		    echo '<br class="clear"/>';
		    echo $form->input('password', array(
				'label' => __('9 Digit SSN', true),
				'between' => '<br />',
				'after' => '<br />'
		    ));
			echo $form->hidden('User.login_type', array('value' => 'website'));
		    echo '<br class="clear"/>';
		    echo $form->end(__('Login', true));
		  ?>
	  </fieldset>
</div>
