<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php $hideFields = $this->Session->read('idCard.success') ?>
<?php $hideZip = $this->Session->read('idCard.zip') ?>
<?php echo $this->Html->script('users/mini.registration', array('inline' => false)) ?>
<div id="miniRegistration" class="self-sign-wrapper">
    <h2>
    <?php __('It appears that we don\'t have a record for you in our database.') ?>
	<br />
    <?php __('Please complete the form below.') ?>
    </h2>
    <?php echo $this->Session->flash(); ?>
    <div class="miniRegistrationForm">
	<?php echo $this->Form->create('User') ?>
	<fieldset>
		<?php $data = $this->Session->read('driver_card') ?>
	    <?php
	    echo $this->Form->hidden('role_id', array('value' => '1'));
	    echo $this->Form->hidden('id_card_number', array('value' => $data['id_number']));
		// required fields
		if($hideFields) {
			echo $this->Form->hidden('firstname', array( 
				'value' => ucfirst(strtolower($data['first_name'])) ));
			echo $this->Form->hidden('lastname');
		}
		else {
			echo $this->Form->input('firstname', array(
			'label' => __('First Name', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>',
			));
			echo $this->Form->input('lastname', array(
			'label' => __('Last Name', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
			echo '<br class="clear"/>';
		}
	    
	    // dynamic fields
		if(in_array('middle_initial', $fields)) {
			if($hideFields) {
				echo $this->Form->hidden('middle_initial');
			}
			else {
				echo $this->Form->input('middle_initial', array(
					'label' => __('Middle Initial ', true),
					'type' => 'text',
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'after' => '</p>'));
				echo '<br class="clear"/>';			
			}
		}
		if(in_array('surname', $fields)) {
			echo $this->Form->input('surname', array(
				'label' => __('Surname', true),
				'type' => 'select',
				'empty' => 'None',
				'options' => array('Jr' => 'Jr', 'Sr' => 'Sr', 'III' => 'III'),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';				
		}
		if(in_array('gender', $fields))	{
			echo $this->Form->input('gender', array(
				'label' => __('Gender', true),
				'type' => 'select',
				'empty' => 'Select Gender',
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';					
		}
		
		// required fields
		if($login_method == 'ssn')
		{
			if($ssn_length == 9)
			{
				echo $this->Form->input('ssn', array(
					'label' => __('9 Digit SSN', true),
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'maxlength' => $ssn_length,
					'type' => 'password',
					'after' => '</p>'
				));
				echo '<br class="clear"/>';

				echo $this->Form->input('ssn_confirm', array(
					'label' => __('Confirm 9 Digit SSN', true),
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'maxlength' => $ssn_length,
					'type' => 'password',
					'after' => '</p>'
				));
				echo '<br class="clear"/>';
			}
			else
			{
				echo $this->Form->input('ssn', array(
					'label' => __('Last ' . $ssn_length . ' digits of SSN', true),
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'maxlength' => $ssn_length,
					'type' => 'password',
					'after' => '</p>'
				));
				echo '<br class="clear"/>';

				echo $this->Form->input('ssn_confirm', array(
					'label' => __('Confirm last ' . $ssn_length . ' digits of SSN', true),
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'maxlength' => $ssn_length,
					'type' => 'password',
					'after' => '</p>'
				));
				echo '<br class="clear"/>';
			}
		}
		else // login method == password
		{
			echo $this->Form->input('username', array(
					'label' => __('Username', true),
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'after' => '</p>'
				));
			echo '<br class="clear"/>';

			echo $this->Form->input('password', array(
				'label' => __('Password', true),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'
			));
			echo '<br class="clear"/>';

			echo $this->Form->input('password_confirm', array(
				'label' => __('Confirm Password', true),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'
			));
			echo '<br class="clear"/>';
		}
		
		//dynamic fields
		if(in_array('dob', $fields)) {
			if($hideFields) {
				echo $this->Form->hidden('dob');
			}
			else {
				echo $this->Form->input('dob', array(
					'label' => __('Birth Date <span class="small gray">(mm/dd/yyyy)</span>', true),
					'type' => 'text',
					'maxlength' => 10,
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'after' => '</p>'));
				echo '<br class="clear"/>';			
			}
		}		
		if(in_array('address_1', $fields)) {
			if($hideFields) {
				echo $this->Form->hidden('address_1');
			}
			else {
				echo $this->Form->input('address_1', array(
					'label' => __('Address', true),
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'after' => '</p>'));
				echo '<br class="clear"/>';					
			}
		}
		if(in_array('city', $fields)) {
			if($hideFields) {
				echo $this->Form->hidden('city');
			}
			else {
				echo $this->Form->input('city', array(
					'label' => __('City', true),
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'after' => '</p>'));
				echo '<br class="clear"/>';					
			}
		}
		if(in_array('county', $fields)) {
			echo $this->Form->input('county', array(
				'label' => __('County', true),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));	
			echo '<br class="clear"/>';		
		}
		if(in_array('state', $fields)) {
			if($hideFields) {
				echo $this->Form->hidden('state');
			}
			else {
				echo $this->Form->input('state', array(
					'empty' => 'Please Select',
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'after' => '</p>'));
				echo '<br class="clear"/>';				
			}
		}
		if(in_array('zip', $fields)) {
			if($hideZip) {
				echo $this->Form->hidden('zip');
			}
			else {
				echo $this->Form->input('zip', array(
					'label' => __('Zip Code', true),
					'before' => '<p class="left">',
					'between' => '</p><p class="left">',
					'after' => '</p>'));
				echo '<br class="clear"/>';					
			}
		}
		if(in_array('phone', $fields)) {
		    echo $this->Form->input('phone', array(
				'label' => __('Phone', true),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';				
		}
		if(in_array('email', $fields)) {
			echo $this->Form->input('email', array(
				'label' => __('Email Address', true),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';					
		}
		if(in_array('language', $fields)) {
			echo $this->Form->input('language', array(
				'label' => __('Primary Spoken Language', true),
				'type' => 'select',
				'empty' => 'Please Select',
				'options' => array('English' => 'English', 'Spanish' => 'Spanish', 'Other' => 'Other'), 
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));	
			echo '<br class="clear"/>';				
		}
		if(in_array('ethnicity', $fields)) {
			echo $this->Form->input('ethnicity', array(
				'label' => __('Ethnicity', true),
				'type' => 'select',
				'empty' => 'Please Select',
				'options' => array(
					'Hispanic or Latino' => 'Hispanic or Latino',
					'Not Hispanic or Latino' => 'Not Hispanic or Latino'), 
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));	
			echo '<br class="clear"/>';			
		}
		if(in_array('race', $fields)) {
			echo $this->Form->input('race', array(
				'label' => __('Race', true),
				'type' => 'select',
				'empty' => 'Please Select',
				'options' => array(
					'American Indian or Alaska Native' => 'American Indian or Alaska Native',
					'Asian' => 'Asian',
					'Black or African American' => 'Black or African American',
					'Hawaiian or Other Pacific Islander' => 'Hawaiian or Other Pacific Islander',
					'White' => 'White'),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';						
		}
		if(in_array('veteran', $fields)) {
			echo $this->Form->input('veteran', array(
				'label' => __('Are you a U.S. veteran', true),
				'type' => 'select',
				'empty' => 'Please Select',
				'options' => array(1 => 'Yes', 0 => 'No'), 
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));	
			echo '<br class="clear"/>';				
		}
		if(in_array('disability', $fields)) {
			echo $this->Form->input('disability', array(
				'label' => __('Do you have a substantial disablilty', true),
				'type' => 'select',
				'empty' => 'Please Select',
				'options' => array(1 => 'Yes', 0 => 'No'), 
				'before' => '<p class="left">',
				'selected' => 0,
				'between' => '</p><p class="left">',
				'after' => '</p>'));	
			echo '<br class="clear"/>';				
		}		
	    echo $this->Form->hidden('mini_registration', array('value' => 'kiosk'));
	    ?>
	</fieldset>
	<ul style="font-size: 11px; margin: 10px 0 0 150px">
		<li>Providing the information is voluntary. The information will be kept confidential as provided by law.</li>
		<li>
			Refusal to provide the information will not subject you to any adverse treatment. 
			The information will be used only in accordance with the law.
		</li>	
	</ul>
	<?php echo $this->Form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button left')); ?>
	<?php echo $this->Html->link(__('Cancel', true), '/kiosk/users/self_sign_login',
		 array('class' => 'self-sign-kiosk-link left'))?>
		 <br class='clear' />
    </div>
</div>
