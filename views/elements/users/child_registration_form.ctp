<?php
	echo $this->Form->hidden('role_id', array('value' => '1'));
	echo $this->Form->input('firstname', array(
		'label' => __('Child\'s First Name', true),
		'between' => '<br />',
		'after' => '<br />'));
	echo $this->Form->input('lastname', array(
		'label' => __('Child\'s Last Name', true),
		'between' => '<br />',
		'after' => '<br />'));
	echo $this->Form->input('middle_initial', array(
		'label' => __('Child\'s Middle Initial', true),
		'between' => '<br />',
		'after' => '<br />'));
	echo $this->Form->input('surname', array(
		'label' => __('Child\'s Surname', true),
		'type' => 'select',
		'empty' => 'None',
		'options' => array('Jr' => 'Jr', 'Sr' => 'Sr', 'III' => 'III', 'IV' => 'IV', 'V' => 'V', 'VI' => 'VI', 'VII' => 'VII'),
		'between' => '<br />',
		'after' => '<br />'));
	echo $this->Form->input('gender', array(
		'label' => __('Child\'s Gender', true),
		'type' => 'select',
		'empty' => 'Please Select',
		'options' => array('Male' => 'Male', 'Female' => 'Female'),
		'between' => '<br />',
		'after' => '<br />'));


	if($login_method == 'ssn')
	{
		if($ssn_length == 9)
		{
			echo $this->Form->input('ssn', array(
				'label' => 'Your child\'s Social Security Number',
				'type' => 'password',
				'maxlength' => $ssn_length,
				'between' => '<br />',
				'after' => '<br />'
			));
			echo $this->Form->input('ssn_confirm', array(
				'label' => 'Confirm your child\'s Social Security Number',
				'type' => 'password',
				'maxlength' => $ssn_length,
				'between' => '<br />',
				'after' => '<br />'
			));
		}
		else
		{
			echo $this->Form->input('ssn', array(
				'label' => 'The last ' . $ssn_length . ' digits of your child\'s Social Security Number',
				'type' => 'password',
				'maxlength' => $ssn_length,
				'between' => '<br />',
				'after' => '<br />'
			));
			echo $this->Form->input('ssn_confirm', array(
				'label' => 'Confirm the last ' . $ssn_length . ' digits of your child\'s Social Security Number',
				'type' => 'password',
				'maxlength' => $ssn_length,
				'between' => '<br />',
				'after' => '<br />'
			));
		}
	}
	else
	{
		echo $this->Form->input('username', array(
			'label' => 'Child\'s Username',
			'between' => '<br />',
			'after' => '<br />'
		));
		echo $this->Form->input('password', array(
			'label' => 'Child\'s Password',
			'type' => 'password',
			'between' => '<br />',
			'after' => '<br />'
		));
		echo $this->Form->input('password_confirm', array(
			'label' => 'Confirm Child\'s Password',
			'type' => 'password',
			'between' => '<br />',
			'after' => '<br />'
		));
	}
	echo $this->Form->input('dob', array(
		'label' => __('Child\'s Birth Date <span class="small gray">(mm/dd/yyyy)</span>', true),
		'type' => 'text',
		'maxlength' => 10,
		'between' => '<br />',
		'after' => '<br />'));

	echo $this->Form->input('address_1', array(
		'label' => __('Child\'s Address', true),
		'between' => '<br />',
		'after' => '<span><strong>&nbspNo P.O. Box Addresses accepted.<strong></span><br />'));
	echo $this->Form->input('city', array(
		'label' => __('Child\'s City', true),
		'between' => '<br />',
		'after' => '<br />'));

	echo $this->Form->input('county', array(
		'label' => __('Child\'s County', true),
		'type' => 'select',
		'empty' => 'Please Select',
		'options' => Configure::read('Company.counties'), 
		'between' => '<br />',
		'after' => '<br />'));;
		
	echo $this->Form->input('state', array(
		'type' => 'text',
		'readonly' => 'readonly',
		'label' => __('Child\'s State', true),
		'value' => Configure::read('Company.state'),
		'between' => '<br />',
		'after' => '<br />'));			
		
	echo $this->Form->input('zip', array(
		'label' => __('Child\'s Zip Code', true),
		'between' => '<br />',
		'after' => '<br />'));

	echo $this->Form->input('phone', array(
		'label' => __('Parent\'s Phone', true),
		'between' => '<br />',
		'after' => '<br />'));

	echo $this->Form->input('email', array(
		'label' => __('Parent\'s Email', true),
		'between' => '<br />',
		'after' => '<br />'));
	echo $this->Form->input('email_confirm', array(
		'label' => __('Confirm Parent\'s Email', true),
		'between' => '<br />',
		'after' => '<br /><p class="small">' . $html->link('Click here to get a free email address if you do not have one.', 
		'https://www.google.com/accounts/NewAccount?service=mail&continue=
		http://mail.google.com/mail/e-11-149ff52bbc80936376c01275ce56c7-f2297e1257c13b74d3ba16b09f1177fc98da2414&type=2',
		array('target' => '_blank')) . '</p>'));	
	echo $this->Form->input('language', array(
		'label' => __('Child\'s Primary Spoken Language', true),
		'type' => 'select',
		'empty' => 'Please Select',
		'options' => array('English' => 'English', 'Spanish' => 'Spanish', 'Other' => 'Other'), 
		'between' => '<br />',
		'after' => '<br />'));
	echo $this->Form->input('ethnicity', array(
		'label' => __('Child\'s Ethnicity', true),
		'type' => 'select',
		'empty' => 'Please Select',
		'options' => array(
			'Hispanic or Latino' => 'Hispanic or Latino',
			'Not Hispanic or Latino' => 'Not Hispanic or Latino'), 
			'between' => '<br />',
			'after' => '<br />'));

	echo $this->Form->input('race', array(
		'label' => __('Child\'s Race', true),
		'type' => 'select',
		'empty' => 'Please Select',
		'options' => array(
			'American Indian or Alaksa Native' => 'American Indian or Alaksa Native',
			'Asian' => 'Asian',
			'Black or African American' => 'Black or African American',
			'Hawaiian or Other Pacific Islander' => 'Hawaiian or Other Pacific Islander',
			'White' => 'White'),
			'between' => '<br />',
			'after' => '<br />'));
	echo $this->Form->input('disability', array(
		'label' => __('Does your child have a substantial disability', true),
		'type' => 'select',
		'empty' => 'Please Select',
		'options' => array('1' => 'Yes', '0' => 'No'), 
		'between' => '<br />',
		'after' => '<br />'));
	echo $this->Form->hidden('registration', array('value' => 'child_website'));
?>    
<br />
<?php echo $this->Form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button')); ?>