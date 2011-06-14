<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<div id="selfSignOther" class="self-sign-wrapper">
    <h1>Please describe service needed.</h1>
    <?php echo $this->Form->create('Kiosk') ?>
    <?php
	echo $this->Form->input('SelfSignLog.other', array(
	    'before' => '<p class="left">',
	    'between' => '</p><p class="left">',
	    'after' => '</p><br class="clear"/>'
	))
	?>
    <?php echo $form->end(array('label' => 'Submit', 'class' => 'self-sign-kiosk-button')); ?>
    <div class="actions">
	<?php
	    if($referer != null) {
		echo $this->Html->link('Go Back', $referer, array('class' => 'self-sign-kiosk-link'));
	    }
	    ?>
    </div>
</div>