<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<?php echo $this->Html->script('jquery.cookie', array('inline' => false)) ?>
<?php echo $this->Html->script('jquery.jstree', array('inline' => false)) ?>
<?php echo $this->Html->script('users/dashboard', array('inline' => false)) ?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Dashboard', 'reset', 'unique') ; ?>
</div>

<div id="adminDashboard" class="admin">
    <p><strong>Welcome to the administration dashboard.</strong>
    </p>

    <div id="administration" class="left">
	<fieldset>
	    <legend>Administration</legend>
	    <p class="expand-wrap"><?php echo $this->Html->link('Expand All', '', array('id' => 'expand')) ?></p>
	    <div id="dashboardAdminTree">
		<ul>
		    <?php if($this->Session->read('Auth.User.role_id') == 2 || $this->Session->read('Auth.User.role_id') == 3 )  { ?>
                        <li rel="website" id="website">
                            <a>Website</a>
                            <ul>
                                <li rel="pages"><?php echo $html->link('Pages', array('controller' => 'pages', 'action' => 'index')); ?></li>
                                <li rel="navigation"><?php echo $html->link('Navigation', array('controller' => 'navigations', 'action' => 'index')); ?></li>
                                <li rel="pressReleases"><?php echo $html->link('Press Releases', array('controller' => 'press_releases', 'action' => 'index')); ?></li>
                                <li rel="chairmanReports"><?php echo $html->link('Chairman Reports', array('controller' => 'chairman_reports', 'action' => 'index')); ?></li>
                            </ul>
                        </li>
			<li rel="settings" id="settings">
			    <a>Settings</a>
			    <ul>
				<li rel="settings_1"><?php echo $html->link('Locations', array('controller' => 'locations', 'action' => 'index')); ?></li>
				<li rel="settings_1"><?php echo $html->link('Roles', array('controller' => 'roles', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link('Self Sign Kiosk/Location Settings', array('controller' => 'kiosks', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link('Master Kiosk Buttons', array('controller' => 'master_kiosk_buttons', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link('Document Filing Categories', array('controller' => 'document_filing_categories', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link('FTP Document Scanners', array('controller' => 'ftp_document_scanners', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link('Document Queue Categories', array('controller' => 'document_queue_categories', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link('Self Scan Categories', array('controller' => 'self_scan_categories', 'action' => 'index')) ?></li>
			    </ul>
			</li>
		    <?php }?>
		    <li rel="group" id="users">
			<a>Users</a>
			<ul>
			    <li rel="group"><?php echo $html->link('Administrators', array('controller' => 'users', 'action' => 'index_admin')); ?></li>
			    <li rel="group"><?php echo $html->link('Customers', array('controller' => 'users', 'action' => 'index')) ?></li>
			</ul>
		    </li>
		    <li rel="user" id="selfSign">
			<a>Self Sign</a>
			<ul>
			    <li rel="queue"><?php echo $html->link('Self Sign Queue', array('controller' => 'self_sign_logs', 'action' => 'index')) ?></li>
			    <li rel="archive"><?php echo $html->link('Self Sign Archives', array('controller' => 'self_sign_log_archives', 'action' => 'index')) ?></li>
			</ul>
		    </li>
		    <li rel="storage" id="storage">
			<a>Storage</a>
			<ul>
			    <li rel="queue"><?php echo $html->link('Queued Documents', array('controller' => 'queued_documents', 'action' => 'index')) ?></li>
			    <li rel="scan"><?php echo $html->link('Desktop Scan', array('controller' => 'queued_documents', 'action' => 'desktop_scan_document')) ?></li>
			    <li rel=""><?php echo $html->link('My Filed Documents', array('controller' => 'filed_documents', 'action' => 'index')) ?></li>
			    <li rel="trash"><?php echo $html->link('Deleted Documents', array('controller' => 'deleted_documents', 'action' => 'index')) ?></li>
			</ul>
		    </li>
		    <li rel="tools" id="tools">
			<a>Tools</a>
			<ul>
			    <li rel="loginIssues"><?php echo $html->link('Resolve Login Issues', array('controller' => 'users', 'action' => 'resolve_login_issues')) ?></li>
			</ul>
		    </li>		    
		</ul>
	    </div>
	</fieldset>
    </div>
    <div id="information" class="left">
	<fieldset>
	    <legend>Information</legend>
	</fieldset>
    </div>
</div>

