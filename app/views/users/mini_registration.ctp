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
	It appears that we don't have a record for you in our database.
	<br />
	Please complete the form below.
    </h2>
    <?php echo $this->Session->flash(); ?>
    <div class="miniRegistrationForm">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
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
	    echo $this->Form->input('zip', array(
		'label' => __('Zip Code', true),
		'before' => '<p class="left">',
		'between' => '</p><p class="left">',
		'after' => '</p>'));
	    echo $this->Form->hidden('mini_registration', array('value' => 'mini'));
	    ?>
	</fieldset>
	<?php echo $this->Form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button')); ?>
	<?php echo $this->Html->link('Cancel', array('controller' => 'users', 'action' => 'self_sign_login'),
		 array('class' => 'self-sign-kiosk-link'))?>
    </div>
</div>
