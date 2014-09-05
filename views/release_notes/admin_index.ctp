<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Release Notes', null, 'unique'); ?>
</div>
<div id="ReleaseNotes">
	<h1>Release 3.8.7 (August 25th 2014)</h1>
	<h2>Features</h2>
	<ol>
		<li>
			Orientations that are being accessed from the kiosk can now
			scan their documents to ATLAS
		</li>
		<li>
			Admins can now choose to recieve a carbon copy of all the outgoing ATLAS System emails
		</li>
		<li>
			Kiosks Buttons can now be assigned actions and logout messages
		</li>
		<li>
			Ecourse Media now supports embeded Youtube videos.
		<li>
		<li>
			Systems can now be configured to allow customers to login using their username and password instead of their lastname and social security number
		</li>
		<li>
			Users that click the, "Attend this Event", button, while not logged in, will automatically be registered for the event on login.
		</li>
		<li>
			Users are presented with a small modal on successful registration for an event.
		</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>
			Events now save properly no matter which checkboxes are selected
		</li>
		<li>
			Filed Documents dropdown no longer shows an error message inside the dropdown menu.
		</li>
		<li>
			The public /events page now has paginates correctly.
		</li>
	</ol>

	<h1>Release 3.8.6 (July 27th 2014)</h1>
	<h2>Features</h2>
	<ol>
		<li>
			Kiosk Buttons can now link to other pages
		</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>
			Master Kiosk Buttons Interface Improvement
		</li>
		<li>
			Kiosk Buttons Interface Improvement
		</li>
		<li>
			Activity Reports now get created based on the search options
		</li>
	</ol>
	<h1>Release 3.8.5 (4-2-2014)</h1>
	<h2>Feature Releases</h2>
	<ol>
		<li>
			Added certificate number to Excel Report
		</li>
		<li>
			Added ID card number to customer profile
		</li>
		<li>
			Added functionality to "activate" an event
		</li>
	</ol>

	<h2>Minor Bug Fixes</h2>
	<ol>
		<li>
			Fixed issue with Alerts permission that was prohibiting admins from creating alerts for program responses for staff
		</li>
		<li>
			Fixed issue that was causing intermittent loss of Step logic tracking, Causing user to not be able to complete program applications with more that 3 steps wihtina module
		</li>
	</ol>

	<h1>Release 3.8.4 (3-20-14)</h1>
	<h2>Feature Releases</h2>
	<ol>
		<li>
			Added additional upload document feedback modal
		</li>
		<li>
			Added additional upload feedback on fail/document size
		</li>
		<li>
			Developed config variable for the Age Check feature to pull url from config file
		</li>
	</ol>

	<h2>
		Minor Bug Fixes
	</h2>

	<ol>
		<li>
			Fixed issue with inconsistent Login auth when using the Auditors module
		</li>
		<li>
			Fixed issue with improper layout being applied to program response forms
		</li>
		<li>
			Adjusted the order of the regenerated pdf's to always display the most recently generated certificate within the customers dashboard
		</li>
		<li>
			Resolved issue that was causing a "invalid username or password" flash on certain logins
		</li>
	</ol>

	<hr />

	<h1>Release 3.8.3 (2-19-14)</h1>
	<h2>
		Minor Bug Fixes
	</h2>
	<ol>
		<li>
			Fixed child login button while google translation is active
		</li>
		<li>
			
		</li>
	</ol>

	<hr />

	<h1>Release 3.8.2 (2-8-14)</h1>
	<h2>
		Feature Releases
	</h2>
	<ol>
		<li>
			The ability to regenerate queued/filed documents in the system
		</li>
		<li>
			Ability to delete comments that are not approved
		</li>
	</ol>
	<h2>Minor Bug Fixes</h2>
	<ol>
		<li>
			Fixed login button problems
		</li>
		<li>
			Fixed issue where some users could not log in on the new login system
		</li>
		<li>
			Fixed "blue screen" issue
		</li>
		<li>
			Fixed Allow Registration and ability to set the events to private
		</li>
		<li>
			Added new options to user registration under race and ethnicity
		</li>
		<li>
			Set events to be viewable from 30 days ago instead of current day
		</li>
		<li>
			Fixed some program bugs
		</li>
		<li>
			Stopped allowing users to circumvent the document upload on enrollments
		</li>
	</ol>

	<hr />

	<h1>Release 3.8 (1-3-14)</h1>
	<p>
		Release notes coming soon.
	</p>

	<hr />

	<h1>Release 3.7.7 (8-16-13)</h1>
	<h2>Minor updates</h2>
	<ol>
		 <li>
		 	Added more permission options for event registration
		 </li>
		 <li>Added Event Registration List Page</li>
		 <li>Added more options to kiosks for admins</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixed issue where kiosk would not register existing users with id cards</li>
	</ol>

	<hr />

	<h1>Release 3.7.6 (8-16-13)</h1>
	<h2>Minor updates</h2>
	<ol>
		 <li>Small release and bug fixes throughout ATLAS</li>
	</ol>
	<h1>Release 3.7.5 (6-20-13)</h1>
	<h2>Minor updates</h2>
	<ol>
		 <li>Added Electronic signature status to customer dashboard</li>
		 <li>Added the ability to use SWF files to Programs and Ecourses</li>
		 <li>Added Social media component support to various customized ATLAS layouts</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixed issue that was causing program response excel reports to generate data out of order within the excel file</li>
		<li>Fixed issue that was causing undesired results within the events web-services module</li>
		<li>Fixed a bug that was causing the Event scheduled date to not display properly</li>
	</ol>
	<hr />

	<h1>Release 3.7.4 (5-09-13)</h1>
	<h2>Minor updates</h2>
	<ol>
		 <li>Added the ability to customize the media acknowledgment statement within the Programs Module</li>
		 <li>Added the ability the Re-Order the E-course structure</li>
		 <li>After the snapshot process of a customer or staff system generated document, the program name is added to the “notes” field within the storage archive</li>
		 <li>Changed all SSN fields to “completely” hidden. Previously customers could see the last number typed for up to 1 second.</li>
		 <li>Added the ability to adjust the kiosk timeout and screen refresh down to 1 second intervals</li>
		 <li>Added the ability to update the events roster after the event has passed</li>
		 <li>Added the ALERT “Self Scan Category” to allow for certain types of Self-Scanned documents to be alerted on</li>
		 <li>Added the ALERT “Program Response Status” to allow for changes in program status to be alerted on</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixed a recursion issue that was causing the server PHP memory to become maxed when a lookup was performed on a location that had large amounts of data associated with it.</li>
		<li>Fixed issue that was causing all the alerts to be hidden </li>
	</ol>
	<hr />

	<h1>Release 3.7.3.1 (4-26-13)</h1>
	<h2>Minor updates</h2>
	<ol>
		<li>Implement a password mask on initial registration SSN fields</li>
		<li>Changed the default sort by date within the EVENTS view to Ascending</li>	
		<li>Added a landing page to ECOURSES</li>
	</ol>
	<hr />
	<h1>Release 3.7.3 (4-25-13)</h1>
	<h2>Minor Updates</h2>
	<ol>
		<li>Minor Feature Enhancements</li>
		<li>Added “Staff Filed” document alert</li>
		<li>Added “Self-Scan” alert</li>
		<li>Implemented the ability to edit ALERTS</li>
		<li>Added ability to view media from admin ecourse create screen</li>
		<li>Added an “input type” filter within the document queue to allow for documents to be sorted based on the input path into ATLAS</li>
		<li>Adjusted layout on E-Course media page</li>
		<li>Changed the language within the DASHBOARD step logic to indicate past tense when a E-course,orientation,event or program was “COMPLETED”</li>
		<li>Added the ability to add customized content (normal and child) to the user login view</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Resolved intermittent issue with Orientation certificate not generating</li>
		<li>Resolved issue with SCRIBD plugin not translating powerpoint presentations within the E-course canvas</li>
		<li>Fixed issue with permissions within the EVENTS module that was preventing Role Admins from adding customers to the event</li>
		<li>Resolved inconsistency within the core atlas.config file that was stopping the EVENT roster from loading the htmltopdf helper when creating the printable pdf</li>
		<li>Isolated issue with Ecourse “view responses” not loading because of an apostrophe within the E-course name</li>
		<li>Resolved issue that was causing the “Queue Cats” to not populate within the “add self-scan cat” view</li>
		<li>Resolved issue that could potentially allow customer to click an in-active step within the program or ecourse step system.</li>
		<li>Resolved bug that was causing the Email trigger to not fire when a customer was added to an EVENT</li>
		<li>Resolved issue that was causing “filter by event” AJAX call to halt within the EVENTS module</li>
		<li>Resolved issue that was causing multiple "certificates" from being created during the "runworker" helper</li>
		<li>Resolved issue with media_location database field character limit</li>
	</ol>
	<hr />
	<h1>Release 3.7.2 (4-11-13)</h1>
	<h2>Minor Updates</h2>
	<ol>
		<li>Added  Demographics from Customer Registration to "top" of Program Response PDF snapshot</li>
		<li>Added all program response fields to excel report for greater report capabilities</li>
		<li>Implemented the ability to filter the Queue by Self-scan documents using the Self-Scan definition Selection tree criteria</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixed spelling error on kiosk registration field</li>
		<li>Resolved bug with registration and “disability” question nor saving to user profile</li>
		<li>Fixed issues that was prohibiting Role Admin the ability to be assigned permissions under E-Course</li>
		<li>Fixed issue that was causing deleted Queues to show up within the Storage Queue as active</li>
		<li>Resolved issue with E-Course grade results being listed Alpha-Numeric rather than in Step ID order</li>
		<li>Resolved issue that was causing assigned ecourses that were completed to show up on the last assigned person of the course</li>
		<li>Fixed issue where confirmation yes|no on a delete of registered user in events was producing undesired results</li>
	</ol>
	<hr />
	<h1>Release 3.7.1 (4-5-13)</h1>
	<h2>Minor Updates</h2>
	<ol>
		<li>Added support for scribd streaming of powerpoint file within the E-course module</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixed bug that was causing printable roster from not displaying within the Events Attendance view</li>
		<li>Fixed bug that was causing an error with the "expand all" action on the ATLAS Dashboard</li>
	</ol>
	<hr />
	<h1>Release 3.7 (3-29-13)</h1>
	<h2>Major Updates</h2>
	<ol>
		<li>Release of the ATLAS integrated E-Course Module. This module will allow delivery of Customer and Staff E-course Material.</li>
	</ol>
	<h2>Minor Updates</h2>
	<ol>
		<li>Added ability to customize an event to allow/disallow self-reservation from the web-services module.</li>
		<li>Added additional event duration options - .25 increment</li>
		<li>Added "printable" sign-in sheet for events</li>
		<li>Added ability for staff to "register" customers from admin area.</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Forced any "location" that was not marked as "Viewable on Web-Site" to be hidden on the Self-Service reservation view within the Events controller.</li>
		<li>Fixed bug that was causing "role admins" to not be able to view locations on the event creation view.</li>
	</ol>
	<hr />	
	<h1>Release 3.6 (1-31-13) </h1>
	<h2>Major Updates</h2>
	<ol>
		<li>Release of the EVENTS module. This module will allow admins to create Events within the ATLAS Web Services System.</li>
		<li>Release of expanded "Workshop" interface to automate the process of registration, enrollment and tracking of participants within a workshop</li>
		<li>Release of redeveloped user Dashboard. Clearly lists all EVENTS, ORIENTATIONS, REGISTRATIONS and ENROLLMENTS the customer is participating in.</li>
	</ol>
	<h2>Minor Updates</h2>
	<ol>
		<li>Added additional fields to all excel reports within the system. "First Name, Last Name and Last 4" are now separate fields</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Resolved issues that was causing search parameters to be cleared when using breadcrumbs between Users-Customers,Staff screens</li>
	</ol>
	<hr />
	<h1>Release 3.5.8 (1-17-13)</h1>
	<h2>Minor feature enhancements</h2>
	<ol>
		<li>Added "Basic" search parameter to the User-Customers view. Previous search was moved to "Advanced" search tab.</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixed bug that was causing bar code definitions to trip "unique" value validation</li>
	</ol>
	<hr />
	<h1>Release 3.5.7 (1-10-13)</h1>
	<h2>Minor feature enhancements</h2>
	<ol>
		<li>Add last 4 ssn and user details to the excel report in self sign log archives.</li>
		<li>Increased the max records self sign log excel report to 20,000 records </li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixing permissions bug in reports module</li>
	</ol>
	<hr />
	<h1>Release 3.5.6 (11-27-12)</h1>
	<ol>
		<li>Lowered the Program responses expire in minimum value to 10.</li>
		<li>Added decoding of custom select options to the program form builder on edit.</li>
	</ol>
	<hr />
	<h1>Release 3.5.5 (11-21-12)</h1>
	<ol>
		<li>Updated registration expiration to allow for 10,30,60,90.</li>
		<li>Fixed issue with registration edit function with-in the form fields.</li>
		<li>Resolved issue with "not approved" mask that was introduced during EXT framework update.</li>
		<li>Optimized database schema for improved search and data sorting.</li>
		<li>Removed most of the required fileds from the admin add and edit customer forms.</li>
	</ol>
	<hr />
	<h1>Release 3.5.4 (11-1-12)</h1>
	<ol>
		<li>Upgraded core EXTJS framework to 3.x to resolve different load mask and Java-script issues.</li>
		<li>Fixed dashboard menu save-state.</li>
		<li>Fixed additional inconsistencies with the Programs module wizard.</li>
		<li>Resolved issue with location scanned ID when documents are generated by the system</li>
		<li>Fixed issue with document queue filters not working properly when queue is in autoload mode.</li>
	</ol>
	<h1>Release 3.5.3 (10-16-12)</h1>
	<ol>
		<li>Fixed persistence issues in the enrollment programs</li>
		<li>Fixed editing issues in the enrollment programs</li>
		<li>Fixed editing issues in the registration programs</li>
	</ol>
	<hr />
	<h1>Release 3.5.2 (10-16-12)</h1>
	<ol>
		<li>Fixed various issues within the Programs Module</li>
	</ol>
	<hr />
	<h1>Release 3.5.1 (10-16-12)</h1>
	<ol>
		<li>Fixed various issues within the Programs Module</li>
	</ol>
	<hr />
	<h1>Release 3.5 (10-4-12)</h1>
	<h2>Major feature enhancements</h2>
	<ol>
		<li>Refactored programs module to allow admins to create registration and orientation type programs.</li>
		<li>Added electronic signature enrollment for programs that require electronic signature.</li>
		<li>Added ability for system to read barcoded electronic signature enrollment documents that are scanned into Atlas.</li>
		<li>Added excel reporting feature to the admin propgram responses view.</li>
		<li>Added background queue for prcessing program documents and program emails behind the scenes.</li>
		<li>Refactored admin program response apporval view.</li>
		<li>Added ability for admins to reset any step in the program to allow customer to redo that step.</li>
		<li>Refactored customer program interface to make it more user friendly.</li>
	</ol>
	<hr />
	<h1>Release 3.4 (9-13-12)</h1>
	<h2>Major feature enhancements</h2>
	<ol>
		<li>Adjust the kiosk timeout and the “are you there” display window</li>
		<li>Kiosk login will now be last name and full social</li>
		<li>Option to add an “I need assistance” button with customizable message per kiosk</li>
		<li>Added “Do you have a substantial disability” as a core registration question</li>
		<li>Added the ability to have up to 7 additional registration fields on the kiosk registration page</li>
		<li>Added the ability to hide/show the kiosk user info confirmation screen</li>
		<li>Implemented a new help desk interface to allow for greater explanation of issues</li>
	</ol>
	<hr />
	<h1>Release 3.3.4 (7-12-12)</h1>
    <h2>Minor feature enhancements</h2>
	<ol>
		<li>Added the ability to add up to 5 fields to kiosk registration.</li>
		<li>Added USDOL confidentiality statement to the kiosk registration page.</li>
		<li>Kiosk registration registration fields are now optional other than first name, last name, and social security number.</li>
		<li>Added secure category enhancement to the audits module. </li>
	</ol>
	<hr />
    <h1>Release 3.3.3 (4-26-12)</h1>
    <h2>Minor feature enhancements</h2>
    <ol>
		<li>Allow registration type program to be created without a completion certificate.</li>
    </ol>
    <h2>Bug Fixes</h2>
    <ol>
		<li>Fixed bug where role admins could not be granted permissions to edit program response notes.</li>
		<li>Fixed bug where legacy imported document paths were not being referenced properly casing some older documents to not open properly.</li>
    </ol>
    <hr />
    <h1>Release 3.3.2 (4-12-12)</h1>
    <h2>Minor feature enhancements</h2>
    <ol>
        <li>Added Atlas Alerts Client download to alerts interface.</li>
    </ol>
    <h2>Bug Fixes</h2>
    <ol>
        <li>
            Fixed bug where users were unable to login to programs due to recursioni
            being set too high on a database query causing a 500 error.
        </li>
        <li>Fixed bug where documents could potentially be filed to the wrong user from the new document filing queue.</li>
    </ol>
    <hr />
    <h1>Release 3.3.1 (4-05-12)</h1>
    <h2>Minor feature enhancements</h2>
    <ol>
        <li>Added Atlas Alerts Client download to alerts interface.</li>
    </ol>
    <h2>Bug Fixes</h2>
    <ol>
        <li>
            Fixed bug where stale documents in the queued docs grid that had been deleted or 
            filed could be locked, causing the user to see a blank screen in the document viewer.
        </li>
    </ol>
    <hr />
    <h1>Release 3.3 (3-20-12)</h1>
    <h2>Main Feature Releases</h2>
    <ol>
        <li>Redeveloped the document queue interface using EXTJS to make it more efficient &amp; user friendly.</li>
        <li>Added Audit Module.</li>
        <li>Added Secure Filing Categories.</li>
    </ol>
    <h2>Minor feature enhancements</h2>
    <ol>
        <li>Added ability to select date type in search filters in the document archive.</li>
        <li>Added filed date to the filed document archive report.</li>
        <li>Added filed date to the deleted, my filed, and customer document views.</li>
    </ol>
    <hr />
    <h1>Release 3.2.4 (3/08/12)</h1>
	<h2>Minor Feature Enhancements</h2>
	<ol>
		<li>Added a user activity transaction to excel report for document archive.</li>
		<li>Updated support link in admin dash to mailto dev@ctsfla.com.</li>
	</ol>
	<h2>Bug Fixes</h2>
	<ol>
		<li>
			Fixed bug where paging toolbar was not showing if there was enough rows in the grid to make it scroll. Adjusted height of editor below to allow for the extra height needed by the grid.
		</li>		
	</ol>	
	<hr />
	<h1>Release 3.2.3 (2/23/12)</h1>
	<h2>Minor Feature Enhancements</h2>
	<ol>
		<li>Added program name to the header within the programs controller.</li>
		<li>Added functionality to allow for 1st, 2nd or 3rd tier alert notifications.</li>
		<li>Adjusted the tabs within the Programs module to save the user state when navigating between panes.</li>
		<li>
			Allow for pagination settings to be retained when navigating between program responses list and program response data.
		</li>
		<li>Implemented a plug-in module architecture to allow for customized deployments of ATLAS to be delivered.</li>
		<li>Added the ability to deliver program orientations utilizing Adobe Presenter</li>
		<li>Added “last activity admin” to the self-sign excel report.</li>
	</ol>	
	<h2>Bug Fixes</h2>
	<ol>
		<li>
			Resolved issue that was causing the selection dropdowns within the self-sign archive 
			to not funtion properly when making multiple selections.
		</li>
		<li>Adjusted excel report within filed document archive to output up to 10,000 records</li>	
		<li>
			Fixing bug where customers could create multiple program responses if they had a not approved
			response that was marked to allow customer to create a new response. 
		</li>		
	</ol>	
	<hr />
	<h1>Release 3.2.2 (1/26/12)</h1>
	<h2>Bug Fixes</h2>
	<ol>
		<li>FIxed bug that was causing Alerts module to not fully exectue "3rd" level alert criteria</li>
		<li>Fixed spelling error within Alerts email notification</li>
		<li>Additional customer level updates to content</li>
	</ol>
	<hr />
	<h1>Release 3.2.1 (1/19/12)</h1>
	<h2>Bug Fixes</h2>
	<ol>
		<li>Fixing bug where admins could type text into the reassign and new sign in drop down menus in the Self Sign Queue.</li>
		<li>Removing the Espanol/English toggle button from all pages on the kiosk except the main page. This was causing undesirable results if the button was pressed after customer had logged in.</li>
	</ol>
	<hr />
	<h1>Release 3.2.0 (1/12/12)</h1>
	<h2>Major Feature Enhancements</h2>
	<ol>
		<li>Release of new ATLAS Alerts Desktop Client to allow for desktop growl notifications when specified alert criteria is met.</li>
		<li>Implemented the ability to have roaming Alerts based on Logged in Windows Username.</li>
		<li>Addition of the following Alerts modules</li>
		<ol>
			<li>Customer Details Alert – Allows you to alert when defined registration criteria is met, i.e.: Veteran</li>
			<li>Self-Scan Alert – Send an alert when a specific customer self-scans a document</li>
			<li>Customer Filed Document – Send alert when a document is filed for a specific customer</li>
			<li>Customer Login – Send alert on a defined customer login</li>			
		</ol>
		<li>Report BETA has now been activated on all Production Servers.</li>
		<ol>
			<li>Implementation of Unduplicated Customer report controller</li>
			<li>Implementation of Services report controller</li>
			<li>Added ability to save most used report criteria as a preset within ATLAS</li>
			<li>Added additional group by and timeframe functions to allow for greater control over reporting outcomes</li>
			<li>Added ability to toggle between Line Graph and Bar Chart</li>
			<li>Added dynamic “zoom” capabilities to focus in on detail areas of a report</li>			
		</ol>
	</ol>
	<h2>Minor Feature Enhancements</h2>
	<ol>
		<li>Implemented a “count” within the self-sign queue display panel.</li>
		<li>Added “Are you a US Veteran” to the optional Kiosk registration fields.</li>	
	</ol>
	<hr />
	<h1>Release 3.1.6 (12/22/11)</h1>
	<h2>Major Feature Enhancements</h2>
	<ol>
		<li>Implementation of ALERTS module. This module will allow ATLAS users to setup specific ALERTS to be generated based on preset conditions within ATLAS.</li>
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
