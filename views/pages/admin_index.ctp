<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Pages', null, 'unique'); ?>
</div>

<div class="pages admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Page', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('title');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('slug');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('published');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('created');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('modified');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($pages as $page):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $page['Page']['id']; ?>&nbsp;</td>
		<td><?php echo $page['Page']['title']; ?>&nbsp;</td>
		<td><?php echo $page['Page']['slug']; ?>&nbsp;</td>
		<td><?php echo ($page['Page']['published'] == 1) ? 'Yes' : 'No' ?></td>
		<td><?php echo $page['Page']['created']; ?>&nbsp;</td>
		<td><?php echo $page['Page']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $page['Page']['id']), array('class'=>'edit')); ?>
            <?php if ($page['Page']['locked'] != 1): ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $page['Page']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete /%s?', true), $page['Page']['slug'])); ?>
            <?php endif ?>
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
