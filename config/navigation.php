<?php
// When adding any leaves to the tree that will have children make sure to add an id config.
// This is needed in order for the cookie that save the open tree nodes to work

$config['navigation.website'] = array(
	'rel' => 'website',
	'title' => 'Website',
	'id' => 'website',
	'links' => array(
		array(
			'link' => array('controller' => 'pages', 'action' => 'index'),
			'rel' => 'pages',
			'title' => 'Pages'
		),
		array(
			'link' => array('controller' => 'navigations', 'action' => 'index'),
			'rel' => 'navigation',
			'title' => 'Navigation'
		),
		array(
			'rel' => 'inTheNews',
			'title' => 'News',
			'id' => 'news',
			'children' => array(
				array(
					'link' => array('controller' => 'press_releases', 'action' => 'index'),
					'rel' => 'pressReleases',
					'title' => 'Press Releases'
				),
				array(
					'link' => array('controller' => 'chairman_reports', 'action' => 'index'),
					'rel' => 'chairmanReports',
					'title' => 'Chairman Reports'
				),
				array(
					'link' => array('controller' => 'in_the_news', 'action' => 'index'),
					'rel' => 'inTheNews',
					'title' => 'In the News'
				),
				array(
					'link' => array('controller' => 'helpful_articles', 'action' => 'index'),
					'rel' => 'inTheNews',
					'title' => 'Helpful Articles'
				)
			)
		),
		array(
			'rel' => 'surveys',
			'title' => 'Surveys',
			'id' => 'surveys',
			'children' => array(
				array(
					'link' => array('controller' => 'career_seekers_surveys', 'action' => 'index'),
					'rel' => 'careerSeekersSurveys',
					'title' => 'Career Seekers Surveys'
				),
				array(
					'link' => array('controller' => 'employers_surveys', 'action' => 'index'),
					'rel' => 'employersSurveys',
					'title' => 'Employer Surveys'
				)
			)
		),
		array(
			'link' => array('controller' => 'hot_jobs', 'action' => 'index'),
			'rel' => 'hotJobs',
			'title' => 'Hot Jobs'
		),
		array(
			'link' => array('controller' => 'rfps', 'action' => 'index'),
			'rel' => 'rfp',
			'title' => 'RFPs & Bids'
		),
		array(
			'link' => array('controller' => 'featured_employers', 'action' => 'index'),
			'rel' => 'featured',
			'title' => 'Featured Employer'
		)
	)
);

$config['navigation.settings'] = array(
	'rel' => 'settings',
	'title' => 'Settings',
	'id' => 'settings',
	'links' => array(
		array(
			'link' => array('controller' => 'settings', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Atlas Module Preferences & Settings'
		),
		array(
			'link' => array('controller' => 'bar_code_definitions', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Bar Code Definitions'
		),
		array(
			'link' => array('controller' => 'document_filing_categories', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Document Filing Categories'
		),
		array(
			'link' => array('controller' => 'document_queue_categories', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Document Queue Categories'
		),
		array(
			'link' => array('controller' => 'event_categories', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Event Categories'
		),
		array(
			'link' => array('controller' => 'ftp_document_scanners', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'FTP Document Scanners'
		),
		array(
			'link' => array('controller' => 'locations', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Locations'
		),
		array(
			'link' => array('controller' => 'master_kiosk_buttons', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Master Kiosk Buttons'
		),
		array(
			'hasPermission' => 2,
			'link' => array('controller' => 'module_access_controls', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Module Access Control'
		),
		array(
			'link' => array('controller' => 'roles', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Roles'
		),
		array(
			'link' => array('controller' => 'secure_categories', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Secure Categories'
		),            
		array(
			'link' => array('controller' => 'self_scan_categories', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Self Scan Categories'
		),
		array(
			'link' => array('controller' => 'kiosks', 'action' => 'index'),
			'rel' => 'settings_1',
			'title' => 'Self Sign Kiosk/Location Settings'
		)
	)
);

$config['navigation.alerts'] = array(
	'link' => array('controller' => 'alerts', 'action' => 'index'),
	'rel' => 'alerts',
	'title' => 'Alerts'
);

$config['navigation.events'] = array(
	'rel' => 'calendar',
	'title' => 'Events',
	'id' => 'events',
	'links' => array(
		array(
			'link' => array('controller' => 'events', 'action' => 'admin_list_events_registration'),
			'rel' => 'calendar',
			'title' => 'Event Registration'
		),
		array(
			'link' => array('controller' => 'events', 'action' => 'index'),
			'rel' => 'calendar',
			'title' => 'Event Management'
		),
		array(
			'link' => array('controller' => 'events', 'action' => 'archive'),
			'rel' => 'calendar',
			'title' => 'Event Archive'
		)
	)
);


$config['navigation.users'] = array(
	'rel' => 'group',
	'title' => 'Users',
	'id' => 'users',
	'links' => array(
		array(
			'link' => array('controller' => 'users', 'action' => 'index_admin'),
			'rel' => 'group',
			'title' => 'Administrators'
		),
		array(
			'link' => array('controller' => 'users', 'action' => 'index'),
			'rel' => 'group',
			'title' => 'Customers'
		),
		array(
			'link' => array('controller' => 'users', 'action' => 'index_auditor'),
			'rel' => 'group',
			'title' => 'Auditors'
		)
	)
);

$config['navigation.selfSign'] = array(
	'rel' => 'user',
	'title' => 'Self Sign',
	'id' => 'selfSign',
	'links' => array(
		array(
			'link' => array('controller' => 'self_sign_logs', 'action' => 'index'),
			'rel' => 'queue',
			'title' => 'Self Sign Queue'
		),
		array(
			'link' => array('controller' => 'self_sign_log_archives', 'action' => 'index'),
			'rel' => 'archive',
			'title' => 'Self Sign Archives'
		),
		array(
			'link' => array('controller' => 'kiosk_surveys', 'action' => 'index'),
			'rel' => 'selfSignSurvey',
			'title' => 'Self Sign Surveys'
		)
	)
);

$config['navigation.storage'] = array(
	'rel' => 'storage',
	'title' => 'Storage',
	'id' => 'storage',
	'links' => array(
		array(
			'link' => array('controller' => 'queued_documents', 'action' => 'index'),
			'rel' => 'queue',
			'title' => 'Queued Documents'
		),
		array(
			'link' => array('controller' => 'queued_documents', 'action' => 'desktop_scan_document'),
			'rel' => 'scan',
			'title' => 'Desktop Scan'
		),
		array(
			'link' => array('controller' => 'filed_documents', 'action' => 'index'),
			'rel' => '',
			'title' => 'My Filed Documents'
		),
		array(
			'link' => array('controller' => 'filed_documents', 'action' => 'view_all_docs'),
			'rel' => 'archive',
			'title' => 'Filed Document Archive'
		),
		array(
			'link' => array('controller' => 'deleted_documents', 'action' => 'index'),
			'rel' => 'trash',
			'title' => 'Deleted Documents'
		),
	)
);

$config['navigation.programs'] = array(
	'link' => array('controller' => 'programs', 'action' => 'index'),
	'rel' => 'programs',
	'title' => 'Programs'
);

$config['navigation.ecourses'] = array(
	'link' => array('controller' => 'ecourses', 'action' => 'index'),
	'rel' => 'ecourses',
	'title' => 'Ecourses'
);

$config['navigation.audits'] = array(
	'link' => array('controller' => 'audits', 'action' => 'index'),
	'rel' => 'audits',
	'title' => 'Audits'
);

$config['navigation.tools'] = array(
	'rel' => 'tools',
	'title' => 'Tools',
	'id' => 'tools',
	'links' => array(
		array(
			'link' => array('controller' => 'users', 'action' => 'resolve_login_issues'),
			'rel' => 'loginIssues',
			'title' => 'Resolve Login Issues'
		),
		array(
			'link' => array('controller' => 'reports', 'action' => 'index'),
			'rel' => 'reports',
			'title' => 'Reports'
		),
		array(
			'link' => array('controller' => 'programs', 'action' => 'alternative_media'),
			'rel' => 'alternativeMedia',
			'title' => 'Alternative Media'
		)
	)
);
