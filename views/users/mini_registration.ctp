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
	<?php echo $this->Form->create('User', array('action' => 'mini_registration')); ?>
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
		echo '<br class="clear"/>';
	    echo $this->Form->input('phone', array(
			'label' => __('Phone', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p>'
		));		
	    echo $this->Form->hidden('mini_registration', array('value' => 'website'));
	    ?>
	    <br />
	    <?php echo $this->Form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button')); ?>
	</fieldset>

</div>

