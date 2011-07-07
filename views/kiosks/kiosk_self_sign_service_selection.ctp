<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div id="selfSignService" class="self-sign-wrapper">
   <?php  if (!empty($childButtons)) { ?>
	    <?php if (!empty ($tag)) { ?>
		<h1><?php echo $tag ?></h1>
	    <?php } else { ?>
        <h1><?php __('Please make next service selection.') ?></h1>
	    <?php } ?>
    <?php } else {?>
    <h1><?php __('How can we help you today?') ?></h1>
    <?php } ?>
    <?php echo $this->Session->flash(); ?>
    <div class="actions">
	<?php
	if (!empty($rootButtons)) {
	    $i = 0;
	    $count = count($rootButtons);
	    while ($i < $count) {
		echo $this->Html->link($masterButtonList[$rootButtons[$i]['KioskButton']['id']], array(
		    'action' => 'self_sign_service_selection',
		    $rootButtons[$i]['KioskButton']['id']), array('class' => 'self-sign-kiosk-link'));
		$i++;
	    }
	}
	if (!empty($childButtons)) {
	    $i = 0;
	    $count = count($childButtons);
	    while ($i < $count) {
		echo $this->Html->link($masterButtonList[$childButtons[$i]['KioskButton']['id']], array('action' => 'self_sign_service_selection',
		    $childButtons[$i]['KioskButton']['id'], true), array('class' => 'self-sign-kiosk-link'));
		$i++;
	    }
	    if($referer != null) {
		echo $this->Html->link(__('Go Back', true), $referer, array('class' => 'self-sign-kiosk-link'));
	    }
	}
	?>
    </div>
</div>
