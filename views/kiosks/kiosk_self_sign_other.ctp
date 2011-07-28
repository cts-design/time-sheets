<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<div id="selfSignOther" class="self-sign-wrapper">
    <h1><?php __('Please describe service needed.') ?></h1>
    <?php echo $this->Session->flash(); ?>
    <?php $url = '/kiosk/kiosks/self_sign_other'; ?>
    <?php if(!empty($this->params['pass'][0])) : ?>
    	<?php $url = '/kiosk/kiosks/self_sign_other/'. $this->params['pass'][0]; ?>
    <?php endif ?>		
    <?php echo $this->Form->create(null, array('url' => $url)) ?>
    <?php
		echo $this->Form->input('SelfSignLog.other', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p><br class="clear"/>'
		))
	?>
    <?php echo $form->end(array('label' => __('Submit', true), 'class' => 'self-sign-kiosk-button')); ?>
    <div class="actions">
		<?php if($referer != null) : ?>
			<?php echo $this->Html->link(__('Go Back', true), $referer, array('class' => 'self-sign-kiosk-link'))?>
		<?php endif ?>
    </div>
</div>
