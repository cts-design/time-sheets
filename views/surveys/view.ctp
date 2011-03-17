<div class="surveys view">
<h2><?php  __('Survey');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $survey['Survey']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $survey['Survey']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Published'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $survey['Survey']['published']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $survey['Survey']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $survey['Survey']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Survey', true), array('action' => 'edit', $survey['Survey']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Survey', true), array('action' => 'delete', $survey['Survey']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $survey['Survey']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Surveys', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
