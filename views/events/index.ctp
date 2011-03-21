<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Events', null, 'unique'); ?></div>
<div class="events admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Event', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('title');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('description');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('category');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('start');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('end');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('location');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('event_url');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('sponsor');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('sponsor_url');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('created');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('modified');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($events as $event):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $event['Event']['id']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['title']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['description']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['category']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['start']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['end']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['location']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['event_url']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['sponsor']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['sponsor_url']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['created']; ?>&nbsp;</td>
		<td><?php echo $event['Event']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $event['Event']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $event['Event']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $event['Event']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>
	<br />
	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
