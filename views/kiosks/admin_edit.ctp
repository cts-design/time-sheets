<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Edit Kiosk', true), null, 'unique'); ?>
</div>
<div class="actions ui-widget-header">
    <ul></ul>
</div>
<div class="kiosks form">
    <?php echo $this->Form->create('Kiosk'); ?>
    <fieldset>
		<legend><?php __('Admin Edit Kiosk'); ?></legend>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->input('location_id', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>'
			));
			echo '<br class="clear">';
			echo $this->Form->input('location_recognition_name', array(
				'before' => '<p class="left">',
				'between' => '</p><p class="left">',
				'after' => '</p>',
				'readonly' => true
			));
		    echo '<br class="clear"/>' ;
		    echo $this->Form->input('logout_message', array(
			    'before' => '<p class="left">',
			    'between' => '</p><p class="left">',
			    'after' => '</p>'
		    ));		
			echo '<br class="clear">';
			echo $this->Form->input('assistance_message', array(
			    'before' => '<p class="left">',
			    'between' => '</p><p class="left">',
			    'after' => '</p>'
		    ));		
			echo '<br class="clear">';

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
