<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => FALSE)); ?>
<?php echo $this->Html->script('ckfinder/ckfinder', array('inline' => FALSE)); ?>
<?php echo $this->Html->script('locations/wysiwyg', array('inline' => FALSE)); ?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Edit Location', null, 'unique'); ?>
</div>
<div class="locations form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
<?php echo $this->Form->create('Location');?>
	<fieldset>
 		<legend><?php __('Admin Edit Location'); ?></legend>
	<?php
		echo $this->Form->input('name', array(
							'disabled' => true,
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('public_name', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('address_1', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('address_2', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('city', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('state', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('zip', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('country', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('telephone', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	
	<?php
		echo $this->Form->input('fax', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
	
	<fieldset>
		<legend><?php __('Location Hours') ?></legend>
		<?php
			echo $this->Form->input('hours', array(
								'before' => '<p class="left">',
								'between' => '</p><p class="left">',
								'after' => '</p>',
								'type' => 'textarea'));
			echo '<br class="clear" />';
		?>		
	</fieldset>
	<fieldset>
		<legend><?php echo __('Location Facilities') ?></legend>
		<?php
			echo $this->Form->input('facilities', array(
								'before' => '<p class="left">',
								'between' => '</p><p class="left wide">',
								'after' => '</p>'));
			echo '<br class="clear" />';
		?>			
	</fieldset>
		<?php
			echo $this->Form->input('hidden', array(
								'label' => 'Hide from website',
								'before' => '<br /><p class="left">',
								'between' => '</p><p class="left checkbox">',
								'after' => '</p>'));
			echo '<br class="clear" />';
		?>		
<?php echo $this->Form->end(__('Submit', true));?>
</div>
