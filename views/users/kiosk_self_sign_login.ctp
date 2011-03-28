<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div class="self-sign-wrapper">
    <h1>Welcome to <?php echo Configure::read('Company.name').'.'; ?> Please sign in here.</h1>
    <div id="errorMessage"></div>
    <?php echo $this->Session->flash(); ?>
    <?php
	    echo $this->Session->flash('auth');
	    echo $form->create('User', array('action' => 'self_sign_login'));
	    echo $form->input('User.username', array(
			'label' => __('Last Name', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p><br class="clear"/>'));
	    echo $form->input('User.password', array(
			'label' => __('Last 4 Digits of Your SSN', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p><br class="clear"/>',
			'maxlength' => 4));
	    echo $form->hidden('User.login_type', array('value' => 'kiosk'));
	    echo $form->end(array('label' => 'Login', 'class' => 'self-sign-kiosk-button'));
    ?>
</div>