<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Edit Permissions', null, 'unique'); ?>
</div>
<div id="permissions">
    <?php if(isset($controllers)) ?>
    <?php echo $this->Form->create('permission', array('action' => 'set_permissions')) ?>
    <?php echo $this->Form->hidden('id', array('value' => $id));?>
    <?php echo $this->Form->hidden('model', array('value' => $model));?>

    <fieldset class="left right-mar-10">
	<legend>Administration</legend>
	 <?php echo $this->Form->input('Users.admin_dashboard', array(
	     'label' => 'Dashboard',
	     'type' => 'checkbox',
	    'checked' => (isset($controllers['Users']['admin_dashboard'])) ? $controllers['Users']['admin_dashboard'] : '' ));?>
    </fieldset>
    <fieldset class="left right-mar-10">
	<legend>Customers</legend>
	<?php echo $this->Form->input('Users.admin_index', array(
	    'type' => 'checkbox',
	    'label' => 'Index',
	    'checked' => (isset($controllers['Users']['admin_index'])) ? $controllers['Users']['admin_index'] : '' ));?>
	<?php echo $this->Form->input('Users.admin_add', array(
	    'type' => 'checkbox',
	    'label' => 'Add',
	    'checked' => (isset($controllers['Users']['admin_add'])) ? $controllers['Users']['admin_add'] : ''));?>
	<?php echo $this->Form->input('Users.admin_edit', array(
	    'type' => 'checkbox',
	    'label' => 'Edit',
	    'checked' => (isset($controllers['Users']['admin_edit']) ? $controllers['Users']['admin_edit'] : '' )));?>
	<?php echo $this->Form->input('Users.admin_delete', array(
	    'type' => 'checkbox',
	    'label' => 'Delete',
	    'checked' => (isset($controllers['Users']['admin_delete'])) ? $controllers['Users']['admin_delete'] : ''));?>
    </fieldset>
    <fieldset class="left right-mar-10">
	<legend>Self Sign</legend>
       <?php echo $this->Form->input('SelfSignLogs.all', array(
	    'type' => 'checkbox',
	   'label' => 'Index',
	    'checked' => (isset($controllers['SelfSignLogs']['All'])) ? $controllers['SelfSignLogs']['All'] : '' ));?>
    </fieldset>
    <fieldset class="left right-mar-10">
	<legend>Self Sign Archive</legend>
       <?php echo $this->Form->input('SelfSignLogArchives.all', array(
	    'type' => 'checkbox',
	   'label' => 'Index',
	    'checked' => (isset($controllers['SelfSignLogArchives']['All'])) ? $controllers['SelfSignLogArchives']['All'] : '' ));?>
    </fieldset>
    <fieldset class="left right-mar-10">
	<legend>Queued Docs</legend>
       <?php echo $this->Form->input('QueuedDocuments.admin_index', array(
	    'type' => 'checkbox',
	   'label' => 'Index',
	    'checked' => (isset($controllers['QueuedDocuments']['admin_index'])) ? $controllers['QueuedDocuments']['admin_index'] : '' ));?>
	 <?php echo $this->Form->input('QueuedDocuments.admin_reassign_queue', array(
	    'type' => 'checkbox',
	   'label' => 'Reassign Queue',
	    'checked' => (isset($controllers['QueuedDocuments']['admin_reassign_queue'])) ? $controllers['QueuedDocuments']['admin_reassign_queue'] : '' ));?>
       <?php echo $this->Form->input('QueuedDocuments.admin_desktop_scan_document', array(
	    'type' => 'checkbox',
	   'label' => 'Scan',
	    'checked' => (isset($controllers['QueuedDocuments']['admin_desktop_scan_document'])) ? $controllers['QueuedDocuments']['admin_desktop_scan_document'] : '' ));?>
	 <?php echo $this->Form->input('QueuedDocuments.admin_file_document', array(
	    'type' => 'checkbox',
	   'label' => 'File Docs',
	    'checked' => (isset($controllers['QueuedDocuments']['admin_file_document'])) ? $controllers['QueuedDocuments']['admin_file_document'] : '' ));?>
       <?php echo $this->Form->input('QueuedDocuments.admin_delete', array(
	    'type' => 'checkbox',
	   'label' => 'Delete Docs',
	    'checked' => (isset($controllers['QueuedDocuments']['admin_delete'])) ? $controllers['QueuedDocuments']['admin_delete'] : '' ));?>
    </fieldset>
    <fieldset class="left right-mar-10">
	<legend>Filed Docs</legend>
	<?php echo $this->Form->input('FiledDocuments.admin_index', array(
	    'type' => 'checkbox',
	   'label' => 'Index',
	    'checked' => (isset($controllers['FiledDocuments']['admin_index'])) ? $controllers['FiledDocuments']['admin_index'] : '' ));?>
	<?php echo $this->Form->input('FiledDocuments.admin_view', array(
	    'type' => 'checkbox',
	   'label' => 'View',
	    'checked' => (isset($controllers['FiledDocuments']['admin_view'])) ? $controllers['FiledDocuments']['admin_view'] : '' ));?>
	<?php echo $this->Form->input('FiledDocuments.admin_upload_document', array(
	    'type' => 'checkbox',
	   'label' => 'Upload',
	    'checked' => (isset($controllers['FiledDocuments']['admin_upload_document'])) ? $controllers['FiledDocuments']['admin_upload_document'] : '' ));?>
	<?php echo $this->Form->input('FiledDocuments.admin_scan_document', array(
	    'type' => 'checkbox',
	   'label' => 'Scan',
	    'checked' => (isset($controllers['FiledDocuments']['admin_scan_document'])) ? $controllers['FiledDocuments']['admin_scan_document'] : '' ));?>
	<?php echo $this->Form->input('FiledDocuments.admin_edit', array(
	    'type' => 'checkbox',
	   'label' => 'Edit',
	    'checked' => (isset($controllers['FiledDocuments']['admin_edit'])) ? $controllers['FiledDocuments']['admin_edit'] : '' ));?>
	<?php echo $this->Form->input('FiledDocuments.admin_delete', array(
	    'type' => 'checkbox',
	   'label' => 'Delete',
	    'checked' => (isset($controllers['FiledDocuments']['admin_delete'])) ? $controllers['FiledDocuments']['admin_delete'] : '' ));?>
    </fieldset>
    <fieldset class="left right-mar-10">
	<legend>Deleted Docs</legend>
       <?php echo $this->Form->input('DeletedDocuments.all', array(
	    'type' => 'checkbox',
	   'label' => 'Index',
	    'checked' => (isset($controllers['DeletedDocuments']['All'])) ? $controllers['DeletedDocuments']['All'] : '' ));?>
    </fieldset>
    <fieldset class="left right-mar-10">
	<legend>Activity</legend>
       <?php echo $this->Form->input('UserTransactions.all', array(
	    'type' => 'checkbox',
	   'label' => 'Index',
	    'checked' => (isset($controllers['UserTransactions']['All'])) ? $controllers['UserTransactions']['All'] : '' ));?>
    </fieldset>
    <fieldset class="left right-mar-10">
	<legend>Tools</legend>
       <?php echo $this->Form->input('Users.admin_resolve_login_issues', array(
	    'type' => 'checkbox',
	   	'label' => 'Resolve Login Issues',
	    'checked' => (isset($controllers['Users']['admin_resolve_login_issues'])) ? $controllers['Users']['admin_resolve_login_issues'] : '' ));?>
    </fieldset>    
    <br class="clear" />
    <?php echo $this->Form->end('Submit')?>

    <br />
    <?php if($model == 'User') {
	echo $this->Html->link('Reset User Permissions', array('action' => 'delete_permissions', 'admin' => true, $aroId, $id));
    }

    ?>
</div>