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
    <?php echo $crumb->getHtml(__('Edit Document Queue Category', true), null, 'unique') ; ?>
</div>
<div class="documentQueueCategories form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
<?php echo $this->Form->create('DocumentQueueCategory');?>
	<fieldset>
 		<legend><?php __('Admin Edit Document Queue Category'); ?></legend>
	<?php
		echo $this->Form->input('id', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo $this->Form->input('ftp_path', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('name', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo '<div class="input"><p class="left">';
		echo $this->Form->label('Secure');
		echo '</p>';
		echo $this->Form->checkbox('secure');
		echo '</div>';
		echo '<br class="clear" />';		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
