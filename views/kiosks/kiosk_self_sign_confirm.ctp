<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="selfSignConfirm" class="self-sign-wrapper">
	<h1><?php __('Please verify that the information we have in our records is correct.') ?> </h1>
    <br />
    <?php echo $this->Session->flash(); ?>
    <p class="left align-right">
	<strong><?php __('First Name:') ?> </strong>
    </p>
    <p class="left">
	<?php echo $session->read('Auth.User.firstname'); ?>
    </p>
    <br class="clear" />
    <p class="left align-right">
	<strong><?php __('Last Name:') ?> </strong>
    </p>
    <p class="left">
	<?php echo $session->read('Auth.User.lastname'); ?>
    </p>
    <br class="clear" />
    <p class="left align-right">
	<strong><?php __('Zip Code:') ?> </strong>
    </p>
    <p class="left">
	<?php echo $session->read('Auth.User.zip'); ?>
    </p>
    <br />
    <div class="actions">
	<?php
	echo $html->link(__('Correct', true), 'self_sign_service_selection', array('class' => 'self-sign-kiosk-link'));
	echo '&nbsp;';
	echo $html->link(__('Incorrect', true), 'self_sign_edit/' . $session->read('Auth.User.id'), array('class' => 'self-sign-kiosk-link'));
	?>
    </div>
    <br class="clear" />
</div>






