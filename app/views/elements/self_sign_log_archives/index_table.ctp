<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<?php
$this->Paginator->options(array(
    'update' => '#ajaxContent',
    'url' => $this->passedArgs
));
?>
<div id="processing"><p>Processing......</p></div>
    <table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
	    <tr>
		<th class="ui-state-default">Name</th>
		<th class="ui-state-default">Location</th>
		<th class="ui-state-default">Kiosk</th>
		<th class="ui-state-default">Button 1</th>
		<th class="ui-state-default">Button 2</th>
		<th class="ui-state-default">Button 3</th>
		<th class="ui-state-default">Other</th>
		<th class="ui-state-default">Status</th>
		<th class="ui-state-default">Last Activity Admin</th>
		<th class="ui-state-default">Created</th>
		<th class="ui-state-default">Closed</th>
		<th class="ui-state-default">Closed In</th>
	    </tr>
	</thead>
	<?php
	$i = 0;
	foreach ($selfSignLogArchives as $selfSignLogArchive):
	    $class = null;
	    if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	    }
	?>
    	<tr<?php echo $class; ?>>
    	    <td>
		<?php echo $selfSignLogArchive['User']['lastname']; ?>
		<?php echo ', ' ?>
		<?php echo $selfSignLogArchive['User']['firstname']; ?>
	    </td>
	    <td><?php echo $selfSignLogArchive['Location']['name']; ?></td>
	    <td><?php echo $selfSignLogArchive['Kiosk']['location_recognition_name']; ?></td>
	    <td><?php echo $masterButtonList[$selfSignLogArchive['SelfSignLogArchive']['level_1']]; ?>&nbsp;</td>
	    <td><?php echo (!empty($selfSignLogArchive['SelfSignLogArchive']['level_2'])) ? $masterButtonList[$selfSignLogArchive['SelfSignLogArchive']['level_2']]: ''; ?>&nbsp;</td>
	    <td><?php echo (!empty($selfSignLogArchive['SelfSignLogArchive']['level_3'])) ? $masterButtonList[$selfSignLogArchive['SelfSignLogArchive']['level_3']]: ''; ?>&nbsp;</td>
	    <td><?php echo $selfSignLogArchive['SelfSignLogArchive']['other']; ?>&nbsp;</td>
	    <td><?php echo $statuses[$selfSignLogArchive['SelfSignLogArchive']['status']]; ?>&nbsp;</td>
	    <td><?php echo trim($selfSignLogArchive['Admin']['lastname'] . ', ' . $selfSignLogArchive['Admin']['firstname'], ', '); ?></td>
	    <td><?php echo $selfSignLogArchive['SelfSignLogArchive']['created']; ?>&nbsp;</td>
	    <td><?php echo $selfSignLogArchive['SelfSignLogArchive']['closed']; ?>&nbsp;</td>
	    <td><?php echo $selfSignLogArchive['SelfSignLogArchive']['closed_in']  ?>&nbsp;</td>
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