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
    <?php echo $crumb->getHtml('Edit Document Filing Category', null, 'unique') ; ?>
</div>
<div class="admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
    <div class="mini-form">
	<?php echo $this->Form->create('DocumentFilingCategory');?>
		<fieldset>
			<legend><?php __('Edit Document Filing Category'); ?></legend>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->input('name');
		?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
    </div>
</div>
