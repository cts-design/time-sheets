<div class="selfScanCategories view">
<h2><?php  __('Self Scan Category');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Queue Cat Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['queue_cat_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cat 1'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['cat_1']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cat 2'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['cat_2']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Cat 3'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['cat_3']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Parent Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['parent_id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $selfScanCategory['SelfScanCategory']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Self Scan Category', true), array('action' => 'edit', $selfScanCategory['SelfScanCategory']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Self Scan Category', true), array('action' => 'delete', $selfScanCategory['SelfScanCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $selfScanCategory['SelfScanCategory']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Self Scan Categories', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Self Scan Category', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
