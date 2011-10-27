<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Featured Employers', true), null, 'unique'); ?></div>
<div class="featuredEmployers admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Featured Employer', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Name', true), 'name');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Description', true), 'description');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Image', true), 'image');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Url', true), 'url');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($featuredEmployers as $featuredEmployer):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $featuredEmployer['FeaturedEmployer']['id']; ?>&nbsp;</td>
		<td><?php echo $featuredEmployer['FeaturedEmployer']['name']; ?>&nbsp;</td>
		<td><?php echo $featuredEmployer['FeaturedEmployer']['description']; ?>&nbsp;</td>
		<td><?php echo $featuredEmployer['FeaturedEmployer']['image']; ?>&nbsp;</td>
		<td><?php echo $featuredEmployer['FeaturedEmployer']['url']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $featuredEmployer['FeaturedEmployer']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $featuredEmployer['FeaturedEmployer']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $featuredEmployer['FeaturedEmployer']['id'])); ?>
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
