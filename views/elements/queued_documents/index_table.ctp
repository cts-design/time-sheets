<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<script type="text/javascript">
    $(document).ready(function(){
	$( ".disabled" ).button({ disabled: true });
	$(".next").button();
	$(".prev").button();
	$('.view').button();
	$('.quick-view-pdf').live('click', function(){
	    $('#queuedDocumentsPdf').hide();
	    $(this).nyroModalManual();
	    return false;
	})
	$('th a').live('click', function(){
	    $(this).parent().addClass('ui-state-active')
	});
	if($('th a').hasClass('desc') || $('th a').hasClass('asc')) {
	    $('.asc, .desc').parent().addClass('ui-state-hover');
	}
    });
</script>
<?php
$this->Paginator->options(array(
    'update' => '#queuedDocumentsNav',
    'url' => $this->passedArgs
));
?>
<table cellpadding="0" cellspacing="0" style="font-size: 11px;">
    <thead class="ui-widget-header">
	<tr>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('id'); ?></th>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('filename'); ?></th>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('Queue Category', 'DocumentQueueCategory.name'); ?></th>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('scanned_location_id'); ?></th>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('Locked By', 'User.lastname'); ?></th>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('locked_status'); ?></th>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('Last Activity Admin',  'LastActAdmin.lastname'); ?></th>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('created'); ?></th>
	    <th class="ui-state-default"><?php echo $this->Paginator->sort('modified'); ?></th>
	    <th class="ui-state-default">Quick View</th>
	    <th class="actions ui-state-default"><?php __('Actions'); ?></th>
	</tr>
    </thead>
    <?php
    $i = 0;
    foreach ($queuedDocuments as $queuedDocument):
	$class = null;
	if ($i++ % 2 == 0) {
	    $class = ' class="altrow"';
	}
    ?>
        <tr<?php echo $class; ?>>
    	<td><?php echo $queuedDocument['QueuedDocument']['id']; ?>&nbsp;</td>
    	<td><?php echo $queuedDocument['QueuedDocument']['filename']; ?>&nbsp;</td>
    	<td><?php echo $queuedDocument['DocumentQueueCategory']['name']; ?>&nbsp;</td>
    	<td><?php echo $queuedDocument['Location']['name']; ?>&nbsp;</td>
    	<td><?php echo trim($queuedDocument['User']['lastname'] . ', ' . $queuedDocument['User']['firstname'], ' ,'); ?>&nbsp;</td>
    	<td><?php echo $lockStatuses[$queuedDocument['QueuedDocument']['locked_status']]; ?>&nbsp;</td>
	<td><?php echo trim($queuedDocument['LastActAdmin']['lastname'] . ', ' . $queuedDocument['LastActAdmin']['firstname'], ' ,'); ?>&nbsp;</td>
	<td><?php echo $this->Time->format('m/d/Y h:i a', $queuedDocument['QueuedDocument']['created']); ?>&nbsp;</td>
	<td><?php echo $this->Time->format('m/d/Y h:i a', $queuedDocument['QueuedDocument']['modified']); ?>&nbsp;</td>
	<td>
	    <?php echo $this->Html->link($this->Html->image('icons/pdf.png'),'/admin/queued_documents/view_thumbnail/'. $queuedDocument['QueuedDocument']['id'] . '.jpg',
		array('escape' => false, 'class' => 'quick-view-pdf'))?>
		
	</td>
	<td class="actions">
	    <?php echo $this->Html->link(__('View', true), array('action' => 'index', 'view', $queuedDocument['QueuedDocument']['id'], 'page:' . $this->Paginator->counter(array('format' => __('%page%', true)))), array('class' => 'view')); ?>
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

<?php echo $this->Js->writeBuffer(); ?>