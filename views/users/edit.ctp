<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div>
    <div class="users form">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
	    <legend><?php __('Edit Profile'); ?></legend>
	    <?php
	    echo $this->Form->input('id');
	    echo $this->Form->input('role_id', array('type' => 'hidden'));
    	echo $this->Form->input('firstname',array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('lastname', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>',
		    'readonly' => 'readonly'
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
		    'after' => '</p>',
		    'value' => (preg_match('(none|nobody|noreply)', $this->data['User']['email'])) ? '' : $this->data['User']['email'] 
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('language', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));		
	    echo '<br class="clear" />' ;
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
			'label' => __('Do you have a substantial disability', true),
			'type' => 'select',
			'empty' => 'Please Select',
			'options' => array(1 => 'Yes', 0 => 'No'), 
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'));
	    echo $this->Form->input('ssn', array(
	    	'type' => 'hidden',
	    	'empty' => 'Please Select'
        ));
	    ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
    </div>
</div>
