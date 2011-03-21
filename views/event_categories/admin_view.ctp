<div class="eventCategories view">
<h2><?php  __('Event Category');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $eventCategory['EventCategory']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $eventCategory['EventCategory']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $eventCategory['EventCategory']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $eventCategory['EventCategory']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Event Category', true), array('action' => 'edit', $eventCategory['EventCategory']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Event Category', true), array('action' => 'delete', $eventCategory['EventCategory']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $eventCategory['EventCategory']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Event Categories', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event Category', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
