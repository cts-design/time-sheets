<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Helpful Articles', true), null, 'unique'); ?></div>
<div class="helpfulArticles admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Helpful Article', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
					<th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Title', true), 'title');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Reporter', true), 'reporter');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Summary', true), 'summary');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Link', true), 'link');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Posted Date', true), 'posted_date');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Created', true), 'created');?></th>
					<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Modified', true), 'modified');?></th>
					<th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($helpfulArticles as $helpfulArticle):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $helpfulArticle['HelpfulArticle']['id']; ?>&nbsp;</td>
		<td><?php echo $helpfulArticle['HelpfulArticle']['title']; ?>&nbsp;</td>
		<td><?php echo $helpfulArticle['HelpfulArticle']['reporter']; ?>&nbsp;</td>
		<td><?php echo $helpfulArticle['HelpfulArticle']['summary']; ?>&nbsp;</td>
		<td><?php echo $helpfulArticle['HelpfulArticle']['link']; ?>&nbsp;</td>
		<td><?php echo $helpfulArticle['HelpfulArticle']['posted_date']; ?>&nbsp;</td>
		<td><?php echo $helpfulArticle['HelpfulArticle']['created']; ?>&nbsp;</td>
		<td><?php echo $helpfulArticle['HelpfulArticle']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $helpfulArticle['HelpfulArticle']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $helpfulArticle['HelpfulArticle']['id']), array('class'=>'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $helpfulArticle['HelpfulArticle']['id'])); ?>
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
