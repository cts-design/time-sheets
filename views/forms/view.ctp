<div class="forms view">
<h2><?php  __('Form');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $form['Form']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $form['Form']['title']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $form['Form']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $form['Form']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Form', true), array('action' => 'edit', $form['Form']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Form', true), array('action' => 'delete', $form['Form']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $form['Form']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Forms', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Form', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
