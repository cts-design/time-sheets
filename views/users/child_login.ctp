<div id="ChildLoginForm">
	<p>
        <?php printf(__("Welcome to the %s Online Services System. To begin, please log in below", true), Configure::read('Company.name')) ?>
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
		    echo $form->create('User', array('url' => $this->Html->url()));
		    echo $form->input('username', array(
		    	'label' => 'Child\'s Last Name', 
		    	'between' => '<br />',
				'after' => '<br />'));
		    echo '<br class="clear"/>';
 
			if($ssn_length != 9)
			{
				echo $form->input('password', array(
			    	'label' => __('Child\'s last ' . $ssn_length . ' Digits of SSN', true),
			    	'maxlength' => $ssn_length,
			    	'between' => '<br />',
			    	'after' => '<br />'
			    ));
			}
			else
			{
				echo $form->input('password', array(
			    	'label' => __('Child\'s Full 9 Digit SSN', true),
			    	'maxlength' => $ssn_length,
			    	'between' => '<br />',
			    	'after' => '<br />'
			    ));
			}
			echo $form->hidden('User.login_type', array('value' => 'child_website'));
		    echo '<br class="clear"/>';
			if(isset($this->params['pass'][0]) && $this->params['pass'][0] === 'program') {
				echo $form->hidden('User.program_id', array('value' => $this->params['pass'][1]));
			}
			echo $this->Form->end(__('Login', true));
		  ?>
	  </fieldset>
	  <br />

</div>
