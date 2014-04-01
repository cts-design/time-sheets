<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Add Customer', true), null, 'unique') ; ?>
</div>
<div class="admin">
    <div class="actions ui-widget-header">
	    <ul></ul>
    </div>
    <div class="users form">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
	    <legend><?php __('Add Customer'); ?></legend>
	    <?php
	    echo $this->Form->hidden('role_id', array('value' => '1'));
	    echo $this->Form->input('firstname',array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('lastname', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('middle_initial', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
		echo $this->Form->input('surname', array(
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array('Jr' => 'Jr', 'Sr' => 'Sr', 'III' => 'III', 'IV' => 'IV', 'V' => 'V', 'VI' => 'VI', 'VII' => 'VII'),
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;		
	    echo $this->Form->input('ssn', array(
		    'type' => 'password',
		    'label' => __('SSN', true),
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('ssn_confirm', array(
		    'type' => 'password',
		    'label' => __('SSN Confirm', true),
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('address_1', array(
		    'label' => 'Address',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('city', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('county', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));		
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('state', array(
		    'empty' => 'Select State',
		    'type' => 'select',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('zip', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('phone', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('alt_phone', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('gender', array(
		    'empty' => 'Select Gender',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('dob',array(
		    'type' => 'text',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('email', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;	
		echo $this->Form->input('language', array(
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array('English' => 'English', 'Spanish' => 'Spanish', 'Other' => 'Other'), 
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'));
		echo '<br class="clear"/>';			
	    echo $this->Form->input('race', array(
	    	'type' => 'select',
	    	'empty' => 'Please Select',
	    	'options' => array(
	    		'American Indian or Alaska Native' => 'American Indian or Alaska Native',
				'Asian' => 'Asian',
				'Black or African American' => 'Black or African American',
				'Hawaiian or Other Pacific Islander' => 'Hawaiian or Other Pacific Islader',
				'White' => 'White'
				),
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
		echo '<br class="clear" />' ;
	    echo $this->Form->input('ethnicity', array(
	    	'type' => 'select',
	    	'empty' => 'Please Select',
	    	'options' => array(
	    		'Hispanic or Latino' => 'Hispanic or Latino',
				'Not Hispanic or Latino'
				),
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));				
		echo '<br class="clear" />' ;
		echo $this->Form->input('User.disability', array(
			'label' => __('Disability', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array(1 => 'Yes', 0 => 'No'), 
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
	    ?>
	    
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
    </div>
</div>
