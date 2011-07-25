
<?php echo $this->Html->script('self_scan_categories/add', array('inline' => 'false'));?>
<?php echo $this->Html->script('jquery.validate', array('inline' => 'false'));?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Add Self Sign Category', true), null, 'unique'); ?>
</div>
<div class="selfScanCategories form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
<?php echo $this->Form->create('SelfScanCategory');?>
	<fieldset>
		<div class="formErrors"></div>
 		<legend><?php __('Admin Add Self Scan Category'); ?></legend>
	<?php
		echo $this->Form->input('name', array(
							'class' => 'required',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		if(empty($this->params['pass'][0])) {
		    echo $this->Form->input('has_children', array(
					    'type' => 'select',
					    'options' => array('no' => 'No', 'yes' => 'Yes'),
					    'before' => '<p class="left">',
					    'between' => '</p><p class="left">',
					    'after' => '</p>'));
		    echo '<br class="clear" />';
		}
		echo '<div class="hide">';
		echo $this->Form->input('queue_cat_id', array(
							'empty' => 'Select Cat',
							'class' => 'required',
							'options' => $queueCatList,
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('cat_1', array(
							'type' => 'select',
							'class' => 'required',
							'options' => $cat1,
							'label' => 'Filing Cats',
							'empty' => 'Select Cat',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('cat_2', array(
							'type' => 'select',
							'label' => '&nbsp;',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('cat_3', array(
							'type' => 'select',
							'label' => '&nbsp;',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '</div>';
		echo $this->Form->input('parent_id', array(
							'type' => 'hidden',
							 'value' => $parentId));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
