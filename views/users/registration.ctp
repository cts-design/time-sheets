<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('jquery.dPassword', array('inline' => 'false')); ?>
<?php echo $this->Html->script('users/mini.registration', array('inline' => 'false')) ?>
<?php echo $this->Html->script('jquery.autotab-1.1b', array('inline' => false)) ?>
<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
$(document).ready(function() {
	$('#UserSsn1, #UserSsn2, #UserSsn3').autotab_magic().autotab_filter('numeric');
	$('#UserSsn1Confirm, #UserSsn2Confirm, #UserSsn3Confirm').autotab_magic().autotab_filter('numeric');
});
<?php echo $this->Html->scriptEnd() ?>

<p>
    <?php __('We currently do not have a record for you.') ?>
	<br />	 
    <?php __('Please register your information using the following form.') ?>
	<br />	
    <?php __('If you have already created a login, please') ?>
    <?php echo $html->link(__('click here', true), array('controller' => 'users', 'action' => 'login', 'child')) ?> 
    <?php __('to return to the login page and try your login information again.') ?>
</p>
<br />
<div class="required"><label></label> <?php __('indicates required fields.') ?></div>
<br />
  <div id="WebRegistrationForm">
	<?php echo $this->Form->create('User', array('action' => 'registration')); ?>
	<fieldset>
		<legend>Register</legend>
	    <?php
	    echo $this->Form->hidden('role_id', array('value' => '1'));
	    echo $this->Form->input('firstname', array(
			'label' => __('First Name', true),
			'between' => '<br />',
			'after' => '<br />'));
	    echo $this->Form->input('lastname', array(
			'label' => __('Last Name', true),
			'between' => '<br />',
			'after' => '<br />'));
	    echo $this->Form->input('middle_initial', array(
			'label' => __('Middle Initial', true),
			'between' => '<br />',
			'after' => '<br />'));
		echo $this->Form->input('surname', array(
			'label' => __('Surname', true),
			'type' => 'select',
			'empty' => 'None',
			'options' => array('Jr' => 'Jr', 'Sr' => 'Sr', 'III' => 'III'),
			'between' => '<br />',
			'after' => '<br />'));
		echo $this->Form->input('gender', array(
			'label' => __('Gender', true),
			'type' => 'select',
			'empty' => 'Select Gender',
			'options' => array('Male' => 'Male', 'Female' => 'Female'),
			'between' => '<br />',
			'after' => '<br />'));
		if(Configure::read('Registration.ssn') == 'full') {
		    echo $this->Form->input('ssn', array(
				'label' => __('Social Security Number', true),
				'between' => '<br />'));
		    echo $this->Form->input('ssn_confirm', array(
				'label' => __('Please confirm your Social Security Number', true),
				'maxlength' => 9,
				'between' => '<br />',
				'after' => '<br />'));
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
	    echo $this->Form->input('dob', array(
			'label' => __('Birth Date <span class="small gray">(mm/dd/yyyy)</span>', true),
			'type' => 'text',
			'maxlength' => 10,
			'between' => '<br />',
			'after' => '<br />'));
		echo $this->Form->input('address_1', array(
			'label' => __('Address', true),
			'between' => '<br />',
			'after' => '<strong>&nbspNo P.O. Box Addresses accepted.<strong></span><br />'));
		echo $this->Form->input('city', array(
			'label' => __('City', true),
			'between' => '<br />',
			'after' => '<br />'));
		echo $this->Form->input('county', array(
			'label' => __('County', true),
			'between' => '<br />',
			'after' => '<br />'));
		echo $this->Form->input('state', array(
			'empty' => 'Please Select',
			'label' => __('State', true),
			'between' => '<br />',
			'after' => '<br />'));
	    echo $this->Form->input('zip', array(
			'label' => __('Zip Code', true),
			'between' => '<br />',
			'after' => '<br />'));
	    echo $this->Form->input('phone', array(
			'label' => __('Phone', true),
			'between' => '<br />',
			'after' => '<br />'));
		echo $this->Form->input('email', array(
			'label' => __('Email Address', true),
			'between' => '<br />',
			'after' => '<br />'));
		echo $this->Form->input('language', array(
			'label' => __('Primary Spoken Language', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array('English' => 'English', 'Spanish' => 'Spanish', 'Other' => 'Other'), 
			'between' => '<br />',
			'after' => '<br />'));	
		echo $this->Form->input('ethnicity', array(
			'label' => __('Ethnicity', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array(
				'Hispanic or Latino' => 'Hispanic or Latino',
				'Not Hispanic or Latino' => 'Not Hispanic or Latino'), 
			'between' => '<br />',
			'after' => '<br />'));
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
			'between' => '<br />',
			'after' => '<br />'));
		echo $this->Form->input('veteran', array(
			'label' => __('Are you a US veteran', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array('1' => 'Yes', '0' => 'No'), 
			'between' => '<br />',
			'after' => '<br />'));				
		echo $this->Form->input('disability', array(
			'label' => __('Do you have a substantial disability', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array('1' => 'Yes', '0' => 'No'), 
			'between' => '<br />',
			'after' => '<br />'));				
	    echo $this->Form->hidden('registration', array('value' => 'website'));
	    ?>
	    <br />
	    <?php echo $this->Form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button')); ?>
	</fieldset>

</div>

