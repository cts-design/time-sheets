<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Hot Jobs', null, 'unique'); ?></div>
<div class="hotJobs admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Hot Job', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('employer');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('title');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('description');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('location');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('url');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('reference_number');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('contact');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('file');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($hotJobs as $hotJob):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $hotJob['HotJob']['id']; ?>&nbsp;</td>
		<td><?php echo $hotJob['HotJob']['employer']; ?>&nbsp;</td>
		<td><?php echo $hotJob['HotJob']['title']; ?>&nbsp;</td>
		<td><?php echo $hotJob['HotJob']['description']; ?>&nbsp;</td>
		<td><?php echo $hotJob['HotJob']['location']; ?>&nbsp;</td>
		<td><?php echo $hotJob['HotJob']['url']; ?>&nbsp;</td>
		<td><?php echo $hotJob['HotJob']['reference_number']; ?>&nbsp;</td>
		<td><?php echo $hotJob['HotJob']['contact']; ?>&nbsp;</td>
		<td><?php echo $hotJob['HotJob']['file']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $hotJob['HotJob']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $hotJob['HotJob']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $hotJob['HotJob']['id'])); ?>
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
