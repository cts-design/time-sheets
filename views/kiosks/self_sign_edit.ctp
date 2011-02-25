<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div class="self-sign-wrapper">
    <h1>Please correct the information we have on file.</h1>
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
	    'before' => '<p class="left">',
	    'between' => '</p><p class="left">',
	    'after' => '</p><br class="clear"/>'
	));
	echo $this->Form->input('User.zip', array(
	    'label' => __('Zip Code', true),
	    'before' => '<p class="left">',
	    'between' => '</p><p class="left">',
	    'after' => '</p><br class="clear"/>'
	));
	echo $this->Form->input('User.id');
	echo $this->Form->input('User.role_id', array('type' => 'hidden', 'value' => 1));
	echo $this->Form->hidden('User.self_sign_edit', array('value' => 'edit'));
	?>
    </fieldset>
    <?php echo $form->end(array('label' => 'Submit', 'class' => 'self-sign-kiosk-button')); ?>
</div>
