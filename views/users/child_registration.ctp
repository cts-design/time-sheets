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
	<?php echo $this->Form->create('User', array('action' => 'registration/child')); ?>
	<fieldset>
		<legend>Register</legend>
	    <?php
	    echo $this->Form->hidden('role_id', array('value' => '1'));
	    echo $this->Form->input('firstname', array(
		'label' => __('Child\'s First Name', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
	    echo $this->Form->input('lastname', array(
		'label' => __('Child\'s Last Name', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
	    echo $this->Form->input('middle_initial', array(
		'label' => __('Child\'s Middle Initial', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
		echo $this->Form->input('surname', array(
		'label' => __('Child\'s Surname', true),
		'type' => 'select',
		'empty' => 'None',
		'options' => array('Jr' => 'Jr', 'Sr' => 'Sr', 'III' => 'III'),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';	
		echo $this->Form->input('gender', array(
		'label' => __('Child\'s Gender', true),
		'type' => 'select',
		'empty' => 'Please Select',
		'options' => array('Male' => 'Male', 'Female' => 'Female'),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
	    echo $this->Form->input('ssn', array(
		'label' => __('Child\'s SSN', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
	    echo $this->Form->input('ssn_confirm', array(
		'label' => __('Child\'s SSN Confirm', true),
		'maxlength' => 9,
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
	    echo $this->Form->input('dob', array(
		'label' => __('Child\'s Birth Date <span class="small gray">(mm/dd/yyyy)</span>', true),
		'type' => 'text',
		'maxlength' => 10,
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo '<br class="clear"/>';
		echo $this->Form->input('address_1', array(
		'label' => __('Child\'s Address', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
		echo '<br class="clear"/>';
		echo $this->Form->input('city', array(
		'label' => __('Child\'s City', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
		echo '<br class="clear"/>';		
		echo $this->Form->input('county', array(
		'label' => __('Child\'s County', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
		echo '<br class="clear"/>';			
		echo $this->Form->input('state', array(
		'label' => __('Child\'s State', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
		echo '<br class="clear"/>';				
	    echo $this->Form->input('zip', array(
		'label' => __('Child\'s Zip Code', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
		echo '<br class="clear"/>';
	    echo $this->Form->input('phone', array(
			'label' => __('Parent\'s Phone', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));
		echo '<br class="clear"/>';	
		echo $this->Form->input('email', array(
			'label' => __('Parent\'s Email', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));	
		echo '<br class="clear"/>';
		echo $this->Form->input('language', array(
			'label' => __('Child\'s Primary Spoken Language', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));
		echo '<br class="clear"/>';			
		echo $this->Form->input('ethnicity', array(
			'label' => __('Child\'s Ethnicity', true),
			'type' => 'select',
			'options' => array(
				'Hispanic or Latino' => 'Hispanic or Latino',
				'Not Hispanic or Latino' => 'Not Hispanic or Latino'), 
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));
		echo '<br class="clear"/>';
		echo $this->Form->input('race', array(
			'label' => __('Child\'s Race', true),
			'type' => 'select',
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
		echo '<br class="clear"/>';	
		echo $html->link('Click here to get an email address if you don not have one.', 
			'https://www.google.com/accounts/NewAccount?service=mail&continue=
			http://mail.google.com/mail/e-11-149ff52bbc80936376c01275ce56c7-f2297e1257c13b74d3ba16b09f1177fc98da2414&type=2',
		array('target' => '_blank'));
	    echo $this->Form->hidden('mini_registration', array('value' => 'child_website'));
	    ?>
	    <br />
	    <?php echo $this->Form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button')); ?>
	</fieldset>

</div>

