<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div class="self-sign-wrapper">
	<h1><?php __('Please correct the information we have on file.') ?></h1>
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->Form->create('Kiosk'); ?>
    <fieldset>
	<?php
	
	
	echo $this->Form->input('User.firstname', array(
	    'label' => __('First Name', true),
	    'before' => '<p class="left">',
	    'between' => '</p><p class="left">',
	    'after' => '</p><br class="clear"/>'
	));
	echo $this->Form->input('User.lastname', array(
	    'label' => __('Last Name', true),
	    'readonly' => 'readonly',
	    'before' => '<p class="left">',
	    'between' => '</p><p class="left">',
	    'after' => '</p><br class="clear"/>'
	));
	
	// Begin dynamic fields
	if(in_array('middle_initial', $fields)) {
	    echo $this->Form->input('User.middle_initial', array(
			'label' => __('Middle Initial ', true),
			'type' => 'text',
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
	    echo '<br class="clear"/>';			
	}
	if(in_array('surname', $fields)) {
		echo $this->Form->input('User.surname', array(
			'label' => __('Surname', true),
			'type' => 'select',
			'empty' => 'None',
			'options' => array('Jr' => 'Jr', 'Sr' => 'Sr', 'III' => 'III'),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));		
	}
	if(in_array('gender', $fields))	{
		echo $this->Form->input('User.gender', array(
			'label' => __('Gender', true),
			'type' => 'select',
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));			
	}		
	
	if(in_array('dob', $fields)) {
	    echo $this->Form->input('User.dob', array(
			'label' => __('Birth Date <span class="small gray">(mm/dd/yyyy)</span>', true),
			'type' => 'text',
			'maxlength' => 10,
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
	    echo '<br class="clear"/>';			
	}
	
	if(in_array('address_1', $fields)) {
		echo $this->Form->input('User.address_1', array(
			'label' => __('Address', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));			
	}
	if(in_array('city', $fields)) {
		echo $this->Form->input('User.city', array(
			'label' => __('City', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));			
	}
	if(in_array('county', $fields)) {
		echo $this->Form->input('User.county', array(
			'label' => __('County', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));		
	}
	if(in_array('state', $fields)) {
		echo $this->Form->input('User.state', array(
			'empty' => 'Please Select',
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));		
	}
	if(in_array('zip', $fields)) {
	    echo $this->Form->input('User.zip', array(
			'label' => __('Zip Code', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));			
	}
	if(in_array('phone', $fields)) {
	    echo $this->Form->input('User.phone', array(
			'label' => __('Phone', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));			
	}
	if(in_array('email', $fields)) {
		echo $this->Form->input('User.email', array(
			'label' => __('Email Address', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));			
	}
	if(in_array('language', $fields)) {
		echo $this->Form->input('User.language', array(
			'label' => __('Primary Spoken Language', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array('English' => 'English', 'Spanish' => 'Spanish', 'Other' => 'Other'), 
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));			
	}
	if(in_array('ethnicity', $fields)) {
		echo $this->Form->input('User.ethnicity', array(
			'label' => __('Ethnicity', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array(
				'Hispanic or Latino' => 'Hispanic or Latino',
				'Not Hispanic or Latino' => 'Not Hispanic or Latino'), 
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));		
	}
	if(in_array('race', $fields)) {
		echo $this->Form->input('User.race', array(
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
	}	
	echo $this->Form->input('User.id');
	echo $this->Form->input('User.role_id', array('type' => 'hidden', 'value' => 1));
	echo $this->Form->hidden('User.self_sign_edit', array('value' => 'edit'));
	?>
    </fieldset>
    <?php echo $form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button')); ?>
</div>
