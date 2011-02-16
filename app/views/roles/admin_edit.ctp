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
    <?php echo $crumb->getHtml('Edit Role', null, 'unique'); ?>
</div>
<div class="roles form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
    <?php echo $this->Form->create('Role'); ?>
    <fieldset>
	<legend><?php __('Admin Edit Role'); ?></legend>
	<?php
	echo $this->Form->input('id');
	echo $this->Form->input('name', array(
	    'before' => '<p class="left">',
	    'between' => '</p><p class="left">',
	    'after' => '</p>'));
	echo '<br class="clear" />';
	?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit', true)); ?>
</div>
