<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div class="self-sign-wrapper">
    <h1><?php printf(__('Welcome to %s. Please sign in here.', true), Configure::read('Company.name')) ?></h1>
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
	    echo $form->end(array('label' => __('Login', true), 'class' => 'self-sign-kiosk-button'));
    ?>
    <?php if ($kioskHasSurvey): ?>
    	<div class="survey-button">
		<a href="/kiosk/survey/<?php echo $kiosk['KioskSurvey'][0]['id'] ?>">Take Survey</a>
    	</div>
    <?php endif ?>
</div>
