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
    <?php echo $crumb->getHtml('Add Ftp Document Scanner', null, 'unique'); ?>
</div>
<div class="ftpDocumentScanners form admin">
    <div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link(__('List Ftp Document Scanners', true), array('action' => 'index'));?></li>
	</ul>
</div>
<?php echo $this->Form->create('FtpDocumentScanner');?>
	<fieldset>
 		<legend><?php __('Admin Add Ftp Document Scanner'); ?></legend>
	<?php
		echo $this->Form->input('device_ip', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('device_name', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('location_id', array(
							'empty' => 'Select Loaction',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
