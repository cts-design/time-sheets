<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Form Fields', null, 'unique'); ?></div>
<div class="formFields admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Form Field', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('form_id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('type');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('metadata');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('created');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('modified');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($formFields as $formField):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $formField['FormField']['id']; ?>&nbsp;</td>
		<td><?php echo $formField['FormField']['form_id']; ?>&nbsp;</td>
		<td><?php echo $formField['FormField']['type']; ?>&nbsp;</td>
		<td><?php echo $formField['FormField']['metadata']; ?>&nbsp;</td>
		<td><?php echo $formField['FormField']['created']; ?>&nbsp;</td>
		<td><?php echo $formField['FormField']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $formField['FormField']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $formField['FormField']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $formField['FormField']['id'])); ?>
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
