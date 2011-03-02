<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Rfps', null, 'unique'); ?></div>
<div class="rfps admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Rfp', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('title');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('byline');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('description');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('deadline');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('expires');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('contact_email');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('file');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($rfps as $rfp):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $rfp['Rfp']['id']; ?>&nbsp;</td>
		<td><?php echo $rfp['Rfp']['title']; ?>&nbsp;</td>
		<td><?php echo $rfp['Rfp']['byline']; ?>&nbsp;</td>
		<td><?php echo $rfp['Rfp']['description']; ?>&nbsp;</td>
		<td><?php echo $rfp['Rfp']['deadline']; ?>&nbsp;</td>
		<td><?php echo $rfp['Rfp']['expires']; ?>&nbsp;</td>
		<td><?php echo $rfp['Rfp']['contact_email']; ?>&nbsp;</td>
		<td><?php echo $rfp['Rfp']['file']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $rfp['Rfp']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $rfp['Rfp']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $rfp['Rfp']['id'])); ?>
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
