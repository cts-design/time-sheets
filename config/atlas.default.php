<?php
/**
 * Configuration file for Atlas 
 *
 * @author dnolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */


$config['URL'] = 'http://tbwa.ctsfla.com/';
$config['Admin']['URL'] = 'http://tbwa.ctsfla.com/admin';
//domain for use with cookies
$config['domain'] = 'tbwa.ctsfla.com';


// Company Name
$config['Company']['name'] = 'Tampa Bay Workforce Alliance';

// System Email From Address
$config['System']['email'] = 'noreply@atlasforworkforce.com';

// Pre Pop Email
$config['PrePop']['email'] = '@atlasforworkforce.com';

// Document Storage location
$config['Document']['storage']['path'] = '/storage/';

// Document Upload path
$config['Document']['storage']['uploadPath'] = '../storage/';

// Document Jpeg location
$config['Document']['jpeg']['path'] = '/storage/thumbnails/';

// Pagination Limits
$config['Pagination']['customer']['limit'] = 10 ;
$config['Pagination']['admin']['limit'] = 10 ;
$config['Pagination']['kiosk']['limit'] = 10 ;
$config['Pagination']['selfSignLogArchive']['limit'] = 10;
$config['Pagination']['deletedDocument']['limit'] = 10;
