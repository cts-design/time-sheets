<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Chairman Reports', true), null, 'unique'); ?>
</div>
<div class="chairmanReports admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Chairman Report', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Title', true), 'title');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('File', true), 'file');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Created', true), 'created');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Modified', true), 'modified');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($chairmanReports as $chairmanReport):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $chairmanReport['ChairmanReport']['id']; ?>&nbsp;</td>
		<td><?php echo $chairmanReport['ChairmanReport']['title']; ?>&nbsp;</td>
		<td><?php echo $chairmanReport['ChairmanReport']['file']; ?>&nbsp;</td>
		<td><?php echo $chairmanReport['ChairmanReport']['created']; ?>&nbsp;</td>
		<td><?php echo $chairmanReport['ChairmanReport']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $chairmanReport['ChairmanReport']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $chairmanReport['ChairmanReport']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $chairmanReport['ChairmanReport']['id'])); ?>
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
