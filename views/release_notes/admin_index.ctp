<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Release Notes', null, 'unique'); ?>
</div>
<div id="ReleaseNotes">
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
	</ul>
</div>