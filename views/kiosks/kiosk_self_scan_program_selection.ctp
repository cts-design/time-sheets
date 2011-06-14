<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<div id="selfScanProgram" class="self-scan-wrapper">
    <h1>Select program to scan document for <?php echo $this->Session->read('Auth.User.lastname') .', ' .$this->Session->read('Auth.User.firstname'); ?></h1>
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
		echo $this->Html->link('Go Back', $referer, array('class' => 'self-sign-kiosk-link'));
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
		echo $this->Html->link('Go Back', $referer, array('class' => 'self-sign-kiosk-link'));
	    }
	}
	?>
    </div>
</div>