<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->scriptStart() ?>
	$(document).ready(function(){	
		$('a').click(function(e){
			e.preventDefault();
			var link = $(this).attr('href');
			$('.self-sign-kiosk-link').button("disable");
			if(link) {
				window.location.href = link;	
			}	
		});
	});
<?php echo $this->Html->scriptEnd() ?>
<?php if($this->Session->read('Config.language') == 'es-es')  : ?>
	<div id="selfSignService" class="self-sign-wrapper spanish">
<?php else : ?>
	<div id="selfSignService" class="self-sign-wrapper">
<?php endif ?>				
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
	if (!empty($rootButtons))
	{
	    $i = 0;
	    $count = count($rootButtons);
	    while ($i < $count)
	    {
	    	$button = $rootButtons[$i]['KioskButton'];

	    	$master_button_name = $masterButtonList[$rootButtons[$i]['KioskButton']['id']];
	    	if($button['action'] == 'link')
	    	{
				echo $this->Html->link($master_button_name, $button['action_meta'],
			    	array('class' => 'self-sign-kiosk-link')
			    );
			}
		    else
		    {
		    	echo $this->Html->link($masterButtonList[$rootButtons[$i]['KioskButton']['id']], array(
			    	'action' => 'self_sign_service_selection',
			    	$rootButtons[$i]['KioskButton']['id']),
			    	array('class' => 'self-sign-kiosk-link'
			    	)
			    );
		    }
		$i++;
	    }
	}

	if (!empty($childButtons))
	{
	    $i = 0;
	    $count = count($childButtons);
	    while ($i < $count)
	    {
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
