<?php $this->Html->scriptStart(array('inline' => false));?>
    var cat2 = <?php echo $this->Js->value($this->data['SelfScanCategory']['cat_2']);?>;
    var cat3 = <?php echo $this->Js->value($this->data['SelfScanCategory']['cat_3']);?>;
<?php $this->Html->scriptEnd(); ?>
<?php echo $this->Html->script('self_scan_categories/edit', array('inline' => 'false'));?>
<?php echo $this->Html->script('jquery.validate', array('inline' => 'false'));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Edit Self Sign Category', true), null, 'unique'); ?>
</div>
<div class="selfScanCategories form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
<?php echo $this->Form->create('SelfScanCategory');?>
	<fieldset>
		<div class="formErrors"></div>
 		<legend><?php __('Admin Edit Self Scan Category'); ?></legend>
	<?php
		echo $this->Form->input('id', array(
							'class' => 'required',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo $this->Form->input('name', array(
							'class' => 'required',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		if(!empty($this->data['SelfScanCategory']['queue_cat_id'])) {
		    echo $this->Form->input('queue_cat_id', array(
					    'empty' => 'Select Cat',
					    'options' => $queueCatList,
					    'before' => '<p class="left">',
					    'between' => '</p><p class="left">',
					    'after' => '</p>'));
		
		echo '<br class="clear" />';
		}
		if(!empty($this->data['SelfScanCategory']['cat_1'])) {
		    echo $this->Form->input('cat_1', array(
							    'type' => 'select',
							    'options' => $cat1,
							    'label' => 'Filing Cats',
							    'empty' => 'Select Main Cat',
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
		}
		echo $this->Form->input('parent_id', array(
							'type' => 'hidden'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
