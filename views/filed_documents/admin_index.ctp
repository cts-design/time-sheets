<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
 ?>
<?php echo $this->Html->script('filed_documents/index', array('inline' => false)); ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Filed Documents', true)) ; ?>
</div>
<div class="filedDocuments admin">
<div class="actions ui-widget-header">
	<ul>
	   
	    <?php if($actButton) { ?>
	    <li><?php echo $this->Html->link(__('Upload', true), array('controller' => 'filed_documents',  'action' => 'upload_document', $user['User']['id'])); ?></li>
	    <li><?php echo $this->Html->link(__('Scan', true), array('controller' => 'filed_documents',  'action' => 'scan_document', $user['User']['id'])); ?></li>
		<li>
		    <?php
			    echo $this->Html->link(__('Activity', true),
				array(
				    'controller' => 'user_transactions',
				    'action' => 'index', (!empty($this->params['pass'][0]) ? $this->params['pass'][0] : null)));
			?>
		</li>
	    <?php } ?></ul>	
</div>
	<table cellpadding="0" cellspacing="0">
	    <thead class="ui-widget-header">
		<tr>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort('id');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort(__('Customer', true), 'User.lastname');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort(__('Filed Location Id', true), 'filed_location_id');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort(__('Admin', true), 'Admin.lastname');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort('Cat 1', 'Cat1.name');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort('Cat 2', 'Cat2.name');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort('Cat 3', 'Cat3.name');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort(__('Description', true), 'description');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort(__('Created', true), 'created');?></th>
		    <th class="ui-state-default"><?php echo $this->Paginator->sort(__('Last Activity Admin', true), 'LastActAdmin.lastname');?></th>
		    <th class="actions ui-state-default"><?php __('Actions');?></th>
		</tr>
	    </thead>
	<?php
	$i = 0;
	foreach ($filedDocuments as $filedDocument):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $filedDocument['FiledDocument']['id']; ?>&nbsp;</td>
		<td>
		    <?php $name = $filedDocument['User']['lastname']. ', ' . $filedDocument['User']['firstname'] ; ?>
		    <?php $name .= ($filedDocument['User']['ssn'] != 0 ) ? ' - ' . $filedDocument['User']['ssn'] : '' ?>
		    <?php echo $this->Html->link($name, array('controller' => 'users', 'action' => 'edit', $filedDocument['User']['id']));?>
		</td>
		<td><?php echo $filedDocument['Location']['name']; ?>&nbsp;</td>
		<td><?php echo (isset($filedDocument['Admin']['lastname'])) ? $filedDocument['Admin']['lastname'] . ', ' . $filedDocument['Admin']['firstname'] : ''; ?>&nbsp;</td>
		<td><?php echo $filedDocument['Cat1']['name']; ?>&nbsp;</td>
		<td><?php echo $filedDocument['Cat2']['name']; ?>&nbsp;</td>
		<td><?php echo $filedDocument['Cat3']['name']; ?>&nbsp;</td>
		<td><?php echo $filedDocument['FiledDocument']['description']; ?>&nbsp;</td>
		<td><?php echo $time->format('m-d-Y g:i a', $filedDocument['FiledDocument']['created']); ?>&nbsp;</td>
		<td><?php echo (isset($filedDocument['LastActAdmin']['lastname'])) ? $filedDocument['LastActAdmin']['lastname'] . ', ' . $filedDocument['LastActAdmin']['firstname'] : ''; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true),
				    array('action' => 'view', $filedDocument['FiledDocument']['id']), array('class' => 'view')); ?>
			<?php echo $this->Html->link(__('Edit', true),
				    array('action' => 'edit', $filedDocument['FiledDocument']['id'], (!empty($this->params['pass'][0]) ? $this->params['pass'][0] : null) ), array('class'=>'edit')); ?>
			<?php echo $this->Html->link(__('Delete', true), 'admin/filed_documents/delete/' .$filedDocument['FiledDocument']['id'], array('class'=>'delete', 'rel' => $filedDocument['FiledDocument']['id'])); ?>
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

	<div id="modalDeleteForm">
	    <?php echo $this->Form->create(array('action' => 'delete'))?>
	    <?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => '')); ?>
	    <?php echo $this->Form->input('reason', array('type' => 'select', 'options' => $reasons))?>
	    <?php echo $this->Form->end('Delete Doc') ?>
	</div>
</div>
