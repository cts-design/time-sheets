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
    <?php echo $crumb->getHtml('Add Location', null, 'unique'); ?>
</div>
<div class="locations form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('Location');?>
	<fieldset>
 		<legend><?php __('Admin Add Location'); ?></legend>
	<?php
		echo $this->Form->input('name', array(
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
			echo $this->Form->input('open', array(
								'before' => '<p class="left">',
								'between' => '</p><p class="left time">',
								'after' => '</p>'));
			echo '<br class="clear" />';
			
			echo $this->Form->input('close', array(
								'before' => '<p class="left">',
								'between' => '</p><p class="left time">',
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
