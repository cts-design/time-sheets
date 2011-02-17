<?php
/**
 * Configuration file for Atlas 
 *
 * @author dnolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

$config['URL'] = 'http://atlas.dev/';
$config['Admin']['URL'] = 'http://atlas.dev/admin';
//domain for use with cookies
$config['domain'] = 'atlas.dev';

// Company Name
$config['Company']['name'] = 'ATLAS';

// Company Logo
$config['Company']['logo'] = 'atlas_logo.jpg';

// System Email From Address
$config['System']['email'] = 'noreply@atlasforworkforce.com';

// Pre Pop Email
$config['PrePop']['email'] = '@atlasforworkforce.com';

// Document Storage location
$config['Document']['storage']['path'] = '/storage/';

// Document Upload path
$config['Document']['storage']['uploadPath'] = '../storage/';

// Document Jpeg location
$config['Document']['jpeg']['path'] = '/img/storage/';

// Pagination Limits
$config['Pagination']['customer']['limit'] = 10 ;
$config['Pagination']['admin']['limit'] = 10 ;
$config['Pagination']['kiosk']['limit'] = 10 ;
$config['Pagination']['selfSignLogArchive']['limit'] = 10;
$config['Pagination']['deletedDocument']['limit'] = 10;
