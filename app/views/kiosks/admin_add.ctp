<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Add Kiosk', null, 'unique'); ?>
</div>
<div class="actions ui-widget-header">
    <ul></ul>
</div>
<div class="kiosks form">
    <?php echo $this->Form->create('Kiosk'); ?>
    <fieldset>
	<legend><?php __('Admin Add Kiosk'); ?></legend>
	<?php
	    echo $this->Form->input('location_id', array(
		    'empty' => 'Select Location',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear"/>' ;
	    echo $this->Form->input('location_recognition_name', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear"/>' ;
	    echo $this->Form->input('logout_message', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));		
	    echo '<br class="clear"/>' ;
	    echo $this->Form->input('location_description', array(
		    'type' => 'textarea',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    
	?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true)); ?>
</div>
