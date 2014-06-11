<?php
	echo $form->create('User', array('url' => $this->here));

	if($login_method == 'ssn')
	{
		echo $form->input('lastname', array(
			'label' => 'Child\'s Last Name', 
			'after' => '<br />'
		));
	}
	else
	{
		echo $form->input('username', array(
			'label' => 'Child\'s Username', 
			'after' => '<br />'
		));
	}

	echo '<br class="clear"/>';

	if($login_method == 'ssn')
	{
		if($ssn_length != 9)
		{
			echo $form->input('ssn', array(
				'label' => __('Child\'s last ' . $ssn_length . ' Digits of SSN', true),
				'maxlength' => $ssn_length,
				'type' => 'password',
				'before' => '<br />',
				'after' => '<br />'
			));
		}
		else
		{
			echo $form->input('ssn', array(
				'label' => __('Child\'s Full 9 Digit SSN', true),
				'maxlength' => $ssn_length,
				'type' => 'password',
				'before' => '<br />',
				'after' => '<br />'
			));
		}
	}
	else
	{
		echo $form->input('password', array(
			'label' => __('Child\'s Password', true),
			'type' => 'password',
			'before' => '<br />',
			'after' => '<br />'
		));
		echo $html->link('Forgot Password?', array('controller' => 'users', 'action' => 'forgot_password'));
	}
	echo $form->hidden('User.login_type', array('value' => 'child_website'));
	echo '<br class="clear"/>';
	if(isset($this->params['pass'][0]) && $this->params['pass'][0] === 'program')
	{
		echo $form->hidden('User.program_id', array('value' => $this->params['pass'][1]));
	}
	echo $this->Form->end(array('label' => 'Login', 'class' => 'login-sumit btn btn-inverse'));
?>