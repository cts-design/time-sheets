<?php
/**
 * Configuration file for Atlas 
 *
 * @author dnolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

$config['URL'] = 'http://ccc.atlasforworkforce.com/';
$config['Admin']['URL'] = 'https://ccc.atlasforworkforce.com/admin';
//domain for use with cookies
$config['domain'] = 'ccc.atlasforworkforce.com';

// Company Name
$config['Company']['name'] = 'ATLAS';

// System Email From Address
$config['System']['email'] = 'noreply@ccc.atlasforworkforce.com';

// Pre Pop Email
$config['PrePop']['email'] = '@ccc.atlasforworkforce.com';

// Document Storage location
$config['Document']['storage']['path'] = '/storage/';

// Document Upload path
$config['Document']['storage']['uploadPath'] = '../storage/';

// Document Jpeg location
$config['Document']['jpeg']['path'] = '/storage/thumbnails/';

// Program storage path
$config['Program']['media']['path'] = '/storage/program_media/';

// Pagination Limits
$config['Pagination']['customer']['limit'] = 10 ;
$config['Pagination']['admin']['limit'] = 10 ;
$config['Pagination']['kiosk']['limit'] = 10 ;
$config['Pagination']['selfSignLogArchive']['limit'] = 10;
$config['Pagination']['deletedDocument']['limit'] = 10;
