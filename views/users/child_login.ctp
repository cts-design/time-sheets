<div id="ChildLoginForm">
	<p>
        <?php printf(__("Welcome to the %s Online Services System. To begin, please log in with your child's last name and your child's full 
                         social security number.", true), Configure::read('Company.name')) ?>
	</p>
	<?php $settings = Cache::read('settings'); ?> 
	<?php if(isset($settings['Users']['LoginAdditionalText'])) : ?>
		<?php $text = json_decode($settings['Users']['LoginAdditionalText'], true); ?> 
	<?php endif ?>
	<?php if(isset($text[1]['value'])) : ?>
		<p><?= $text[1]['value'] ?></p>
	<?php endif ?>
	<?php if(isset($instructions)) : ?>
		<p><?php echo __($instructions) ?></p>
	<?php endif ?>
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

		    $ssn = Configure::read('Login.child.ssn');
 
			if($ssn == 'last4')
			{
				echo $form->input('password', array(
			    	'label' => __('Child\'s last 4 Digits of SSN', true),
			    	'maxlength' => 9,
			    	'between' => '<br />',
			    	'after' => '<br />'
			    ));
			}
			else if($ssn == 'last5')
			{
				echo $form->input('password', array(
			    	'label' => __('Child\'s last 5 Digits of SSN', true),
			    	'maxlength' => 9,
			    	'between' => '<br />',
			    	'after' => '<br />'
			    ));
			}
			else
			{
				echo $form->input('password', array(
			    	'label' => __('Child\'s 9 Digit SSN', true),
			    	'maxlength' => 9,
			    	'between' => '<br />',
			    	'after' => '<br />'
			    ));
			}
			echo $form->hidden('User.login_type', array('value' => 'child_website'));
		    echo '<br class="clear"/>';
			if(isset($this->params['pass'][0]) && $this->params['pass'][0] === 'program') {
				echo $form->hidden('User.program_id', array('value' => $this->params['pass'][1]));
			}
		  ?>
	  </fieldset>
	  <br />
	 <?php echo $form->end(__('Login', true)); ?>

</div>
