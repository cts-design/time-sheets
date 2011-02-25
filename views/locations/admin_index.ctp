<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Locations', null, 'unique'); ?>
</div>
<div class="locations admin">
<div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link(__('New Location', true), array('action' => 'add')); ?></li>
	</ul>
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort('name');?></th>
		    <th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($locations as $location):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $location['Location']['name']; ?>&nbsp;</td>
		<td class="actions">
		    <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $location['Location']['id']), array('class'=>'edit')); ?>
		    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $location['Location']['id']), array('class'=>'delete'), __('Are you sure you want to delete this location?', true)); ?>
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
