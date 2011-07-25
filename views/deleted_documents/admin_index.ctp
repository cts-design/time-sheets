<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<script>
    $(document).ready(function(){
	$('.date').datepicker();
    });
</script>
<?php $paginator->options(array('url' => $this->passedArgs));?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Deleted Documents', true), null, 'unique'); ?>
</div>
<div class="deletedDocuments admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
    <div id="deletedDoumentsSerach">
	<?php echo $this->Form->create()?>
	<?php echo $this->Form->input('from_date', 
		array(
		    'class' => 'date',
		    'value' => (isset($this->passedArgs['from_date']) ? $this->Time->format('m/d/Y', $this->passedArgs['from_date']) : '')))
	    ?>
	<?php echo $this->Form->input('to_date', 
		array(
		    'class' => 'date',
		    'value' => (isset($this->passedArgs['to_date']) ? $this->Time->format('m/d/Y', $this->passedArgs['to_date']) : '')))
	    ?>
	<?php echo $this->Form->end(__('Search', true))?>
	<span class="quick-dates">
	    Quick Dates: 
	    <?php echo $this->Html->link(__('Today', true), array('action' => 'index', 'today'))?>
	    |
	    <?php echo $this->Html->link(__('Yesterday', true), array('action' => 'index', 'yesterday'))?>
	    |
	    <?php echo $this->Html->link(__('Last 7 Days', true), array('action' => 'index', 'last_7'))?>
	    |
	    <?php echo $this->Html->link(__('Last Month', true), array('action' => 'index', 'last_month'))?>
	</span>
    </div>
    <table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
	    <tr>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('id'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Admin', true), 'Admin.lastname'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Customer', true), 'User.lastname'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Deleted Location Id', true), 'deleted_location_id'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('Cat 1', 'Cat1.name'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('Cat 2', 'Cat2.name'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort('Cat 3', 'Cat3.name'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Description', true), 'description'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Entry Method', true), 'entry_method'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Deleted Reason', true), 'deleted_reason'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Last Activity Admin Id', true), 'last_activity_admin_id'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Created', true), 'created'); ?></th>
		<th class="ui-state-default"><?php echo $this->Paginator->sort(__('Modified', true), 'modified'); ?></th>
		<th class="actions ui-state-default"><?php __('Actions'); ?></th>
	    </tr>
	</thead>
	<?php
	$i = 0;
	foreach($deletedDocuments as $deletedDocument):
	    $class = null;
	    if($i++ % 2 == 0) {
		$class = ' class="altrow"';
	    }
	?>
    	<tr<?php echo $class;?>>
    	    <td><?php echo $deletedDocument['DeletedDocument']['id']; ?>&nbsp;</td>
    	    <td>
		<?php echo (!empty($deletedDocument['Admin']['lastname'])) ? $deletedDocument['Admin']['lastname']  .', '. $deletedDocument['Admin']['firstname'] : ''; ?>&nbsp;
	    </td>
    	    <td>
		<?php echo (!empty($deletedDocument['User']['lastname'])) ?  $deletedDocument['User']['lastname'] . ', ' . $deletedDocument['User']['firstname'] : '' ;?>&nbsp;
	    </td>
	    <td><?php echo $deletedDocument['Location']['name'] ?>&nbsp;</td>
	    <td><?php echo $deletedDocument['Cat1']['name']; ?>&nbsp;</td>
	    <td><?php echo $deletedDocument['Cat2']['name']; ?>&nbsp;</td>
	    <td><?php echo $deletedDocument['Cat3']['name']; ?>&nbsp;</td>
	    <td><?php echo $deletedDocument['DeletedDocument']['description']; ?>&nbsp;</td>
	    <td><?php echo $deletedDocument['DeletedDocument']['entry_method']; ?>&nbsp;</td>
	    <td><?php echo $deletedDocument['DeletedDocument']['deleted_reason']; ?>&nbsp;</td>
	    <td>
		<?php echo (!empty($deletedDocument['LastActAdmin']['lastname'])) ? $deletedDocument['LastActAdmin']['lastname']  .', '. $deletedDocument['LastActAdmin']['firstname'] : ''; ?>&nbsp;
	    </td>
	    <td><?php echo $this->Time->format('m-d-y g:i a', $deletedDocument['DeletedDocument']['created']); ?>&nbsp;</td>
	    <td><?php echo $this->Time->format('m-d-y g:i a', $deletedDocument['DeletedDocument']['modified']); ?>&nbsp;</td>
	    <td class="actions">
		<?php echo $this->Html->link(__('View', true), array('action' => 'view', $deletedDocument['DeletedDocument']['id']), array('class' => 'view')); ?>
		<?php echo $this->Html->link(__('Restore', true), array('action' => 'restore', $deletedDocument['DeletedDocument']['id']), array('class' => 'restore')); ?>
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
