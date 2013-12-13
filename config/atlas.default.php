<?php
/**
 * Configuration file for Atlas 
 *
 * @author dnolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

// domain for use with cookies
$config['domain'] = 'development.ctsfla.com';

// Company Name
$config['Company']['name'] = 'ATLAS';

// Company State
$config['Company']['state'] = 'Florida';

// Company Counties
$config['Company']['counties'] = array(
	'Pinellas' => 'Pinellas', 
	'Hillsborough' => 'Hillsborough',
	'Pasco' => 'Pasco',
	'Manatee' => 'Manatee', 
	'Other' => 'Other');

// System Email From Address
$config['System']['email'] = 'noreply@development.ctsfla.com';

// Pre Pop Email
$config['PrePop']['email'] = '@development.ctsfla.com';

// Registration SSN requirement (options are 'full' or 'last4')
$config['Registration']['ssn'] = 'full';

// Login SSN enum = 'full', 'last4', 'last5'
$config['Login']['ssn'] = 'full';

// Kiosk login type (options are 'id_card or normal'
$config['Kiosk']['login_type'] = 'normal';

// Registration - Use password or SSN
// True to use a password, false to use SSN
$config['Registration']['usePassword'] = false;

// Document Storage location
$config['Document']['storage']['path'] = '/storage/';

// Document Upload path
$config['Document']['storage']['uploadPath'] = '../storage/';

// Absolute path to storage
$config['Document']['storage']['absolutePath'] = APP . 'storage';

// Time before partial_document timeout
$config['PartialDocumentSessionTimeout'] = "+30 mintes";

// Document Jpeg location
$config['Document']['jpeg']['path'] = '/storage/thumbnails/';

// Program storage path
$config['Program']['media']['path'] = '/storage/program_media/';

// Ecourse storage path
$config['Ecourse']['media']['path'] = '/storage/ecourse_media/';

// Admin alert E-Mail address
$config['Admin']['alert']['email'] = 'bill@ctsfla.com';

// Document Scan path
$config['Document']['scan']['path'] = '/var/www/vhosts/deploy/atlas/shared/scan_folders/';

// FTP Log Path
$config['FTP']['log']['path'] = '/var/log/proftpd/xferlog';

// Pagination Limits
$config['Pagination']['customer']['limit'] = 10 ;
$config['Pagination']['admin']['limit'] = 10 ;
$config['Pagination']['kiosk']['limit'] = 10 ;
$config['Pagination']['selfSignLogArchive']['limit'] = 10;
$config['Pagination']['deletedDocument']['limit'] = 10;
