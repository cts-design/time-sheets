<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<?php echo $this->Html->script(array('jquery.cookie', 'jquery.jstree', 'users/dashboard'), array('inline' => false)) ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Dashboard', true), 'reset', 'unique') ; ?>
</div>

<div id="adminDashboard" class="admin">
    <p><strong><?php __('Welcome to the administration dashboard.') ?></strong>
    </p>

    <div id="administration" class="left">
        <div>
	    <h3><?php echo $html->image('icons/user_suit.png')?> Administration</h3>
	    <p class="expand-wrap"><?php echo $this->Html->link(__('Expand All', true), '', array('id' => 'expand')) ?></p>
	    <div id="dashboardAdminTree" style="background-color: transparent">
		<ul>
		    <?php if($this->Session->read('Auth.User.role_id') == 2 || $this->Session->read('Auth.User.role_id') == 3 )  { ?>
                        <li rel="website" id="website">
                            <a><?php __('Website') ?></a>
                            <ul>
                                <li rel="pages"><?php echo $html->link(__('Pages', true), array('controller' => 'pages', 'action' => 'index')); ?></li>
                                <li rel="navigation"><?php echo $html->link(__('Navigation', true), array('controller' => 'navigations', 'action' => 'index')); ?></li>
                            	<li rel="inTheNews" id="news">
                                    <a><?php __('News') ?></a>
                            		<ul>
										<li rel="pressReleases"><?php echo $html->link(__('Press Releases', true), array('controller' => 'press_releases', 'action' => 'index')); ?></li>
                                		<li rel="chairmanReports"><?php echo $html->link(__('Chairman Reports', true), array('controller' => 'chairman_reports', 'action' => 'index')); ?></li>                            			
                            			<li rel="inTheNews"><?php echo $html->link(__('In the News', true), array('controller' => 'in_the_news', 'action' => 'index')) ?></li>
                            			<li rel="inTheNews"><?php echo $html->link(__('Helpful Articles', true), array('controller' => 'helpful_articles', 'action' => 'index')) ?></li>
                            		</ul>
                            	</li>
                            	<li rel="surveys" id="surveys">
                                    <a><?php __('Surveys') ?></a>
                            		<ul>
										<li rel="careerSeekersSurveys"><?php echo $html->link(__('Career Seekers Surveys', true), array('controller' => 'career_seekers_surveys', 'action' => 'index')); ?></li>
                                		<li rel="employersSurveys"><?php echo $html->link(__('Employer Surveys', true), array('controller' => 'employers_surveys', 'action' => 'index')); ?></li>                            			
                            		</ul>
                            	</li>
                                <li rel="calendar"><?php echo $html->link(__('Calendar of Events', true), array('controller' => 'events', 'action' => 'index')); ?></li>
                            	<li rel="hotJobs"><?php echo $html->link(__('Hot Jobs', true), array('controller' => 'hot_jobs', 'action' => 'index')); ?></li>
                            	<li rel="rfp"><?php echo $html->link(__('RFPs & Bids', true), array('controller' => 'rfps', 'action' => 'index')); ?></li>
                            	<li rel="featured"><?php echo $html->link(__('Featured Employer', true), array('controller' => 'featured_employers', 'action' => 'index')); ?></li>
								<li rel="jobOrderForms"><?php echo $html->link(__('Job Order Forms', true), array('controller' => 'job_order_forms', 'action' => 'index')); ?></li>
                            </ul>
                        </li>
			<li rel="settings" id="settings">
                <a><?php __('Settings') ?></a>
			    <ul>
				<li rel="settings_1"><?php echo $html->link(__('Atlas Module Preferences & Settings', true), array('controller' => 'settings', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link(__('Bar Code Definitions', true), array('controller' => 'bar_code_definitions', 'action' => 'index')) ?></li>			    	
			    <li rel="settings_1"><?php echo $html->link(__('Document Filing Categories', true), array('controller' => 'document_filing_categories', 'action' => 'index')) ?></li>	
				<li rel="settings_1"><?php echo $html->link(__('Document Queue Categories', true), array('controller' => 'document_queue_categories', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link(__('FTP Document Scanners', true), array('controller' => 'ftp_document_scanners', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link(__('Locations', true), array('controller' => 'locations', 'action' => 'index')); ?></li>
				<li rel="settings_1"><?php echo $html->link(__('Master Kiosk Buttons', true), array('controller' => 'master_kiosk_buttons', 'action' => 'index')) ?></li>
                <?php if ($this->Session->read('Auth.User.role_id') == 2): ?>
				<li rel="settings_1"><?php echo $html->link(__('Module Access Control', true), array('controller' => 'module_access_controls', 'action' => 'index')) ?></li>
                <?php endif; ?>				
				<li rel="settings_1"><?php echo $html->link(__('Roles', true), array('controller' => 'roles', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link(__('Self Scan Categories', true), array('controller' => 'self_scan_categories', 'action' => 'index')) ?></li>
				<li rel="settings_1"><?php echo $html->link(__('Self Sign Kiosk/Location Settings', true), array('controller' => 'kiosks', 'action' => 'index')) ?></li>			
                </ul>
			</li>
		    <?php }?>
		    <li rel="alerts" id="alerts">
		    	<?php echo $html->link(__('Alerts', true), array('controller' => 'alerts', 'action' => 'index', 'admin' => true)); ?>
		    </li>	
		    <li rel="group" id="users">
            <a><?php __('Users') ?></a>
			<ul>
			    <li rel="group"><?php echo $html->link(__('Administrators', true), array('controller' => 'users', 'action' => 'index_admin')); ?></li>
			    <li rel="group"><?php echo $html->link(__('Customers', true), array('controller' => 'users', 'action' => 'index')) ?></li>
			</ul>
		    </li>
		    <li rel="user" id="selfSign">
            <a><?php __('Self Sign') ?></a>
			<ul>
			    <li rel="queue"><?php echo $html->link(__('Self Sign Queue', true), array('controller' => 'self_sign_logs', 'action' => 'index')) ?></li>
			    <li rel="archive"><?php echo $html->link(__('Self Sign Archives', true), array('controller' => 'self_sign_log_archives', 'action' => 'index')) ?></li>
			    <li rel="selfSignSurvey"><?php echo $html->link(__('Self Sign Surveys', true), array('controller' => 'kiosk_surveys', 'action' => 'index')) ?></li>
			</ul>
		    </li>
		    <li rel="storage" id="storage">
            <a><?php __('Storage') ?></a>
			<ul>
			    <li rel="queue"><?php echo $html->link(__('Queued Documents', true), array('controller' => 'queued_documents', 'action' => 'index')) ?></li>
			    <li rel="scan"><?php echo $html->link(__('Desktop Scan', true), array('controller' => 'queued_documents', 'action' => 'desktop_scan_document')) ?></li>
			    <li rel=""><?php echo $html->link(__('My Filed Documents', true), array('controller' => 'filed_documents', 'action' => 'index')) ?></li>
			    <li rel="archive"><?php echo $html->link(__('Filed Document Archive', true), array('controller' => 'filed_documents', 'action' => 'view_all_docs')) ?></li>
			    <li rel="trash"><?php echo $html->link(__('Deleted Documents', true), array('controller' => 'deleted_documents', 'action' => 'index')) ?></li>
			</ul>
		    </li>
		    <li rel="programs" id="programs">
		    	<?php echo $html->link(__('Programs', true), array('controller' => 'programs', 'action' => 'index')) ?>
		    </li>
		    <li rel="tools" id="tools">
            <a><?php __('Tools') ?></a>
			<ul>
			    <li rel="loginIssues"><?php echo $html->link(__('Resolve Login Issues', true), array('controller' => 'users', 'action' => 'resolve_login_issues')) ?></li>
                <li rel="reports"><?php echo $html->link(__('Reports', true), array('controller' => 'reports', 'action' => 'index')) ?></li>
			</ul>
		    </li>		    
		</ul>
	    </div>
    </div>
    </div>
    <div id="information" class="left">
	    <div id='help'>
            <h3><?php echo $html->image('icons/help.png')?> <?php __('Help') ?></h3>
            <?php if($this->Session->read('Auth.User.role_id') <= 3) : ?>
		    	<p>
		    		<?php echo $html->image('icons/email.png')?> 
		    		<a href="mailto:CTSATLAS@support.assembla.com
		    			?subject=This subject line will be the title of your ticket
		    			&body=Please be as descriptive as possible.
		    			%0AAttachments included in this email will be included in the ticket.
		    			%0AInclude a screenshot of the error as an attachment if possible.
	                    %0AReply to a ticket alert from Assembla and your email will be posted as a ticket comment."><?php __('Create a support ticket via email') ?></a> 
		    	</p>
		    	<p><?php echo $html->image('icons/telephone.png')?> 352-666-0333</p>
	    	<?php else : ?>
	    		<p><?php echo $html->image('icons/bug.png')?> Please report issues with ATLAS to your supervisor.</p>
	    	<?php endif ?> 
	    	<p>
	    		<?php echo $html->image('icons/application_xp_terminal.png')?>
	    		<?php echo $html->link('Atlas 3.2.1', array('controller' => 'release_notes', 'admin' => true))?>
	    	</p>    	
	    </div>
    </div>
    <div class="clear"></div>
</div>

