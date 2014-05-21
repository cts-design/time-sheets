<?php
	echo $form->create('User', array('url' => $this->here));

	if($login_method == 'ssn')
	{
		echo $form->input('lastname', array(
			'label' =>__('Last Name', true),
			'between' => '<br />',
			'after' => '<br />'
		));
		if($ssn_length != 9)
		{
			echo $form->input('ssn', array(
				'label' => __('Last ' . $ssn_length . ' SSN Digits', true),
				'type' => 'password',
				'between' => '<br />',
				'after' => '<br />',
				'maxlength' => $ssn_length
			));
		}
		else
		{
			echo $form->input('ssn', array(
				'label' => __('9 Digit Social Security Number', true),
				'between' => '<br />',
				'after' => '<br />',
				'maxlength' => $ssn_length
			));
		}
	}
	else
	{
		echo $form->input('username', array(
			'label' =>__('Username', true),
			'between' => '<br />',
			'after' => '<br />'
		));
		echo $form->input('password', array(
				'label' => __('Password', true),
				'between' => '<br />',
				'after' => '<br />'
			));
	}
	echo $form->hidden('User.login_type', array('value' => $type));

	$params = $this->params['pass'];

	if( isset($params[0]) && $params[0] == 'program' && isset($params[1]) )
	{
		echo $form->hidden( 'User.program_id', array('value' => $params[1]) );
	}
	echo $this->Form->end( array('label' => 'Login') );
?>