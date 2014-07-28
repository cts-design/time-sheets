<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<?php echo $this->Html->scriptStart() ?>
	$(document).ready(function(){	
		$('a').click(function(e){
			e.preventDefault();
			var link = $(this).attr('href');
			$('.self-scan-kiosk-link).button("disable");
			if(link) {
				window.location.href = link;
			}		
		});
	});
<?php echo $this->Html->scriptEnd() ?>
<div id="selfScanAnother" class="self-scan-wrapper">
	<h1><?php printf(__('Would you like to scan another document for %s, %s?', true), $this->Session->read('Auth.User.lastname'), $this->Session->read('Auth.User.firstname')) ?></h1>
    <?php echo $this->Session->flash(); ?>
    <div class="actions">
	<?php echo $this->Html->link(__('Yes Scan Another', true), array('action' => 'self_scan_program_selection', 'kiosk' => true), array('class' => 'self-scan-kiosk-link'))?>
	<?php echo $this->Html->link(__('No Logout', true), '/users/logout/kiosk', array('class' => 'self-scan-kiosk-link'))?>
  
    </div>
</div>