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
    <?php echo $crumb->getHtml('Ftp Document Scanners', null, 'unique'); ?>
</div>
<div class="ftpDocumentScanners admin">
    <div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link(__('New Ftp Document Scanner', true), array('action' => 'add')); ?></li>
	</ul>
    </div>
    <table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
	    <tr>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('device_ip'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('device_name'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('location_id'); ?></th>
		<th class="actions ui-state-default"><?php __('Actions'); ?></th>
	    </tr>
	</thead>
	<?php
	$i = 0;
	foreach($ftpDocumentScanners as $ftpDocumentScanner):
	    $class = null;
	    if($i++ % 2 == 0) {
		$class = ' class="altrow"';
	    }
	?>
    	<tr<?php echo $class; ?>>
    	    <td><?php echo $ftpDocumentScanner['FtpDocumentScanner']['device_ip']; ?>&nbsp;</td>
    	    <td><?php echo $ftpDocumentScanner['FtpDocumentScanner']['device_name']; ?>&nbsp;</td>
    	    <td><?php echo $ftpDocumentScanner['Location']['name']; ?></td>
    	    <td class="actions">
		<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $ftpDocumentScanner['FtpDocumentScanner']['id']), array('class' => 'edit')); ?>
		<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $ftpDocumentScanner['FtpDocumentScanner']['id']), array('class' => 'delete'), __('Are you sure you want to delete this scanner?', true)); ?>
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
	<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
		 | 	<?php echo $this->Paginator->numbers(); ?>
		|
	<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>
</div>
