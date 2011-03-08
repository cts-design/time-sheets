<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('In The News', null, 'unique'); ?></div>
<div class="inTheNews admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Article', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('title');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('reporter');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('summary');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('link');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('posted_date');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('created');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('modified');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($inTheNews as $inTheNews):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $inTheNews['InTheNews']['id']; ?>&nbsp;</td>
		<td><?php echo $inTheNews['InTheNews']['title']; ?>&nbsp;</td>
		<td><?php echo $inTheNews['InTheNews']['reporter']; ?>&nbsp;</td>
		<td><?php echo $inTheNews['InTheNews']['summary']; ?>&nbsp;</td>
		<td><?php echo $inTheNews['InTheNews']['link']; ?>&nbsp;</td>
		<td><?php echo $inTheNews['InTheNews']['posted_date']; ?>&nbsp;</td>
		<td><?php echo $inTheNews['InTheNews']['created']; ?>&nbsp;</td>
		<td><?php echo $inTheNews['InTheNews']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $inTheNews['InTheNews']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $inTheNews['InTheNews']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $inTheNews['InTheNews']['id'])); ?>
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
