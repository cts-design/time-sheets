<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Release Notes', null, 'unique'); ?>
</div>
<div id="ReleaseNotes">
	<h1>Release 3.0.2 (7/7/11)</h1>
	<ol>
		<li>Update to Module Access Control(mac) – Internal to CTS and Development Team</li>
		<li>Fixed bug that was causing document archive searches or “admins” to return improper results. Search now returns “admins” that have the “index and view” permissions assigned within the role system</li> 
		<li>Added ability to “disable” Admin and customers. Administrators can now toggle admin and customer view to show or hide the current disabled users</li> 
		<li>Added increased searching capabilities within the admin and customer search views. Allows for multiple search parameters to be passed from within the search view</li> 
		<li>Fixed bug with Admin roles higher than “3” getting an “unauthorized” message when attempting to create an Excel report from within the document archive</li> 
		<li>Added "other" - Description to filed document archive view</li> 
		<li>Added ability to create customized kiosk "modal" windows from specific buttons within the self sign kiosk button definitions</li>
		<li>Removed references to “Social Security Number already exists” error messages from self service and kiosk screens. Message has been replaced with “unable to register at this time, please see a customer service representative for assistance”</li>
		<li>Fixed issue with customers reporting orphaned customer documents being submitted into the document archive</li>		
	</ol>	
	<hr />
	<h1>Release 3.0.1 (6/23/11)</h1>
	<ol>
		<li>Resolved issue with ability to sort within the “Resolve Login Issues” area of Tools</li>
		<li>Resolved issue with Navigation tree resetting. Now keeps the most current view settings</li> 
		<li>Module Access Control enhancements for easy on/off module activation</li> 
		<li>Redesign of roles pages to group security options by module</li> 
		<li>Email notification to be sent when password is reset for admin.</li> 
		<li>Fixed issue with deleting FTP scanner</li> 
		<li>Fixed issue with “deleted” queue categories showing up in active filing trees</li> 
		<li>Fixed issue with user being deleted or marked inactive and another user with the same last name tries to login they get a account deleted or inactive message</li> 
		<li>Created routing structure for self-service module /kiosk as compared to /kiosks -to use router::url(), and refactoring views to use $html->url()</li> 
		<li>Adjustment to Cake Bake templates for page views</li> 
		<li>Added core Module “Programs” to code base</li> 
		<li>Integrated CMS Module “Website” to code base</li> 
		<li>Fixed issue with SSL twain - scan and upload being blackholed by CAKE form security component</li> 
		<li>Implemented "auth" areas of the cms system</li> 
		<li>Added link to release notes page</li> 
		<li>Fixed bug with storage upload moduld validation</li> 
		<li>Multiple changes to kiosk logos on production servers</li> 
		<li>Re-wrote aros table and login structure to exclude non-admins from role assignments. increasing speed of user views.</li> 
		<li>Added ability to reference outside url's from the cms navigation structure.</li> 
		<li>Fixed Excel reports not working in Internet Explorer, Excel reports now work in Excel 2003 - 2010.</li> 
	</ol>
</div>