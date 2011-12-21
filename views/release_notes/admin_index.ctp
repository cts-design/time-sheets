<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Release Notes', null, 'unique'); ?>
</div>
<div id="ReleaseNotes">
	<h1>Release 3.1.6 (12/22/11)</h1>
	<h2>Major Feature Enhancements</h2>
	<ol>
		<li>Implementation of ALERTS module. This module will allow ATLAS users to setup specific ALERTS to be generated based on preset conditions within ATLAS.</li>
		<li>Implementation of REPORTS module. Module allows for in-depth reporting and charting.	</li>	
	</ol>
	<h2>Minor Feature Enhancements</h2>
	<ol>
		<li>Implemented a “save state” feature that retains the selected Self-Sign Queue filter settings.</li>
		<li>Implemented a status bar and added counts to the Self-Sign Queue.</li>		
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Resolved issue with “generate” function not working for Role level admins.</li>
		<li>Increased "deleted reason" text box size.</li>
	</ol>
	<hr />
	<h1>Release 3.1.5 (11/16/11)</h1>
	<h2>Main Feature Releases</h2>
	<ol>
		<li>Development of customizable kiosk registration and edit screens.</li>
		<li>Added Open, Closed, Not Helped tabs to self-sign queue.</li>
		<li>Added ATLAS Module Preferences and Settings.</li>
		<li>Added SSHkey deployment strategy to production deployment model. (Internal to CTS)</li>
		<li>Finalized production layout files deployment strategy (Internal to CTS)</li>
	</ol>	
	<h2>Minor Feature Enhancements</h2>
	<ol>
		<li>Added alphanumeric sorting to admin list in Document Archive.</li>
		<li>Added ability for customer to edit ATLAS registration data on kiosk based on required registration fields set forth on kiosk registration customization.</li>
		<li>Added “touch trigger” to disable kiosk button after press/click.</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Resolved bug that was causing the “other” field to not populate during “reassign” and “new sign-in” actions within the self-sign queue.</li>
		<li>Fixed issue within the self-sign archive that was causing the “second” button selection to not default to “all buttons”</li>
		<li>Added “Go Back” button on the self-scan scan interface page.</li>
		<li>Adjusted Self-Sign button height to allow for Spanish translations to wrap.</li>
		<li>Resolved issue that was causing time-out feature within kiosk applications to not execute under certain conditions.</li>
		<li>Resolution to deleted document archive not displaying correct “last activity admin”.</li>
	</ol>
	<h2>Known Issues</h2>
	<ol>
		<li>Java-script issue causing scroll bar to occasionally not respond within the document archive. Ticket #280</li>
		<li>Erroneous flash message indicating “Not authorized to view this location” to certain Role Admins that have custom permissions. Ticket #284</li>
		<li>Java-script issue that is causing the open “Programs” window to hide the scroll bar in firefox.</li>
	</ol>
	<hr />
	<h1>Release 3.1.4 (10/27/11)</h1>
	<h2>Minor Feature Enhancements</h2>	
	<ol>
		<li>Added the “other” field to the program response docs view to allow staff working program responses to view the reason the document was rejected.</li>
		<li>Added additional logic within the profile edit function to check customer record for email address on none@email.com, no@email.com or "none". 
			This will prompt the edit profile function after a customer logs into any self service module.</li>
		<li>Added “edit profile” and “logout” capabilities to customers who are logged into the On-Line self service system.</li>	
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixed issue that was causing the “scan documents” self-scan interface to not be triggered when user is in a Spanish session.</li>
		<li>Resolved issue with roles that hid the Permissions tab from certain Admin level users.</li>
		<li>Resolved issue with role based admins not having access to Edit Instructions and Edit Emails section of the programs management console.</li>
		<li>Corrected a mislabel within the programs module. Programs with Confirmation numbers were being labled conformation instead of confirmation. Adjusted controller, table and view labels.</li>
		<li>Resolved issue that was causing the first level of the filing tree within the Self-Sign archive to display the first option rather that “All Buttons”.	</li>	
	</ol>
	<h2>Known Issues</h2>
	<ol>
		<li>Specific self-scan buttons within the self-scan module are not being translated to Spanish.</li>
	</ol>
	<hr />
	<h1>Release 3.1.3.1 (10/25/11)</h1>
	<h2>Main Feature Releases</h2>
	<ol>
		<li>Development of shared config strategy to utilize production server based config file.</li>
		<li>Development of profile edit capabilities. Requires customers that were originally registered at kiosk to update profile when accessing Self Service Programs.</li>
		<li>Fixed loophole that would allow customer to register at kiosk and the register for program, without updating profile.#2 - Specifically email address which is mandatory in the program section.</li>
		<li>Added "Edit Profile" link to navigation header.</li>
		<li>Absorbed legacy registration process from customers not using the programs module to track program registrations.</li>
		<li>Added functionality to allow documents that were orignally filed as a non watch document to be "edited" and moved to a filing category outside of the program response documents controller.</li>
		<li>Resolved deployment caching issue that would retain cache and make areas of the program response module funtion incorrectly.</li>
	</ol>
	<hr />
	<h1>Release 3.1.3 (10/19/11)</h1>
	<h2>Main Feature Releases</h2>
	<ol>
		<li>Upgraded all EXT3 views to EXT4. (Resolution to reported context and dropdown bug issues within IE 9).</li>
		<li>Development of bar-code definition interface.</li>
	</ol>
	<h2>Minor Feature Enhancements</h2>
	<ol>
		<li>Deprecated the CKEditor component.</li>
		<li>Implemented a fix for .XLSX headers not being shown correctly within the Survey Module.</li>
		<li>Implemented “reject feature” within the approval tab of the programs module.</li>
		<li>Implemented real-time sorting enhancement within the storage queue. Filter now requires multiple fields to begin live query.</li>
		<li>Redesign of the CMS editor to integrate with the new version of EXT4.</li>
		<li>Implemented feature request to define additional administrator permissions to role admins.</li>
		<li>Added “average wait time” to the self-sign archive .XLSX report.</li>
		<li>Added default dropdown selections and focus to search term within the customer search component.</li>
		<li>Added increased functionality for the “In The News” section of the website module.</li>
		<li>Added right click context menus to website navigation.</li>
		<li>Added right click context menus to filing tree navigation.</li>
		<li>Implemented an .XLSX activity report within the admin activity view.</li>
		<li>Modified the "programs" module email notifications to send status and confirmation emails using plain text format.</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Implemented PDFOBJECT version 1.2 to resolve compatibility issues between Adobe X and Internet Explorer 9.</li>
		<li>Migrated from EXT3 to EXT4 to resolve drop downs other context menus not rendering correctly within IE9.</li>
	</ol>
	<hr />	
	<h1>Release 3.1.2 (9/8/11)</h1>
	<h2>Main Feature Releases</h2>
	<ol>
		<li>Deployment of the ATLAS Self-Sign Survey Module.</li>
		<li>Release of Training Provider Application to specific ATLAS workforce regions.</li>
	</ol>
	<h2>Minor Feature Enhancements</h2>
	<ol>
		<li>Added notes display panel to program responses.</li>
		<li>Adjusted program response "docs" panel to display all filed, rejected and deleted documents. Ordered display to keep "filed" documents on top.</li>
		<li>Added functionality that will not allow an "expired" response to overwrite a newer "un-expired" response.</li>
		<li>Added functionality that only allows admins to "approve" an application after all "watched categories" requirements are met.</li>
		<li>Added ability to “edit document” directly from the program response Document panel. This will allow staff to review and “file” or “reject” documents much easier.</li>
		<li>Updated breadcrumb helper to allow for direct link back to Document panel after “edit” has been completed.</li>
		<li>Implemented a search feature within the program response module.</li>
		<li>Added ability to "decline" - labeled (not-approved) to the program response. Staff has ability to decline an application and note the reason why. Not-approved email is sent to customer with reason.</li>
		<li>Added "date modified" to the document archive and document archive XLS report</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Revisited "mark expired" action based on customers input. Determined the "mark expired" action must be available in a scenario where customer is automatically expired, then needs to be "unexpired". Determination was made that the mark expired would only add an addition feature and does not affect automatic expiration duration.</li>
		<li>Fixed bug that was causing edited documents not to trigger watched filing categories</li>
		<li>Verified functionality, ability to download CERT if expiration of application occurred between the application being approved and customer login in to download CERT.</li>
		<li>Verified functionality, if customer is expired, new application documents are filed correctly.</li>
		<li>Fixed bug which was causing issues with "expired" responses that were marked "un-expired".</li>
		<li>Verified functionality, the same document was showing up more than once, tested OK.</li>
		<li>Fixed bug that was causing an issue after documents were "restored" after being deleted from the queue or from filed docs.</li>
	</ol>
	<hr />
	<h1>Release 3.1.1 (8/18/11)</h1>
	<ol>
		<li>Implemented a dynamic text resizing tool for website CMS pages.</li>
		<li>Fixed bug that was causing the self-sign queue to not display within IE7.</li>
		<li>Implemented "Bug Report" option to admin roles only.</li>
		<li>Resolved issue with “view” button within the deleted documents area of Storage.</li>
		<li>Adjusted “required” fields within the “add customer” section of users to allow addition of customer without specification of Gender, Race and Ethnicity.</li>
		<li>Fixed bug which was causing the location house to display incorrectly within the CMS locations module.</li>
		<li>Implemented a deployment monitoring tool to track load times, memory usage, CPU usage, ping times and alert systems admin in the event of an issue.</li>
		<li>Resolved validation issue when using 2nd conditional search criteria within the Users - Customers search function.</li>
	</ol>
	<hr />	
	<h1>Release 3.1.0 (8/4/11)</h1>
	<ol>
		<li>Added bi-lingual (Spanish) selection to Website module. Utilizing browser based "regional" settings to determine language. Also, added manual link on public facing websites</li>
		<li>Added bi-lingual (Spanish) translation option to kiosk buttons.</li>
		<li>Added bilingual (Spanish) translation options to the admin sections of ATLAS.</li>
		<li>Re-development of the self-sign queue to allow for status grouping, column sorting, enhanced "status" definitions.</li>
		<li>Refactoring of self-sign archive to take in account new filtering fields from within the self-ign queue.</li>
		<li>Re-development of the standalone AIR application to mimic fetaures and look of browser based self sign queue application.</li>
		<li>Resolved bug causing customer documents to be un-editable when imported through a custom import tool</li>
		<li>Fixed bug where document was not viewable in the queue after using filing and requeue option.</li>
		<li>Fixed bug where random role permissions were not able to be edited.</li>
		<li>Fixed bug that was causing kiosk buttons page to load blank.</li>
		<li>Fixed bug where button dropdown menus were showing button ids instead of button names on the self sign archive page.</li>
	</ol>
	<hr />
	<h1>Release 3.0.3 (7/14/11)</h1>
	<ol>
		<li>Fixed issue that caused pagination to reset after search results returned more that 2 pages</li>
		<li>Fixed issue that caused search parameters to be blanked out after initial search</li>
		<li>Implemented a 5mb file size upload limit for PDF documents being sent into the "programs" module</li>
		<li>Made changes to email notification within the "programs" module, for users that are processing automated VPK applications with the ATLAS system</li>
		<li>Implemented a way to control what roles can or cannot view full ssn numbers in the customer list view. If you create or edit a role there is now a checkbox for (can view full ssn). When a admin belongs to a role that has that checkbox ticked they can hover over the obscured ssn in the customer list to view the full ssn.</li>
		<li>Fixed issue where third category dropdown menu was intermittently not being populated when editing a filed customer document.</li>
		<li>Added ability to search by full SSN or last 4 SSN in customer list view and filed document archive view.</li>
		<li>Fixed bug where disabled document queue categories were showing up in the dropdown menu for the requeue document form.</li>
		<li>Updated view document link in filed documents index view to open in a new tab or window.</li>
	</ol>	
	<hr />	
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