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
                         your last name and your full social security
                         number.", true), Configure::read('Company.name')) ?>
	</p>
	<?php $settings = Cache::read('settings'); ?> 
	<?php if(isset($settings['Users']['LoginAdditionalText'])) : ?>
		<?php $text = json_decode($settings['Users']['LoginAdditionalText'], true); ?> 
	<?php endif ?>
	<?php if(isset($text[0]['value'])) : ?>
		<p><?= $text[0]['value'] ?></p>
	<?php endif ?>
	<?php if(isset($instructions)) : ?>
		<p><?php echo __($instructions) ?></p>
	<?php endif ?>
	<br />	
	<fieldset>
		<legend>Login</legend>
		<?php
		    echo $form->create('User', array('url' => $this->Html->url()));
			echo $form->input('username', array(
				'label' =>__('Lastname', true),
				'between' => '<br />',
				'after' => '<br />'));

			if($ssn_length != 9)
			{
				echo $form->input('password', array(
					'label' => __('Last ' . $ssn_length . ' SSN Digits', true),
					'between' => '<br />',
					'after' => '<br />',
					'maxlength' => $ssn_length
			    ));
			}
			else
			{
				echo $form->input('password', array(
					'label' => __('9 Digit SSN', true),
					'between' => '<br />',
					'after' => '<br />',
					'maxlength' => $ssn_length
			    ));
			}
		   echo $form->hidden('User.login_type', array('value' => $loginType));
			if(isset($this->params['pass'][0]) && $this->params['pass'][0] === 'program') {
				echo $form->hidden('User.program_id', array('value' => $this->params['pass'][1]));
			}
		    echo $form->end(__('Login', true));
		  ?>
	  </fieldset>
</div>
