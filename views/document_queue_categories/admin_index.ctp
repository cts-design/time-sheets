<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Document Queue Categories', true), null, 'unique') ; ?>
</div>
<div class="documentQueueCategories admin">
<div class="actions ui-widget-header">
	<ul>
		<li><?php echo $this->Html->link(__('New Document Queue Category', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort(__('FTP Path', true), 'ftp_path');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort(__('Name', true), 'name');?></th>
		    <th class="ui-state-default"><?php __('Secure');?></th>
		    <th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($documentQueueCategories as $documentQueueCategory):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $documentQueueCategory['DocumentQueueCategory']['ftp_path']; ?>&nbsp;</td>
		<td><?php echo $documentQueueCategory['DocumentQueueCategory']['name']; ?>&nbsp;</td>
		<td><?php echo $documentQueueCategory['DocumentQueueCategory']['secure']; ?></td>	
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $documentQueueCategory['DocumentQueueCategory']['id']), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $documentQueueCategory['DocumentQueueCategory']['id']), array('class'=>'delete'), __('Are you sure you want to delete category?', true)); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p class="paging-counter">
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
