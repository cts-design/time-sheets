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
    <?php echo $crumb->getHtml('Administrators', null, 'unique') ; ?>
</div>
<div class="admin">
    <?php $this->Paginator->options(array('url' => $this->passedArgs)); ?>
    <div class="actions ui-widget-header">
	<ul>
	    <li><?php echo $this->Html->link(__('New Admin', true), array('action' => 'add_admin', 'admin' => true)); ?></li>
	</ul>
    </div>
    <ul class="search">
	<li>
	    <?php echo $this->Form->create() ?>
	    <?php
	    echo $this->Form->input('search_by', array(
		'type' => 'select',
		'options' => array('lastname' => 'Last Name', 'firstname' => 'First Name', 'username' => 'User Name')))
	    ?>
	</li>
	<li><?php echo $this->Form->input('search_term') ?></li>
	<li><?php echo $this->Form->end(array('label' => 'Search', 'div' => false)) ?></li>
    </ul>
    <br class="clear"/>
    <span class="small bot-mar-10">* denotes admin has role overridden permissions</span>
    <table cellpadding="0" cellspacing="0">
	<thead class="ui-widget-header">
	    <tr>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort('firstname'); ?></th>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort('lastname'); ?></th>
		<th width="20%" class="ui-state-default"><?php echo $this->Paginator->sort('email'); ?></th>
		<th width="10%" class="ui-state-default"><?php echo $this->Paginator->sort('Role', 'Role.name'); ?></th>
		<th width="10%" class="actions ui-state-default"><?php __('Actions'); ?></th>
	    </tr>
	</thead>
	<?php
	    $i = 0;
	    
	    foreach ($users as $user):
		$class = null;
		if ($i++ % 2 == 0) {
		    $class = ' class="altrow"';
		}
	?>

	<tr<?php echo $class; ?>>
	    <td><?php echo $user['User']['firstname']; ?>&nbsp;</td>
	    <td><?php echo $user['User']['lastname']; ?>&nbsp;</td>
	    <td><?php echo $this->Html->link($user['User']['email'], 'mailto:'.$user['User']['email']); ?>&nbsp;</td>
	    <td>
		<?php echo $user['Role']['name'];?>
		<?php echo (in_array($user['User']['id'], $perms)) ? '*' : '' ?>
	    </td>
	    <td class="actions">
		<?php echo $this->Html->link(__('Docs', true), array('controller' => 'filed_documents',  'action' => 'index', $user['User']['id']), array('class' => 'docs')); ?>
		<?php echo $this->Html->link(__('Activity', true), array('controller' => 'user_transactions',  'action' => 'index', $user['User']['id']), array('class' => 'activity')); ?>
		<?php echo $this->Html->link(__('Upload', true), array('controller' => 'filed_documents',  'action' => 'upload_document', $user['User']['id']), array('class' => 'docs')); ?>
		<?php echo $this->Html->link(__('Scan', true), array('controller' => 'filed_documents',  'action' => 'scan_document', $user['User']['id']), array('class' => 'docs')); ?>
		<?php if($user['User']['role_id'] > 3 ) { ?>
		    <?php echo $this->Html->link(__('Permissions', true), array('controller' => 'permissions', 'action' => 'index', $user['User']['id'], 'User'), array('class'=>'permissions')); ?>
		<?php } ?>
		<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit_admin', $user['User']['id'], 'admin' => true), array('class' => 'edit')); ?>
		<?php echo $this->Html->link(__('Delete', true),array('action' => 'delete_admin', $user['User']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete this admin?', true), $user['User']['id']), array('class' => 'delete')); ?>
	    </td>
	</tr>
	<?php endforeach; ?>
    </table>
    <p class="paging-counter">
	<?php
	echo $this->Paginator->counter(array(
	    'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	
    </p>
    <br />
    <div class="paging">
	<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
	 | 	<?php echo $this->Paginator->numbers(); ?>
	|
	<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>


    </div>
</div>
