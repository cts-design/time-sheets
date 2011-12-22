<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

?>
<script type="text/javascript">
	Ext.onReady(function(){
		Ext.QuickTips.init();
		var PermissionTabs = Ext.create('Ext.TabPanel', {
			activeTab: 0,
			bodyStyle: 'padding: 10px',
			renderTo: 'PermissionTabs',
			items:[
				{contentEl: 'Users', title: 'Users'},
				{contentEl: 'Website', title: 'Website'},
				{contentEl: 'Storage', title: 'Storage'},
				{contentEl: 'SelfSign', title: 'Self Sign'},
				{contentEl: 'Programs', title: 'Programs'},
				{contentEl: 'Tools', title: 'Tools'},
				{contentEl: 'Reports', title: 'Reports'}
			]
		})
	});
</script>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Edit Permissions', true), null, 'unique'); ?>
</div>
<div id="PermissionTabs">
	<div id="Users" class="x-hide-display">
		    <?php echo $this->Form->create('permission', array('action' => 'set_permissions')) ?>
		    <?php echo $this->Form->hidden('id', array('value' => $id));?>
		    <?php echo $this->Form->hidden('model', array('value' => $model));?>		
		  <fieldset class="left right-mar-10">
                  <legend><?php __('Administration') ?></legend>
				 <?php echo $this->Form->input('Users.admin_dashboard', array(
				     'label' => 'Dashboard',
				     'type' => 'checkbox',
				    'checked' => (isset($controllers['Users']['admin_dashboard'])) ? $controllers['Users']['admin_dashboard'] : '' ));?>
			    </fieldset>
			    <fieldset class="left right-mar-10">
                <legend><?php __('Customers') ?></legend>
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
				<?php echo $this->Form->input('Users.admin_toggle_disabled', array(
				    'type' => 'checkbox',
				    'label' => 'Toggle Disabled',
				    'checked' => (isset($controllers['Users']['admin_toggle_disabled']) ? $controllers['Users']['admin_toggle_disabled'] : '' )));?>				    
			    </fieldset>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Administrators') ?></legend>
			<?php echo $this->Form->input('Users.admin_index_admin', array(
			    'type' => 'checkbox',
			    'label' => 'Index',
			    'checked' => (isset($controllers['Users']['admin_index_admin'])) ? $controllers['Users']['admin_index_admin'] : '' ));?>
			<?php echo $this->Form->input('Users.admin_add_admin', array(
			    'type' => 'checkbox',
			    'label' => 'Add',
			    'checked' => (isset($controllers['Users']['admin_add_admin'])) ? $controllers['Users']['admin_add_admin'] : ''));?>
			<?php echo $this->Form->input('Users.admin_edit_admin', array(
			    'type' => 'checkbox',
			    'label' => 'Edit',
			    'checked' => (isset($controllers['Users']['admin_edit_admin']) ? $controllers['Users']['admin_edit_admin'] : '' )));?>
			<?php echo $this->Form->input('Users.admin_toggle_disabled_admin', array(
			    'type' => 'checkbox',
			    'label' => 'Toggle Disabled',
			    'checked' => (isset($controllers['Users']['admin_toggle_disabled_admin']) ? $controllers['Users']['admin_toggle_disabled_admin'] : '' )));?>				    
		    </fieldset>			    
			    <?php if (!in_array('UserTransactions', $disabledModules)): ?>
			    <fieldset class="left right-mar-10">
                <legend><?php __('Activity') ?></legend>
			       <?php echo $this->Form->input('UserTransactions.all', array(
				    'type' => 'checkbox',
				   'label' => 'Index',
				    'checked' => (isset($controllers['UserTransactions']['all'])) ? $controllers['UserTransactions']['all'] : '' ));?>
			    </fieldset>
		    <?php endif; ?>
		    <br class="clear" />
			<?php echo $this->Form->end(__('Submit', true))?>    
	</div>
	<div id="Website" class="x-hide-display">
		    <?php echo $this->Form->create('permission', array('action' => 'set_permissions')) ?>
		    <?php echo $this->Form->hidden('id', array('value' => $id));?>
		    <?php echo $this->Form->hidden('model', array('value' => $model));?>			
		   <?php if (!in_array('Pages', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Website Pages') ?></legend>
		       <?php echo $this->Form->input('Pages.admin_index', array(
			    'type' => 'checkbox',
			   	'label' => 'Index',
			    'checked' => (isset($controllers['Pages']['admin_index'])) ? $controllers['Pages']['admin_index'] : '' ));?>
		       <?php echo $this->Form->input('Pages.admin_add', array(
			    'type' => 'checkbox',
			   	'label' => 'Add',
			    'checked' => (isset($controllers['Pages']['admin_add'])) ? $controllers['Pages']['admin_add'] : '' ));?>
		       <?php echo $this->Form->input('Pages.admin_edit', array(
			    'type' => 'checkbox',
			   	'label' => 'Edit',
			    'checked' => (isset($controllers['Pages']['admin_edit'])) ? $controllers['Pages']['admin_edit'] : '' ));?>
		       <?php echo $this->Form->input('Pages.admin_delete', array(
			    'type' => 'checkbox',
			   	'label' => 'Delete',
			    'checked' => (isset($controllers['Pages']['admin_delete'])) ? $controllers['Pages']['admin_delete'] : '' ));?>
		    </fieldset>
		    <?php endif; ?>
		    
		    <?php if (!in_array('Navigations', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Website Navigation') ?></legend>
		       <?php echo $this->Form->input('Navigations.all', array(
			    'type' => 'checkbox',
			   	'label' => 'Index',
			    'checked' => (isset($controllers['Navigations']['all'])) ? $controllers['Navigations']['all'] : '' ));?>
		    </fieldset>
		    <?php endif; ?>
		    
		    <?php if (!in_array('ChairmanReports', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Chairman Reports') ?></legend>
		       <?php echo $this->Form->input('ChairmanReports.admin_index', array(
			    'type' => 'checkbox',
			   	'label' => 'Index',
			    'checked' => (isset($controllers['ChairmanReports']['admin_index'])) ? $controllers['ChairmanReports']['admin_index'] : '' ));?>
		       <?php echo $this->Form->input('ChairmanReports.admin_add', array(
			    'type' => 'checkbox',
			   	'label' => 'Add',
			    'checked' => (isset($controllers['ChairmanReports']['admin_add'])) ? $controllers['ChairmanReports']['admin_add'] : '' ));?>
		       <?php echo $this->Form->input('ChairmanReports.admin_edit', array(
			    'type' => 'checkbox',
			   	'label' => 'Edit',
			    'checked' => (isset($controllers['ChairmanReports']['admin_edit'])) ? $controllers['ChairmanReports']['admin_edit'] : '' ));?>
		       <?php echo $this->Form->input('ChairmanReports.admin_delete', array(
			    'type' => 'checkbox',
			   	'label' => 'Delete',
			    'checked' => (isset($controllers['ChairmanReports']['admin_delete'])) ? $controllers['ChairmanReports']['admin_delete'] : '' ));?>
		    </fieldset>
		    <?php endif; ?>
		    
		    <?php if (!in_array('PressReleases', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Press Releases') ?></legend>
		       <?php echo $this->Form->input('PressReleases.admin_index', array(
			    'type' => 'checkbox',
			   	'label' => 'Index',
			    'checked' => (isset($controllers['PressReleases']['admin_index'])) ? $controllers['PressReleases']['admin_index'] : '' ));?>
		       <?php echo $this->Form->input('PressReleases.admin_add', array(
			    'type' => 'checkbox',
			   	'label' => 'Add',
			    'checked' => (isset($controllers['PressReleases']['admin_add'])) ? $controllers['PressReleases']['admin_add'] : '' ));?>
		       <?php echo $this->Form->input('PressReleases.admin_edit', array(
			    'type' => 'checkbox',
			   	'label' => 'Edit',
			    'checked' => (isset($controllers['PressReleases']['admin_edit'])) ? $controllers['PressReleases']['admin_edit'] : '' ));?>
		       <?php echo $this->Form->input('PressReleases.admin_delete', array(
			    'type' => 'checkbox',
			   	'label' => 'Delete',
			    'checked' => (isset($controllers['PressReleases']['admin_delete'])) ? $controllers['PressReleases']['admin_delete'] : '' ));?>
		    </fieldset>
		    <?php endif; ?>
		    
		    <?php if (!in_array('HotJobs', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Hot Jobs') ?></legend>
		       <?php echo $this->Form->input('HotJobs.admin_index', array(
			    'type' => 'checkbox',
			   	'label' => 'Index',
			    'checked' => (isset($controllers['HotJobs']['admin_index'])) ? $controllers['HotJobs']['admin_index'] : '' ));?>
		       <?php echo $this->Form->input('HotJobs.admin_add', array(
			    'type' => 'checkbox',
			   	'label' => 'Add',
			    'checked' => (isset($controllers['HotJobs']['admin_add'])) ? $controllers['HotJobs']['admin_add'] : '' ));?>
		       <?php echo $this->Form->input('HotJobs.admin_edit', array(
			    'type' => 'checkbox',
			   	'label' => 'Edit',
			    'checked' => (isset($controllers['HotJobs']['admin_edit'])) ? $controllers['HotJobs']['admin_edit'] : '' ));?>
		       <?php echo $this->Form->input('HotJobs.admin_delete', array(
			    'type' => 'checkbox',
			   	'label' => 'Delete',
			    'checked' => (isset($controllers['HotJobs']['admin_delete'])) ? $controllers['HotJobs']['admin_delete'] : '' ));?>
		    </fieldset>
		    <?php endif; ?>
		    
		    <?php if (!in_array('Rfps', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('RFPs & Bids') ?></legend>
		       <?php echo $this->Form->input('Rfps.admin_index', array(
			    'type' => 'checkbox',
			   	'label' => 'Index',
			    'checked' => (isset($controllers['Rfps']['admin_index'])) ? $controllers['Rfps']['admin_index'] : '' ));?>
		    </fieldset>
		    <?php endif; ?>
		    
		    <?php if (!in_array('FeaturedEmployers', $disabledModules)): ?>
			    <fieldset class="left right-mar-10">
                <legend><?php __('Featured Employers') ?></legend>
			       <?php echo $this->Form->input('FeaturedEmployers.admin_index', array(
				    'type' => 'checkbox',
				   	'label' => 'Index',
				    'checked' => (isset($controllers['FeaturedEmployers']['admin_index'])) ? $controllers['FeaturedEmployers']['admin_index'] : '' ));?>
			       <?php echo $this->Form->input('FeaturedEmployers.admin_add', array(
				    'type' => 'checkbox',
				   	'label' => 'Add',
				    'checked' => (isset($controllers['FeaturedEmployers']['admin_add'])) ? $controllers['FeaturedEmployers']['admin_add'] : '' ));?>
			       <?php echo $this->Form->input('FeaturedEmployers.admin_edit', array(
				    'type' => 'checkbox',
				   	'label' => 'Edit',
				    'checked' => (isset($controllers['FeaturedEmployers']['admin_edit'])) ? $controllers['FeaturedEmployers']['admin_edit'] : '' ));?>
			       <?php echo $this->Form->input('FeaturedEmployers.admin_delete', array(
				    'type' => 'checkbox',
				   	'label' => 'Delete',
				    'checked' => (isset($controllers['FeaturedEmployers']['admin_delete'])) ? $controllers['FeaturedEmployers']['admin_delete'] : '' ));?>
			    </fieldset>
		    <?php endif; ?>	
		    <br class="clear" />
			<?php echo $this->Form->end(__('Submit', true))?>    
	</div>
	<div id="Storage" class="x-hide-display">
		    <?php echo $this->Form->create('permission', array('action' => 'set_permissions')) ?>
		    <?php echo $this->Form->hidden('id', array('value' => $id));?>
		    <?php echo $this->Form->hidden('model', array('value' => $model));?>	
		    <?php if (!in_array('QueuedDocuments', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Queued Docs') ?></legend>
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
		    <?php endif; ?>
		    
		    <?php if (!in_array('FiledDocuments', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Filed Docs') ?></legend>
			<?php echo $this->Form->input('FiledDocuments.admin_index', array(
			    'type' => 'checkbox',
			   'label' => 'Index',
			    'checked' => (isset($controllers['FiledDocuments']['admin_index'])) ? $controllers['FiledDocuments']['admin_index'] : '' ));?>
			<?php echo $this->Form->input('FiledDocuments.admin_view', array(
			    'type' => 'checkbox',
			   'label' => 'View',
			    'checked' => (isset($controllers['FiledDocuments']['admin_view'])) ? $controllers['FiledDocuments']['admin_view'] : '' ));?>
			<?php echo $this->Form->input('FiledDocuments.admin_view_all_docs', array(
			    'type' => 'checkbox',
			   'label' => 'Archive',
			    'checked' => (isset($controllers['FiledDocuments']['admin_view_all_docs'])) ? $controllers['FiledDocuments']['admin_view_all_docs'] : '' ));?>	
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
		    <?php endif; ?>
		    
		    <?php if (!in_array('DeletedDocuments', $disabledModules)): ?>
		    <fieldset class="left right-mar-10">
            <legend><?php __('Deleted Docs') ?></legend>
		       <?php echo $this->Form->input('DeletedDocuments.all', array(
			    'type' => 'checkbox',
			   'label' => 'Index',
			    'checked' => (isset($controllers['DeletedDocuments']['all'])) ? $controllers['DeletedDocuments']['all'] : '' ));?>
		    </fieldset>
		    <?php endif; ?>
		    <br class="clear" />
			<?php echo $this->Form->end(__('Submit', true))?>    
	</div>
	<div id="SelfSign" class="x-hide-display">
		    <?php echo $this->Form->create('permission', array('action' => 'set_permissions')) ?>
		    <?php echo $this->Form->hidden('id', array('value' => $id));?>
		    <?php echo $this->Form->hidden('model', array('value' => $model));?>
		    <?php if (!in_array('SelfSignLogs', $disabledModules)): ?>
			    <fieldset class="left right-mar-10">
                <legend><?php __('Self Sign') ?></legend>
			       <?php echo $this->Form->input('SelfSignLogs.all', array(
				    'type' => 'checkbox',
				   	'label' => 'Index',
				    'checked' => (isset($controllers['SelfSignLogs']['all'])) ? $controllers['SelfSignLogs']['all'] : '' ));?>
			    </fieldset>
			    <?php endif; ?>
			    
			    <?php if (!in_array('SelfSignLogArchives', $disabledModules)): ?>
			    <fieldset class="left right-mar-10">
                <legend><?php __('Self Sign Archive') ?></legend>
			       <?php echo $this->Form->input('SelfSignLogArchives.all', array(
				    'type' => 'checkbox',
				   'label' => 'Index',
				    'checked' => (isset($controllers['SelfSignLogArchives']['all'])) ? $controllers['SelfSignLogArchives']['all'] : '' ));?>
			    </fieldset>
			<?php endif; ?>	

			    <?php if (!in_array('KioskSurveys', $disabledModules)): ?>
			    <fieldset class="left right-mar-10">
                <legend><?php __('Self Sign Surveys') ?></legend>
			       <?php echo $this->Form->input('KioskSurveys.all', array(
				    'type' => 'checkbox',
				   'label' => 'Index',
				    'checked' => (isset($controllers['KioskSurveys']['all'])) ? $controllers['KioskSurveys']['all'] : '' ));?>
			    </fieldset>
			<?php endif; ?>	
			<br class="clear" />
			<?php echo $this->Form->end(__('Submit', true))?>    
	</div>
	<div id="Programs" class="x-hide-display">
		    <?php echo $this->Form->create('permission', array('action' => 'set_permissions')) ?>
		    <?php echo $this->Form->hidden('id', array('value' => $id));?>
		    <?php echo $this->Form->hidden('model', array('value' => $model));?>		
		    <fieldset class="left right-mar-10">
                <legend><?php __('Programs') ?></legend>
			       <?php echo $this->Form->input('Programs.admin_index', array(
				    'type' => 'checkbox',
				   	'label' => 'Index',
				    'checked' => (isset($controllers['Programs']['admin_index'])) ? $controllers['Programs']['admin_index'] : '' ));?>
			       <?php echo $this->Form->input('ProgramInstructions.admin_index', array(
				    'type' => 'checkbox',
				   	'label' => 'Instructions Index',
				    'checked' => (isset($controllers['ProgramInstructions']['admin_index'])) ? $controllers['ProgramInstructions']['admin_index'] : '' ));?>				    
			       <?php echo $this->Form->input('ProgramInstructions.admin_edit', array(
				    'type' => 'checkbox',
				   	'label' => 'Edit Instructions',
				    'checked' => (isset($controllers['ProgramInstructions']['admin_edit'])) ? $controllers['ProgramInstructions']['admin_edit'] : '' ));?>
			       <?php echo $this->Form->input('ProgramEmails.admin_index', array(
				    'type' => 'checkbox',
				   	'label' => 'Emails Index',
				    'checked' => (isset($controllers['ProgramEmails']['admin_index'])) ? $controllers['ProgramEmails']['admin_index'] : '' ));?>
			       <?php echo $this->Form->input('ProgramEmails.admin_edit', array(
				    'type' => 'checkbox',
				   	'label' => 'Edit Emails',
				    'checked' => (isset($controllers['ProgramEmails']['admin_edit'])) ? $controllers['ProgramEmails']['admin_edit'] : '' ));?>			    
     	 		  <?php echo $this->Form->input('ProgramEmails.admin_toggle_disabled', array(
				    'type' => 'checkbox',
				   	'label' => 'Enable/Disable Emails',
				    'checked' => (isset($controllers['ProgramEmails']['admin_toggle_disabled'])) ? $controllers['ProgramEmails']['admin_toggle_disabled'] : '' ));?>			    
			    </fieldset>
		    <fieldset class="left right-mar-10">
                <legend><?php __('Program Responses') ?></legend>
			       <?php echo $this->Form->input('ProgramResponses.admin_index', array(
				    'type' => 'checkbox',
				   	'label' => 'Index',
				    'checked' => (isset($controllers['ProgramResponses']['admin_index'])) ? $controllers['ProgramResponses']['admin_index'] : '' ));?>
  				  <?php echo $this->Form->input('ProgramResponses.admin_view', array(
				    'type' => 'checkbox',
				   	'label' => 'View',
				    'checked' => (isset($controllers['ProgramResponses']['admin_view'])) ? $controllers['ProgramResponses']['admin_view'] : '' ));?>			       
			       <?php echo $this->Form->input('ProgramResponses.admin_approve', array(
				    'type' => 'checkbox',
				   	'label' => 'Approve / Not Approve',
				    'checked' => (isset($controllers['ProgramResponses']['admin_approve'])) ? $controllers['ProgramResponses']['admin_approve'] : '' ));?>
				   <?php echo $this->Form->input('ProgramResponses.admin_toggle_expired', array(
				    'type' => 'checkbox',
				   	'label' => 'Toggle Expired',
				    'checked' => (isset($controllers['ProgramResponses']['admin_toggle_expired'])) ? $controllers['ProgramResponses']['admin_toggle_expired'] : '' ));?>
				 				    
			    </fieldset>		    
		    <br class="clear" />
			<?php echo $this->Form->end(__('Submit', true))?>    
	</div>
	<div id="Tools" class="x-hide-display">
		    <?php echo $this->Form->create('permission', array('action' => 'set_permissions')) ?>
		    <?php echo $this->Form->hidden('id', array('value' => $id));?>
		    <?php echo $this->Form->hidden('model', array('value' => $model));?>		
		    <fieldset class="left right-mar-10">
            <legend><?php __('Tools') ?></legend>
		       <?php echo $this->Form->input('Users.admin_resolve_login_issues', array(
			    'type' => 'checkbox',
			   	'label' => 'Resolve Login Issues',
			    'checked' => (isset($controllers['Users']['admin_resolve_login_issues'])) ? $controllers['Users']['admin_resolve_login_issues'] : '' ));?>
		    </fieldset>
		    <br class="clear" />
			<?php echo $this->Form->end(__('Submit', true))?>    
	</div>
	<div id="Reports" class="x-hide-display">
		    <?php echo $this->Form->create('permission', array('action' => 'set_permissions')) ?>
		    <?php echo $this->Form->hidden('id', array('value' => $id));?>
		    <?php echo $this->Form->hidden('model', array('value' => $model));?>		
		    <fieldset class="left right-mar-10">
            <legend><?php __('Reports') ?></legend>
		       <?php echo $this->Form->input('Reports.admin_index', array(
			    'type' => 'checkbox',
			   	'label' => 'Index',
			    'checked' => (isset($controllers['Reports']['admin_index'])) ? $controllers['Reports']['admin_index'] : '' ));?>
 		       <?php echo $this->Form->input('Reports.admin_self_sign_logs', array(
 			    'type' => 'checkbox',
 			   	'label' => 'Self Sign Logs Report',
 			    'checked' => (isset($controllers['Reports']['admin_self_sign_logs'])) ? $controllers['Reports']['admin_self_sign_logs'] : '' ));?>
                <?php echo $this->Form->input('Reports.admin_self_sign_logs', array(
 			    'type' => 'checkbox',
 			   	'label' => 'Self Sign Logs Report',
 			    'checked' => (isset($controllers['Reports']['admin_self_sign_logs'])) ? $controllers['Reports']['admin_self_sign_logs'] : '' ));?>
                <?php echo $this->Form->input('Reports.admin_self_sign_logs', array(
 			    'type' => 'checkbox',
 			   	'label' => 'Self Sign Logs Report',
 			    'checked' => (isset($controllers['Reports']['admin_self_sign_logs'])) ? $controllers['Reports']['admin_self_sign_logs'] : '' ));?>
		    </fieldset>
		    <br class="clear" />
			<?php echo $this->Form->end(__('Submit', true))?>    
	</div>
</div>
<br class="clear" />
<?php if($model == 'User') : ?>
	<p>
		<?php echo $this->Html->link(__('Reset User Permissions', true), array(
			'action' => 'delete_permissions', 'admin' => true, $aroId, $id));?>
	</p>	
<?php endif ?>
    
