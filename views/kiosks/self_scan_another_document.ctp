<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<div id="selfScanAnother" class="self-scan-wrapper">
    <h1>Would you like to scan another document for <?php echo $this->Session->read('Auth.User.lastname') .', ' .$this->Session->read('Auth.User.firstname'); ?> ?</h1>
    <?php echo $this->Session->flash(); ?>
    <div class="actions">
	<?php echo $this->Html->link('Yes Scan Another', array('action' => 'self_scan_program_selection'), array('class' => 'self-scan-kiosk-link'))?>
	<?php echo $this->Html->link('No Logout', array( 'controller'  => 'users',  'action' => 'logout'), array('class' => 'self-scan-kiosk-link'))?>
    </div>
</div>