<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<?php if($this->Session->read('Config.language') == 'es-es')  : ?>
	<div id="selfScanProgram" class="self-scan-wrapper spanish">
<?php else : ?>
	<div id="selfScanProgram" class="self-scan-wrapper">
<?php endif ?>	

	<h1><?php printf(__('Select program to scan document for %s, %s', true), $this->Session->read('Auth.User.lastname'), $this->Session->read('Auth.User.firstname')) ?></h1>
    <?php echo $this->Session->flash(); ?>
    <div class="actions">
	<?php
	if (!empty($parentButtons)) {
	    $i = 0;
	    $count = count($parentButtons);
	    while ($i < $count) {
		echo $this->Html->link($parentButtons[$i]['SelfScanCategory']['name'], array(
		    'action' => 'self_scan_program_selection',
		    $parentButtons[$i]['SelfScanCategory']['id']), array('class' => 'self-scan-kiosk-link'));
		$i++;
	    }
	    if($referer != null) {
		echo $this->Html->link(__('Go Back', true), $referer, array('class' => 'self-sign-kiosk-link'));
	    }
	}
	if (!empty($childButtons)) {
	    $i = 0;
	    $count = count($childButtons);
	    while ($i < $count) {
		echo $this->Html->link($childButtons[$i]['SelfScanCategory']['name'], array('action' => 'self_scan_program_selection',
		  $childButtons[$i]['SelfScanCategory']['id']), array('class' => 'self-scan-kiosk-link'));
		$i++;
	    }
	    if($referer != null) {
		echo $this->Html->link(__('Go Back', true), $referer, array('class' => 'self-sign-kiosk-link'));
	    }
	}
	?>
    </div>
</div>
