<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Press Releases', true), null, 'unique'); ?>
</div>
<div class="pressReleases admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Press Release', true), array('action' => 'add')); ?></li>
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
	foreach ($pressReleases as $pressRelease):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $pressRelease['PressRelease']['id']; ?>&nbsp;</td>
		<td><?php echo $pressRelease['PressRelease']['title']; ?>&nbsp;</td>
		<td><?php echo $pressRelease['PressRelease']['file']; ?>&nbsp;</td>
		<td><?php echo $pressRelease['PressRelease']['created']; ?>&nbsp;</td>
		<td><?php echo $pressRelease['PressRelease']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $pressRelease['PressRelease']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $pressRelease['PressRelease']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $pressRelease['PressRelease']['id'])); ?>
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
