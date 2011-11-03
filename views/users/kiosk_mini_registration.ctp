<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<?php echo $this->Html->script('users/mini.registration', array('inline' => 'false')) ?>
<div id="miniRegistration" class="self-sign-wrapper">
    <h2>
    <?php __('It appears that we don\'t have a record for you in our database.') ?>
	<br />
    <?php __('Please complete the form below.') ?>
    </h2>
    <?php echo $this->Session->flash(); ?>
    <div class="miniRegistrationForm">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
	    <?php
	    echo $this->Form->hidden('role_id', array('value' => '1'));
		// required fields
	    echo $this->Form->input('firstname', array(
		'label' => __('First Name', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
	    echo $this->Form->input('lastname', array(
		'label' => __('Last Name', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
	    
	    // dynamic fields
		if(in_array('middle_initial', $fields)) {
		    echo $this->Form->input('middle_initial', array(
				'label' => __('Middle Initial ', true),
				'type' => 'text',
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
		    echo '<br class="clear"/>';			
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
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';					
		}
		
		// required fields
		
		if(Configure::read('Registration.ssn') == 'full') {
			
		    echo $this->Form->input('ssn', array(
			'label' => __('SSN', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
		    echo '<br class="clear"/>';
		    echo $this->Form->input('ssn_confirm', array(
			'label' => __('SSN Confirm', true),
			'maxlength' => 9,
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
		    echo '<br class="clear"/>';
		}
		elseif(Configure::read('Registration.ssn') == 'last4') {
			echo '<div class="input required">';
				echo $this->Form->label(__('Social Security Number', true));
				echo '<br />';	
				echo $this->Form->input('ssn_1', array(
					'type' => 'text',
					'div' => false,
					'maxlength' => 3, 
					'label' => false));
				echo $this->Form->input('ssn_2', array(
					'type' => 'text',
					'maxlength' => 2,
					'label' => false,
					'div' => false));
				echo $this->Form->input('ssn_3', array(
					'type' => 'text',
					'maxLength' => 4,
					'label' => false,
					'div' => false));
				echo "<br class='clear' />";
				echo '<div class="small">Please see the <a href="#">privacy act</a> statement concerning social security numbers.</div>';	
				echo $this->Form->error('ssn');
			echo '</div>';
			echo '<div class="input required">';
				echo $this->Form->label(__('Please confirm your Social Security Number', true));
				echo '<br />';			
				echo $this->Form->input('ssn_1_confirm', array(
					'type' => 'text',
					'maxlength' => 3,
					'label' => false,
					'div' => false));
				echo $this->Form->input('ssn_2_confirm', array(
					'type' => 'text',
					'maxlength' => 2,
					'label' => false,
					'div' => false));
				echo $this->Form->input('ssn_3_confirm', array(
					'type' => 'text',
					'maxlength' => 4,
					'label' => false,
					'div' => false,
					'after' => '<br />'));
				echo $this->Form->error('ssn_confirm');
			echo '</div>';					
		}		
		
		
		
		
		

		
		//dynamic fields
		if(in_array('dob', $fields)) {
		    echo $this->Form->input('dob', array(
				'label' => __('Birth Date <span class="small gray">(mm/dd/yyyy)</span>', true),
				'type' => 'text',
				'maxlength' => 10,
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
		    echo '<br class="clear"/>';			
		}		
		if(in_array('address_1', $fields)) {
			echo $this->Form->input('address_1', array(
				'label' => __('Address', true),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';					
		}
		if(in_array('city', $fields)) {
			echo $this->Form->input('city', array(
				'label' => __('City', true),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';					
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
			echo $this->Form->input('state', array(
				'empty' => 'Please Select',
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';				
		}
		if(in_array('zip', $fields)) {
		    echo $this->Form->input('zip', array(
				'label' => __('Zip Code', true),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';					
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
					'American Indian or Alaksa Native' => 'American Indian or Alaksa Native',
					'Asian' => 'Asian',
					'Black or African American' => 'Black or African American',
					'Hawaiian or Other Pacific Islander' => 'Hawaiian or Other Pacific Islander',
					'White' => 'White'),
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'));
			echo '<br class="clear"/>';						
		}
	    echo $this->Form->hidden('mini_registration', array('value' => 'kiosk'));
	    ?>
	</fieldset>
	<?php echo $this->Form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button left')); ?>
	<?php echo $this->Html->link(__('Cancel', true), array('controller' => 'users', 'action' => 'self_sign_login'),
		 array('class' => 'self-sign-kiosk-link left'))?>
    </div>
</div>
