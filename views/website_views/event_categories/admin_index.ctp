<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Event Categories', true), null, 'unique'); ?></div>
<div class="eventCategories admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Event Category', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Name', true), 'name');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Created, true'), 'created');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Modified', true), 'modified');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($eventCategories as $eventCategory):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $eventCategory['EventCategory']['id']; ?>&nbsp;</td>
		<td><?php echo $eventCategory['EventCategory']['name']; ?>&nbsp;</td>
		<td><?php echo $eventCategory['EventCategory']['created']; ?>&nbsp;</td>
		<td><?php echo $eventCategory['EventCategory']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $eventCategory['EventCategory']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $eventCategory['EventCategory']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $eventCategory['EventCategory']['id'])); ?>
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
