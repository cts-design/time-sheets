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

<p>We do not have a record for you please register below.</p>
<br />
  <div>
	<?php echo $this->Form->create('User', array('action' => 'registration')); ?>
	<fieldset>
		<legend>Register</legend>
	    <?php
	    echo $this->Form->hidden('role_id', array('value' => '1'));
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
	    echo $this->Form->input('middle_initial', array(
			'label' => __('Middle Initial', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
	    echo '<br class="clear"/>';
		echo $this->Form->input('surname', array(
			'label' => __('Surname', true),
			'type' => 'select',
			'empty' => 'None',
			'options' => array('Jr' => 'Jr', 'Sr' => 'Sr', 'III' => 'III'),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
	    echo '<br class="clear"/>';
		echo $this->Form->input('gender', array(
			'label' => __('Gender', true),
			'type' => 'select',
			'options' => array('Male' => 'Male', 'Female' => 'Female'),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
	    echo '<br class="clear"/>';					
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
	    echo $this->Form->input('dob', array(
			'label' => __('Birth Date <span class="small gray">(mm/dd/yyyy)</span>', true),
			'type' => 'text',
			'maxlength' => 10,
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
	    echo '<br class="clear"/>';
		echo $this->Form->input('address_1', array(
			'label' => __('Address', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
		echo '<br class="clear"/>';
		echo $this->Form->input('city', array(
			'label' => __('City', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
		echo '<br class="clear"/>';		
		echo $this->Form->input('county', array(
			'label' => __('County', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
		echo '<br class="clear"/>';			
		echo $this->Form->input('state', array(
			'empty' => 'Please Select',
			'label' => __('State', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
		echo '<br class="clear"/>';			
	    echo $this->Form->input('zip', array(
			'label' => __('Zip Code', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
		echo '<br class="clear"/>';
	    echo $this->Form->input('phone', array(
			'label' => __('Phone', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));
		echo '<br class="clear"/>';
		echo $this->Form->input('email', array(
			'label' => __('Email Address', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));
		echo '<br class="clear"/>';
		echo $this->Form->input('language', array(
			'label' => __('Primary Spoken Language', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));
		echo '<br class="clear"/>';		
		echo $this->Form->input('ethnicity', array(
			'label' => __('Ethnicity', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array(
				'Hispanic or Latino' => 'Hispanic or Latino',
				'Not Hispanic or Latino' => 'Not Hispanic or Latino'), 
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));
		echo '<br class="clear"/>';
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
			'after' => '</p>'
		));
	    echo $this->Form->hidden('registration', array('value' => 'website'));
	    ?>
	    <br />
	    <?php echo $this->Form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button')); ?>
	</fieldset>

</div>

