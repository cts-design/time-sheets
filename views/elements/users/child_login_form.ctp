<?php
	echo $form->create('User', array('url' => $this->here));

	if($login_method == 'ssn')
	{
		echo $form->input('lastname', array(
			'label' => 'Child\'s Last Name', 
			'between' => '<br />',
			'after' => '<br />'
		));
	}
	else
	{
		echo $form->input('username', array(
			'label' => 'Child\'s Username', 
			'between' => '<br />',
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
				'between' => '<br />',
				'after' => '<br />'
			));
		}
		else
		{
			echo $form->input('ssn', array(
				'label' => __('Child\'s Full 9 Digit SSN', true),
				'maxlength' => $ssn_length,
				'type' => 'password',
				'between' => '<br />',
				'after' => '<br />'
			));
		}
	}
	else
	{
		echo $form->input('password', array(
			'label' => __('Child\'s Password', true),
			'between' => '<br />',
			'after' => '<br />'
		));
	}
	echo $form->hidden('User.login_type', array('value' => 'child_website'));
	echo '<br class="clear"/>';
	if(isset($this->params['pass'][0]) && $this->params['pass'][0] === 'program')
	{
		echo $form->hidden('User.program_id', array('value' => $this->params['pass'][1]));
	}
	echo $this->Form->end(__('Login', true));
?>