<?php
/**
 * @author Joseph Shering
 * @copyright Complete Technology Solutions 2014
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->scriptStart() ?>
	$(document).ready(function(){
		$('#UserSelfSignLoginForm').submit(function(){
			$('.self-sign-kiosk-button').button("disable");
			return true;
		});
	});
<?php echo $this->Html->scriptEnd() ?>
<style>
.error-message {
	margin-left:415px;
}
</style>
<div class="self-sign-wrapper">
    <h1>
    	<?php printf('Welcome to %s. Please sign in here.', Configure::read('Company.name')) ?>
    </h1>
    <div id="errorMessage"></div>
    <?= $this->Session->flash(); ?>

    <p style="padding:10px">
    	We could not find that card in the system, please enter your last name and nine digit Social Security Number
    </p>
    <?php
	    echo $this->Session->flash('auth');
	    echo $form->create('User', array('action' => 'kiosk_id_card_confirm'));
	    echo $form->input('lastname', array(
			'label' => __('Last Name', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p><br class="clear"/>'));
	    echo $form->input('ssn', array(
			'label' => __('9 Digit SSN', true),
			'type' => 'password',
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p><br class="clear"/>',
			'maxlength' => 9));
	    echo $form->end(array('label' => __('Login', true), 'class' => 'self-sign-kiosk-button'));
    ?>
</div>
