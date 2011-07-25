<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Self Sign Categories', true), null, 'unique'); ?>
</div>
<div class="selfScanCategories admin">
    <div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link(__('Add Main Category', true), array('action' => 'add')); ?></li>
	</ul>
    </div>
    <table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
	    <tr>
		<th class="ui-state-default"><?php __('Name') ?></th>
		<th class="ui-state-default"><?php __('Queue Cat'); ?></th>
		<th class="ui-state-default"><?php __('Cat 1'); ?></th>
		<th class="ui-state-default"><?php __('Cat 2'); ?></th>
		<th class="ui-state-default"><?php __('Cat 3')?></th>
		<th class="actions ui-state-default"><?php __('Actions'); ?></th>
	    </tr>
	</thead>
	<?php foreach($selfScanCategories as $selfScanCategory):?>
    	<tr>
    	    <td><?php echo $selfScanCategory['SelfScanCategory']['name']; ?>&nbsp;</td>
    	    <td><?php echo $selfScanCategory['DocumentQueueCategory']['name']; ?>&nbsp;</td>
    	    <td><?php echo $selfScanCategory['DocumentFilingCategory']['name']; ?>&nbsp;</td>
    	    <td><?php echo $selfScanCategory['Cat2']['name']; ?>&nbsp;</td>
    	    <td><?php echo $selfScanCategory['Cat3']['name']; ?>&nbsp;</td>
    	    <td class="actions">
		<?php if(empty($selfScanCategory['DocumentQueueCategory']['name']) && empty($selfScanCategory['DocumentFilingCategory']['name'])) :?>
		    <?php echo $this->Html->link(__('Add Child Category', true), array('action' => 'add', $selfScanCategory['SelfScanCategory']['id']), array('class' => 'add')); ?>
		<?php endif ?>
		<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $selfScanCategory['SelfScanCategory']['id']), array('class' => 'edit')); ?>
		<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $selfScanCategory['SelfScanCategory']['id']), array('class' => 'delete'), __('Are you sure you want to delete this category?', true)); ?>
	    </td>
	</tr>
	    <?php foreach($selfScanCategory['children'] as $k => $v) :?>
		<tr class="child-cat">
		<td><?php echo '- ' . $v['SelfScanCategory']['name']; ?>&nbsp;</td>
		<td><?php echo $v['DocumentQueueCategory']['name']; ?>&nbsp;</td>
		<td><?php echo $v['DocumentFilingCategory']['name']; ?>&nbsp;</td>
		<td><?php echo $v['Cat2']['name']; ?>&nbsp;</td>
		<td><?php echo $v['Cat3']['name']; ?>&nbsp;</td>
		<td class="actions">
		    <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $v['SelfScanCategory']['id']), array('class' => 'edit')); ?>
		    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $v['SelfScanCategory']['id']), array('class' => 'delete'), __('Are you sure you want to delete this category?', true)); ?>
		</td>
	    </tr>
	    <?php endforeach; ?>
	<?php endforeach; ?>
    </table>
</div>
