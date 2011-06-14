<?php
/* DocumentFilingCategory Fixture generated on: 2011-06-13 14:50:08 : 1307991008 */
class DocumentFilingCategoryFixture extends CakeTestFixture {
	var $name = 'DocumentFilingCategory';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'disabled' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => NULL,
			'name' => 'Valid Category',
			'disabled' => 0,
			'lft' => 1,
			'rght' => 8,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
		),
		array(
			'id' => 2,
			'parent_id' => NULL,
			'name' => 'Disabled Category',
			'disabled' => 1,
			'lft' => 9,
			'rght' => 12,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
		),
		array(
			'id' => 3,
			'parent_id' => 1,
			'name' => 'A Nested Valid Category',
			'disabled' => 0,
			'lft' => 2,
			'rght' => 7,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
		),
        array(
			'id' => 4,
			'parent_id' => 3,
			'name' => 'Another Nested Category',
			'disabled' => 0,
			'lft' => 3,
			'rght' => 4,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
        ),
        array(
			'id' => 5,
			'parent_id' => NULL,
			'name' => 'Another Parent Category',
			'disabled' => 0,
			'lft' => 3,
			'rght' => 4,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
        ) ,
        array(
			'id' => 6,
			'parent_id' => 3,
			'name' => 'Another Parent Category',
			'disabled' => 0,
			'lft' => 5,
			'rght' => 6,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
        ),
        array(
			'id' => 7,
			'parent_id' => 2,
			'name' => 'Disabled Child',
			'disabled' => 1,
			'lft' => 10,
			'rght' => 11,
			'created' => '2010-10-19 15:57:41',
			'modified' => '2010-10-19 15:57:41'
        ),
		array(
			'id' => 20,
			'parent_id' => NULL,
			'name' => 'IWT-EWT',
			'lft' => 109,
			'rght' => 130,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:01',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 21,
			'parent_id' => NULL,
			'name' => 'Job Fair',
			'lft' => 303,
			'rght' => 306,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:08',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 22,
			'parent_id' => NULL,
			'name' => 'NCPEP',
			'lft' => 285,
			'rght' => 302,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:12',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 23,
			'parent_id' => NULL,
			'name' => 'PRA',
			'lft' => 269,
			'rght' => 284,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:17',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 24,
			'parent_id' => NULL,
			'name' => 'REA',
			'lft' => 261,
			'rght' => 268,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 25,
			'parent_id' => NULL,
			'name' => 'Special Projects',
			'lft' => 233,
			'rght' => 260,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:26',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 26,
			'parent_id' => NULL,
			'name' => 'TAA',
			'lft' => 179,
			'rght' => 232,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:30',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 27,
			'parent_id' => NULL,
			'name' => 'WIA',
			'lft' => 131,
			'rght' => 178,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:36',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 28,
			'parent_id' => NULL,
			'name' => 'WIA Youth',
			'lft' => 397,
			'rght' => 442,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:42',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 29,
			'parent_id' => NULL,
			'name' => 'WP',
			'lft' => 103,
			'rght' => 108,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:52',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 30,
			'parent_id' => NULL,
			'name' => 'WTP',
			'lft' => 33,
			'rght' => 102,
			'disabled' => 0,
			'created' => '2010-11-01 09:02:56',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 31,
			'parent_id' => NULL,
			'name' => 'REA2009',
			'lft' => 355,
			'rght' => 360,
			'disabled' => 0,
			'created' => '2010-11-01 09:03:05',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 32,
			'parent_id' => NULL,
			'name' => 'TWW',
			'lft' => 361,
			'rght' => 372,
			'disabled' => 0,
			'created' => '2010-11-01 09:03:09',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 33,
			'parent_id' => NULL,
			'name' => 'YouthBuild',
			'lft' => 373,
			'rght' => 374,
			'disabled' => 0,
			'created' => '2010-11-01 09:03:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 34,
			'parent_id' => NULL,
			'name' => 'Career Camps',
			'lft' => 395,
			'rght' => 396,
			'disabled' => 0,
			'created' => '2010-11-01 09:03:30',
			'modified' => '2011-05-09 09:33:14'
		),
		array(
			'id' => 35,
			'parent_id' => NULL,
			'name' => 'LEAPS - SEP',
			'lft' => 387,
			'rght' => 394,
			'disabled' => 0,
			'created' => '2010-11-01 09:03:38',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 36,
			'parent_id' => NULL,
			'name' => 'Nurses Now',
			'lft' => 383,
			'rght' => 386,
			'disabled' => 0,
			'created' => '2010-11-01 09:03:46',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 37,
			'parent_id' => NULL,
			'name' => 'Florida Rebuilds',
			'lft' => 381,
			'rght' => 382,
			'disabled' => 0,
			'created' => '2010-11-01 09:03:55',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 38,
			'parent_id' => NULL,
			'name' => 'E-Signature',
			'lft' => 375,
			'rght' => 380,
			'disabled' => 0,
			'created' => '2010-11-01 09:04:00',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 39,
			'parent_id' => 2,
			'name' => 'Archive',
			'lft' => 346,
			'rght' => 353,
			'disabled' => 0,
			'created' => '2010-11-01 09:04:24',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 40,
			'parent_id' => 2,
			'name' => 'Enrollment Information',
			'lft' => 336,
			'rght' => 345,
			'disabled' => 0,
			'created' => '2010-11-01 09:50:52',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 41,
			'parent_id' => 20,
			'name' => 'Archive',
			'lft' => 126,
			'rght' => 129,
			'disabled' => 0,
			'created' => '2010-11-01 09:51:16',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 42,
			'parent_id' => 20,
			'name' => 'Enrollment Information',
			'lft' => 124,
			'rght' => 125,
			'disabled' => 0,
			'created' => '2010-11-01 09:51:32',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 44,
			'parent_id' => 20,
			'name' => 'Planning Documents',
			'lft' => 118,
			'rght' => 123,
			'disabled' => 0,
			'created' => '2010-11-01 09:51:57',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 45,
			'parent_id' => 20,
			'name' => 'Credentials andCertificates',
			'lft' => 116,
			'rght' => 117,
			'disabled' => 0,
			'created' => '2010-11-01 09:52:14',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 46,
			'parent_id' => 20,
			'name' => 'Financial',
			'lft' => 114,
			'rght' => 115,
			'disabled' => 0,
			'created' => '2010-11-01 09:52:24',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 47,
			'parent_id' => 20,
			'name' => 'Employment Information',
			'lft' => 112,
			'rght' => 113,
			'disabled' => 0,
			'created' => '2010-11-01 09:52:35',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 48,
			'parent_id' => 22,
			'name' => 'Chart Index',
			'lft' => 286,
			'rght' => 301,
			'disabled' => 0,
			'created' => '2010-11-01 09:52:55',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 49,
			'parent_id' => 23,
			'name' => 'Archive',
			'lft' => 282,
			'rght' => 283,
			'disabled' => 0,
			'created' => '2010-11-01 09:53:05',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 50,
			'parent_id' => 23,
			'name' => 'Enrollement Information',
			'lft' => 280,
			'rght' => 281,
			'disabled' => 0,
			'created' => '2010-11-01 09:53:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 51,
			'parent_id' => 23,
			'name' => 'Planning Documents',
			'lft' => 278,
			'rght' => 279,
			'disabled' => 0,
			'created' => '2010-11-01 09:53:36',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 52,
			'parent_id' => 23,
			'name' => 'Communiques',
			'lft' => 276,
			'rght' => 277,
			'disabled' => 0,
			'created' => '2010-11-01 09:53:48',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 53,
			'parent_id' => 23,
			'name' => 'Work Activities',
			'lft' => 274,
			'rght' => 275,
			'disabled' => 0,
			'created' => '2010-11-01 09:53:58',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 54,
			'parent_id' => 23,
			'name' => 'Financial',
			'lft' => 272,
			'rght' => 273,
			'disabled' => 0,
			'created' => '2010-11-01 09:54:06',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 55,
			'parent_id' => 23,
			'name' => 'Employment Information',
			'lft' => 270,
			'rght' => 271,
			'disabled' => 0,
			'created' => '2010-11-01 09:54:20',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 56,
			'parent_id' => 24,
			'name' => 'Plan',
			'lft' => 266,
			'rght' => 267,
			'disabled' => 0,
			'created' => '2010-11-01 09:54:32',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 57,
			'parent_id' => 24,
			'name' => 'Assesment',
			'lft' => 264,
			'rght' => 265,
			'disabled' => 0,
			'created' => '2010-11-01 09:54:42',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 58,
			'parent_id' => 24,
			'name' => 'Notes',
			'lft' => 262,
			'rght' => 263,
			'disabled' => 0,
			'created' => '2010-11-01 09:54:51',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 62,
			'parent_id' => 48,
			'name' => 'File 1',
			'lft' => 287,
			'rght' => 288,
			'disabled' => 0,
			'created' => '2010-11-09 16:57:58',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 63,
			'parent_id' => 2,
			'name' => 'Communiques',
			'lft' => 310,
			'rght' => 323,
			'disabled' => 0,
			'created' => '2010-12-20 10:14:16',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 66,
			'parent_id' => 2,
			'name' => 'Work Activities',
			'lft' => 324,
			'rght' => 329,
			'disabled' => 0,
			'created' => '2010-12-20 10:15:12',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 67,
			'parent_id' => 2,
			'name' => 'Special Conditions',
			'lft' => 330,
			'rght' => 335,
			'disabled' => 0,
			'created' => '2010-12-20 10:15:23',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 68,
			'parent_id' => 40,
			'name' => 'Paper Intake Packet',
			'lft' => 343,
			'rght' => 344,
			'disabled' => 0,
			'created' => '2010-12-20 10:15:41',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 69,
			'parent_id' => 40,
			'name' => 'Assessments (TABE, WRC)',
			'lft' => 341,
			'rght' => 342,
			'disabled' => 0,
			'created' => '2010-12-20 10:16:20',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 70,
			'parent_id' => 40,
			'name' => 'Employment Plan',
			'lft' => 339,
			'rght' => 340,
			'disabled' => 0,
			'created' => '2010-12-20 10:16:33',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 72,
			'parent_id' => 40,
			'name' => 'Online Orientation Packet',
			'lft' => 337,
			'rght' => 338,
			'disabled' => 0,
			'created' => '2010-12-20 10:17:12',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 73,
			'parent_id' => 63,
			'name' => 'Orientation Assessment Letters',
			'lft' => 321,
			'rght' => 322,
			'disabled' => 0,
			'created' => '2010-12-20 10:18:49',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 74,
			'parent_id' => 63,
			'name' => 'Misc Letters',
			'lft' => 319,
			'rght' => 320,
			'disabled' => 0,
			'created' => '2010-12-20 10:19:01',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 75,
			'parent_id' => 66,
			'name' => 'Work Experience Job Descriptions',
			'lft' => 327,
			'rght' => 328,
			'disabled' => 0,
			'created' => '2010-12-20 10:45:55',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 76,
			'parent_id' => 66,
			'name' => 'Work Experience Time Sheets',
			'lft' => 325,
			'rght' => 326,
			'disabled' => 0,
			'created' => '2010-12-20 10:46:13',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 77,
			'parent_id' => 63,
			'name' => 'Job Search Form',
			'lft' => 317,
			'rght' => 318,
			'disabled' => 0,
			'created' => '2010-12-20 10:46:26',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 78,
			'parent_id' => 63,
			'name' => 'School Verifications and Time',
			'lft' => 315,
			'rght' => 316,
			'disabled' => 0,
			'created' => '2010-12-20 10:46:45',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 79,
			'parent_id' => 63,
			'name' => 'Employment Verifications and Pay',
			'lft' => 313,
			'rght' => 314,
			'disabled' => 0,
			'created' => '2010-12-20 10:46:58',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 80,
			'parent_id' => 63,
			'name' => 'Transportation Receipts',
			'lft' => 311,
			'rght' => 312,
			'disabled' => 0,
			'created' => '2010-12-20 10:47:09',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 81,
			'parent_id' => 67,
			'name' => 'Good Cause',
			'lft' => 333,
			'rght' => 334,
			'disabled' => 0,
			'created' => '2010-12-20 10:47:27',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 82,
			'parent_id' => 67,
			'name' => 'Support Service Request',
			'lft' => 331,
			'rght' => 332,
			'disabled' => 0,
			'created' => '2010-12-20 10:47:41',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 83,
			'parent_id' => 48,
			'name' => 'Admissions',
			'lft' => 299,
			'rght' => 300,
			'disabled' => 0,
			'created' => '2010-12-20 10:48:27',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 84,
			'parent_id' => 48,
			'name' => 'Client Status',
			'lft' => 297,
			'rght' => 298,
			'disabled' => 0,
			'created' => '2010-12-20 10:48:37',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 85,
			'parent_id' => 48,
			'name' => 'Evaluations/Orientations',
			'lft' => 295,
			'rght' => 296,
			'disabled' => 0,
			'created' => '2010-12-20 10:48:48',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 86,
			'parent_id' => 48,
			'name' => 'Legal',
			'lft' => 293,
			'rght' => 294,
			'disabled' => 0,
			'created' => '2010-12-20 10:48:57',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 87,
			'parent_id' => 48,
			'name' => 'Progress Notes',
			'lft' => 291,
			'rght' => 292,
			'disabled' => 0,
			'created' => '2010-12-20 10:49:06',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 88,
			'parent_id' => 48,
			'name' => 'Correspondance',
			'lft' => 289,
			'rght' => 290,
			'disabled' => 0,
			'created' => '2010-12-20 10:49:24',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 89,
			'parent_id' => 25,
			'name' => 'Archive',
			'lft' => 242,
			'rght' => 243,
			'disabled' => 0,
			'created' => '2010-12-20 10:49:54',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 90,
			'parent_id' => 25,
			'name' => 'Enrollment Information',
			'lft' => 240,
			'rght' => 241,
			'disabled' => 0,
			'created' => '2010-12-20 10:50:11',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 91,
			'parent_id' => 25,
			'name' => 'Planning Documents',
			'lft' => 238,
			'rght' => 239,
			'disabled' => 0,
			'created' => '2010-12-20 10:50:20',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 93,
			'parent_id' => 25,
			'name' => 'Credentials and Certificates',
			'lft' => 236,
			'rght' => 237,
			'disabled' => 0,
			'created' => '2010-12-20 10:50:53',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 94,
			'parent_id' => 25,
			'name' => 'Financial',
			'lft' => 244,
			'rght' => 245,
			'disabled' => 0,
			'created' => '2010-12-20 10:51:04',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 95,
			'parent_id' => 25,
			'name' => 'Employment Information',
			'lft' => 246,
			'rght' => 247,
			'disabled' => 0,
			'created' => '2010-12-20 10:51:14',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 96,
			'parent_id' => 25,
			'name' => 'Youth Build',
			'lft' => 248,
			'rght' => 253,
			'disabled' => 0,
			'created' => '2010-12-20 10:51:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 97,
			'parent_id' => 96,
			'name' => 'Eligibility',
			'lft' => 251,
			'rght' => 252,
			'disabled' => 0,
			'created' => '2010-12-20 10:51:34',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 98,
			'parent_id' => 96,
			'name' => 'Other',
			'lft' => 249,
			'rght' => 250,
			'disabled' => 0,
			'created' => '2010-12-20 10:51:39',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 99,
			'parent_id' => 25,
			'name' => 'Boot Camps',
			'lft' => 254,
			'rght' => 259,
			'disabled' => 0,
			'created' => '2010-12-20 10:51:51',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 100,
			'parent_id' => 99,
			'name' => 'Eligibility',
			'lft' => 257,
			'rght' => 258,
			'disabled' => 0,
			'created' => '2010-12-20 10:51:58',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 101,
			'parent_id' => 99,
			'name' => 'Other',
			'lft' => 255,
			'rght' => 256,
			'disabled' => 0,
			'created' => '2010-12-20 10:52:03',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 102,
			'parent_id' => 26,
			'name' => 'Enrollment Information',
			'lft' => 222,
			'rght' => 231,
			'disabled' => 0,
			'created' => '2010-12-20 10:52:19',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 103,
			'parent_id' => 102,
			'name' => 'TAA Enrollment Forms Training',
			'lft' => 229,
			'rght' => 230,
			'disabled' => 0,
			'created' => '2010-12-20 10:52:44',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 104,
			'parent_id' => 102,
			'name' => 'TAA App and Validation Docs',
			'lft' => 227,
			'rght' => 228,
			'disabled' => 0,
			'created' => '2010-12-20 10:53:00',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 105,
			'parent_id' => 102,
			'name' => 'TAA Waivers',
			'lft' => 225,
			'rght' => 226,
			'disabled' => 0,
			'created' => '2010-12-20 10:53:06',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 107,
			'parent_id' => 102,
			'name' => 'WIA/TAA Dual Enrollment Forms',
			'lft' => 223,
			'rght' => 224,
			'disabled' => 0,
			'created' => '2010-12-20 10:53:34',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 108,
			'parent_id' => 26,
			'name' => 'Communiques',
			'lft' => 216,
			'rght' => 221,
			'disabled' => 0,
			'created' => '2010-12-20 10:53:46',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 109,
			'parent_id' => 108,
			'name' => 'Customer Monthly Report',
			'lft' => 219,
			'rght' => 220,
			'disabled' => 0,
			'created' => '2010-12-20 10:54:00',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 110,
			'parent_id' => 108,
			'name' => 'Internal/External Communications',
			'lft' => 217,
			'rght' => 218,
			'disabled' => 0,
			'created' => '2010-12-20 10:54:12',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 111,
			'parent_id' => 26,
			'name' => 'Work Activities',
			'lft' => 202,
			'rght' => 215,
			'disabled' => 0,
			'created' => '2010-12-20 10:54:27',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 112,
			'parent_id' => 111,
			'name' => 'Attendance Verification',
			'lft' => 213,
			'rght' => 214,
			'disabled' => 0,
			'created' => '2010-12-20 10:54:41',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 113,
			'parent_id' => 111,
			'name' => 'OJT Time Sheets',
			'lft' => 211,
			'rght' => 212,
			'disabled' => 0,
			'created' => '2010-12-20 10:55:09',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 114,
			'parent_id' => 111,
			'name' => 'Job Search (After Enrollment)',
			'lft' => 209,
			'rght' => 210,
			'disabled' => 0,
			'created' => '2010-12-20 10:55:34',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 115,
			'parent_id' => 111,
			'name' => 'Grades Transcriptions (After Enrollment)',
			'lft' => 207,
			'rght' => 208,
			'disabled' => 0,
			'created' => '2010-12-20 10:55:55',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 116,
			'parent_id' => 111,
			'name' => 'Credentials (After Enrollment)',
			'lft' => 205,
			'rght' => 206,
			'disabled' => 0,
			'created' => '2010-12-20 10:56:20',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 117,
			'parent_id' => 111,
			'name' => 'Revised TAA Planning Docs',
			'lft' => 203,
			'rght' => 204,
			'disabled' => 0,
			'created' => '2010-12-20 10:56:38',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 118,
			'parent_id' => 26,
			'name' => 'Financial',
			'lft' => 194,
			'rght' => 199,
			'disabled' => 0,
			'created' => '2010-12-20 10:56:49',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 119,
			'parent_id' => 118,
			'name' => 'Budget',
			'lft' => 197,
			'rght' => 198,
			'disabled' => 0,
			'created' => '2010-12-20 10:57:00',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 120,
			'parent_id' => 118,
			'name' => 'Support Service Request',
			'lft' => 195,
			'rght' => 196,
			'disabled' => 0,
			'created' => '2010-12-20 10:57:12',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 121,
			'parent_id' => 26,
			'name' => 'Employment Information',
			'lft' => 180,
			'rght' => 193,
			'disabled' => 0,
			'created' => '2010-12-20 10:57:30',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 122,
			'parent_id' => 121,
			'name' => 'Employment',
			'lft' => 181,
			'rght' => 182,
			'disabled' => 0,
			'created' => '2010-12-20 10:57:37',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 123,
			'parent_id' => 121,
			'name' => 'TAA/TRA Completion Forms',
			'lft' => 183,
			'rght' => 184,
			'disabled' => 0,
			'created' => '2010-12-20 10:58:05',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 124,
			'parent_id' => 121,
			'name' => 'Resume',
			'lft' => 185,
			'rght' => 186,
			'disabled' => 0,
			'created' => '2010-12-20 10:58:18',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 125,
			'parent_id' => 121,
			'name' => 'Follow-Up Contacts',
			'lft' => 187,
			'rght' => 188,
			'disabled' => 0,
			'created' => '2010-12-20 10:58:32',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 126,
			'parent_id' => 121,
			'name' => 'Exit Letter',
			'lft' => 191,
			'rght' => 192,
			'disabled' => 0,
			'created' => '2010-12-20 10:58:39',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 127,
			'parent_id' => 121,
			'name' => 'No Fault Exit Info',
			'lft' => 189,
			'rght' => 190,
			'disabled' => 0,
			'created' => '2010-12-20 10:58:52',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 128,
			'parent_id' => 26,
			'name' => 'Archive',
			'lft' => 200,
			'rght' => 201,
			'disabled' => 0,
			'created' => '2010-12-20 10:59:03',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 129,
			'parent_id' => 27,
			'name' => 'Enrollment Information',
			'lft' => 172,
			'rght' => 177,
			'disabled' => 0,
			'created' => '2010-12-20 10:59:27',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 130,
			'parent_id' => 27,
			'name' => 'Communiques',
			'lft' => 166,
			'rght' => 171,
			'disabled' => 0,
			'created' => '2010-12-20 10:59:51',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 131,
			'parent_id' => 27,
			'name' => 'Work Activities',
			'lft' => 152,
			'rght' => 165,
			'disabled' => 0,
			'created' => '2010-12-20 11:00:05',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 132,
			'parent_id' => 27,
			'name' => 'Financial',
			'lft' => 146,
			'rght' => 151,
			'disabled' => 0,
			'created' => '2010-12-20 11:00:13',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 133,
			'parent_id' => 27,
			'name' => 'Employment Information',
			'lft' => 132,
			'rght' => 145,
			'disabled' => 0,
			'created' => '2010-12-20 11:00:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 134,
			'parent_id' => 129,
			'name' => 'Enrollment Forms',
			'lft' => 175,
			'rght' => 176,
			'disabled' => 0,
			'created' => '2010-12-20 11:03:02',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 135,
			'parent_id' => 129,
			'name' => 'Original Budget',
			'lft' => 173,
			'rght' => 174,
			'disabled' => 0,
			'created' => '2010-12-20 11:04:04',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 136,
			'parent_id' => 130,
			'name' => 'Customer Monthly Report',
			'lft' => 169,
			'rght' => 170,
			'disabled' => 0,
			'created' => '2010-12-20 11:04:40',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 137,
			'parent_id' => 130,
			'name' => 'Internal/External Communication',
			'lft' => 167,
			'rght' => 168,
			'disabled' => 0,
			'created' => '2010-12-20 11:05:25',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 138,
			'parent_id' => 131,
			'name' => 'Attendance Verification',
			'lft' => 163,
			'rght' => 164,
			'disabled' => 0,
			'created' => '2010-12-20 11:06:09',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 139,
			'parent_id' => 131,
			'name' => 'OJT Time Sheets',
			'lft' => 161,
			'rght' => 162,
			'disabled' => 0,
			'created' => '2010-12-20 11:06:44',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 140,
			'parent_id' => 131,
			'name' => 'Job Search (after enrollment)',
			'lft' => 159,
			'rght' => 160,
			'disabled' => 0,
			'created' => '2010-12-20 11:07:24',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 141,
			'parent_id' => 131,
			'name' => 'Grades/ Transcripts (after enrollment)',
			'lft' => 157,
			'rght' => 158,
			'disabled' => 0,
			'created' => '2010-12-20 11:07:55',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 142,
			'parent_id' => 131,
			'name' => 'Credentials (after enrollment)',
			'lft' => 155,
			'rght' => 156,
			'disabled' => 0,
			'created' => '2010-12-20 11:08:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 143,
			'parent_id' => 131,
			'name' => 'Revised Planning Documents',
			'lft' => 153,
			'rght' => 154,
			'disabled' => 0,
			'created' => '2010-12-20 11:08:51',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 144,
			'parent_id' => 132,
			'name' => 'Budget-Revisions After Original',
			'lft' => 149,
			'rght' => 150,
			'disabled' => 0,
			'created' => '2010-12-20 11:09:35',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 145,
			'parent_id' => 132,
			'name' => 'Support Service Requests With Back Up',
			'lft' => 147,
			'rght' => 148,
			'disabled' => 0,
			'created' => '2010-12-20 11:10:09',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 146,
			'parent_id' => 133,
			'name' => 'Employment',
			'lft' => 133,
			'rght' => 134,
			'disabled' => 0,
			'created' => '2010-12-20 11:10:30',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 147,
			'parent_id' => 35,
			'name' => 'Application and Eligibility',
			'lft' => 392,
			'rght' => 393,
			'disabled' => 0,
			'created' => '2010-12-20 11:10:31',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 148,
			'parent_id' => 35,
			'name' => 'Services',
			'lft' => 390,
			'rght' => 391,
			'disabled' => 0,
			'created' => '2010-12-20 11:10:39',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 149,
			'parent_id' => 35,
			'name' => 'Employment and Follow Up',
			'lft' => 388,
			'rght' => 389,
			'disabled' => 0,
			'created' => '2010-12-20 11:11:07',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 150,
			'parent_id' => 133,
			'name' => 'Resume After Training',
			'lft' => 135,
			'rght' => 136,
			'disabled' => 0,
			'created' => '2010-12-20 11:11:11',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 151,
			'parent_id' => 133,
			'name' => 'Follow-Up Contacts',
			'lft' => 137,
			'rght' => 138,
			'disabled' => 0,
			'created' => '2010-12-20 11:11:36',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 152,
			'parent_id' => 133,
			'name' => 'Maintenance Income EV',
			'lft' => 139,
			'rght' => 140,
			'disabled' => 0,
			'created' => '2010-12-20 11:12:59',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 153,
			'parent_id' => 32,
			'name' => 'Tickets',
			'lft' => 364,
			'rght' => 371,
			'disabled' => 0,
			'created' => '2010-12-20 11:13:22',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 154,
			'parent_id' => 153,
			'name' => 'Employment',
			'lft' => 369,
			'rght' => 370,
			'disabled' => 0,
			'created' => '2010-12-20 11:13:40',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 155,
			'parent_id' => 133,
			'name' => 'No Fault Exit Info- Global Exclusions',
			'lft' => 141,
			'rght' => 142,
			'disabled' => 0,
			'created' => '2010-12-20 11:13:47',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 156,
			'parent_id' => 32,
			'name' => 'Other',
			'lft' => 362,
			'rght' => 363,
			'disabled' => 0,
			'created' => '2010-12-20 11:13:52',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 157,
			'parent_id' => 153,
			'name' => 'Other',
			'lft' => 367,
			'rght' => 368,
			'disabled' => 0,
			'created' => '2010-12-20 11:14:08',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 158,
			'parent_id' => 153,
			'name' => 'IWP',
			'lft' => 365,
			'rght' => 366,
			'disabled' => 0,
			'created' => '2010-12-20 11:14:25',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 159,
			'parent_id' => 31,
			'name' => 'Assessment',
			'lft' => 358,
			'rght' => 359,
			'disabled' => 0,
			'created' => '2010-12-20 11:15:19',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 160,
			'parent_id' => 31,
			'name' => 'Notes',
			'lft' => 356,
			'rght' => 357,
			'disabled' => 0,
			'created' => '2010-12-20 11:16:00',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 161,
			'parent_id' => 30,
			'name' => 'Enrollment Information',
			'lft' => 94,
			'rght' => 101,
			'disabled' => 0,
			'created' => '2010-12-20 11:16:34',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 162,
			'parent_id' => 30,
			'name' => 'Special Conditions',
			'lft' => 72,
			'rght' => 85,
			'disabled' => 0,
			'created' => '2010-12-20 11:17:31',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 163,
			'parent_id' => 30,
			'name' => 'Communiques',
			'lft' => 56,
			'rght' => 71,
			'disabled' => 0,
			'created' => '2010-12-20 11:17:46',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 164,
			'parent_id' => 30,
			'name' => 'Work Activities',
			'lft' => 44,
			'rght' => 55,
			'disabled' => 0,
			'created' => '2010-12-20 11:18:01',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 165,
			'parent_id' => 30,
			'name' => 'Financial',
			'lft' => 36,
			'rght' => 43,
			'disabled' => 0,
			'created' => '2010-12-20 11:18:13',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 167,
			'parent_id' => 30,
			'name' => 'Transitional Services',
			'lft' => 86,
			'rght' => 93,
			'disabled' => 0,
			'created' => '2010-12-20 11:18:59',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 168,
			'parent_id' => 30,
			'name' => 'Archive',
			'lft' => 34,
			'rght' => 35,
			'disabled' => 0,
			'created' => '2010-12-20 11:19:07',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 169,
			'parent_id' => 161,
			'name' => 'Intake/Orientation',
			'lft' => 99,
			'rght' => 100,
			'disabled' => 0,
			'created' => '2010-12-20 11:21:29',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 170,
			'parent_id' => 161,
			'name' => 'Assessment (TABE)',
			'lft' => 97,
			'rght' => 98,
			'disabled' => 0,
			'created' => '2010-12-20 11:21:44',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 171,
			'parent_id' => 161,
			'name' => 'Career Plan Documents',
			'lft' => 95,
			'rght' => 96,
			'disabled' => 0,
			'created' => '2010-12-20 11:21:58',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 172,
			'parent_id' => 162,
			'name' => 'Hardship Extensions',
			'lft' => 79,
			'rght' => 80,
			'disabled' => 0,
			'created' => '2010-12-20 11:22:15',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 173,
			'parent_id' => 162,
			'name' => 'Relocation Documentation',
			'lft' => 77,
			'rght' => 78,
			'disabled' => 0,
			'created' => '2010-12-20 11:22:26',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 174,
			'parent_id' => 28,
			'name' => 'Enrollment',
			'lft' => 406,
			'rght' => 411,
			'disabled' => 0,
			'created' => '2010-12-20 11:23:11',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 175,
			'parent_id' => 162,
			'name' => 'Cash Severance Documentation',
			'lft' => 75,
			'rght' => 76,
			'disabled' => 0,
			'created' => '2010-12-20 11:23:31',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 176,
			'parent_id' => 28,
			'name' => 'Planning',
			'lft' => 398,
			'rght' => 405,
			'disabled' => 0,
			'created' => '2010-12-20 11:23:34',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 177,
			'parent_id' => 162,
			'name' => 'Up Front Diversion Documentation',
			'lft' => 73,
			'rght' => 74,
			'disabled' => 0,
			'created' => '2010-12-20 11:23:51',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 178,
			'parent_id' => 28,
			'name' => 'Communiques',
			'lft' => 412,
			'rght' => 415,
			'disabled' => 0,
			'created' => '2010-12-20 11:24:00',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 179,
			'parent_id' => 162,
			'name' => 'Deferral Documents (Med Verify)',
			'lft' => 81,
			'rght' => 82,
			'disabled' => 0,
			'created' => '2010-12-20 11:24:07',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 180,
			'parent_id' => 28,
			'name' => 'Work Activities',
			'lft' => 416,
			'rght' => 423,
			'disabled' => 0,
			'created' => '2010-12-20 11:24:15',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 181,
			'parent_id' => 28,
			'name' => 'Financial',
			'lft' => 434,
			'rght' => 441,
			'disabled' => 0,
			'created' => '2010-12-20 11:24:27',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 182,
			'parent_id' => 162,
			'name' => 'SSI/SSDI Documentation',
			'lft' => 83,
			'rght' => 84,
			'disabled' => 0,
			'created' => '2010-12-20 11:24:46',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 183,
			'parent_id' => 28,
			'name' => 'Outcome',
			'lft' => 426,
			'rght' => 433,
			'disabled' => 0,
			'created' => '2010-12-20 11:24:46',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 184,
			'parent_id' => 163,
			'name' => 'Appointment Letters',
			'lft' => 67,
			'rght' => 68,
			'disabled' => 0,
			'created' => '2010-12-20 11:25:07',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 185,
			'parent_id' => 28,
			'name' => 'Archive',
			'lft' => 424,
			'rght' => 425,
			'disabled' => 0,
			'created' => '2010-12-20 11:25:13',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 186,
			'parent_id' => 163,
			'name' => 'Pre-Penalty Letters',
			'lft' => 65,
			'rght' => 66,
			'disabled' => 0,
			'created' => '2010-12-20 11:25:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 187,
			'parent_id' => 174,
			'name' => 'Enrollment Forms',
			'lft' => 409,
			'rght' => 410,
			'disabled' => 0,
			'created' => '2010-12-20 11:25:34',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 188,
			'parent_id' => 163,
			'name' => 'Sanction Letters',
			'lft' => 63,
			'rght' => 64,
			'disabled' => 0,
			'created' => '2010-12-20 11:25:34',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 189,
			'parent_id' => 163,
			'name' => 'Child Care Referrals',
			'lft' => 61,
			'rght' => 62,
			'disabled' => 0,
			'created' => '2010-12-20 11:25:51',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 190,
			'parent_id' => 163,
			'name' => 'Community Agency Referrals',
			'lft' => 59,
			'rght' => 60,
			'disabled' => 0,
			'created' => '2010-12-20 11:26:06',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 191,
			'parent_id' => 174,
			'name' => 'Eligibility Documentation',
			'lft' => 407,
			'rght' => 408,
			'disabled' => 0,
			'created' => '2010-12-20 11:26:09',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 192,
			'parent_id' => 163,
			'name' => 'Employment Referrals',
			'lft' => 57,
			'rght' => 58,
			'disabled' => 0,
			'created' => '2010-12-20 11:26:27',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 193,
			'parent_id' => 176,
			'name' => 'Individual Service Strategies',
			'lft' => 403,
			'rght' => 404,
			'disabled' => 0,
			'created' => '2010-12-20 11:26:38',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 194,
			'parent_id' => 163,
			'name' => 'Misc Letters',
			'lft' => 69,
			'rght' => 70,
			'disabled' => 0,
			'created' => '2010-12-20 11:26:40',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 195,
			'parent_id' => 176,
			'name' => 'Younger Youth Goals',
			'lft' => 401,
			'rght' => 402,
			'disabled' => 0,
			'created' => '2010-12-20 11:26:54',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 196,
			'parent_id' => 164,
			'name' => 'Enrollments',
			'lft' => 53,
			'rght' => 54,
			'disabled' => 0,
			'created' => '2010-12-20 11:27:01',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 197,
			'parent_id' => 164,
			'name' => 'Attendance Verification',
			'lft' => 51,
			'rght' => 52,
			'disabled' => 0,
			'created' => '2010-12-20 11:27:15',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 198,
			'parent_id' => 176,
			'name' => 'Assessment',
			'lft' => 399,
			'rght' => 400,
			'disabled' => 0,
			'created' => '2010-12-20 11:27:15',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 199,
			'parent_id' => 164,
			'name' => 'Employment Verification',
			'lft' => 49,
			'rght' => 50,
			'disabled' => 0,
			'created' => '2010-12-20 11:27:32',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 200,
			'parent_id' => 164,
			'name' => 'Pay Stubs',
			'lft' => 47,
			'rght' => 48,
			'disabled' => 0,
			'created' => '2010-12-20 11:27:43',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 201,
			'parent_id' => 178,
			'name' => 'Internal/External Communication',
			'lft' => 413,
			'rght' => 414,
			'disabled' => 0,
			'created' => '2010-12-20 11:27:48',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 202,
			'parent_id' => 164,
			'name' => 'Comm SRV Job Descriptions',
			'lft' => 45,
			'rght' => 46,
			'disabled' => 0,
			'created' => '2010-12-20 11:27:58',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 203,
			'parent_id' => 180,
			'name' => 'School Attendance Records',
			'lft' => 417,
			'rght' => 418,
			'disabled' => 0,
			'created' => '2010-12-20 11:28:20',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 204,
			'parent_id' => 165,
			'name' => 'Budget (OSST, Training, Etc.)',
			'lft' => 37,
			'rght' => 38,
			'disabled' => 0,
			'created' => '2010-12-20 11:28:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 205,
			'parent_id' => 165,
			'name' => 'Training ITA Back Up',
			'lft' => 39,
			'rght' => 40,
			'disabled' => 0,
			'created' => '2010-12-20 11:28:35',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 206,
			'parent_id' => 180,
			'name' => 'Grades/Progress Reports',
			'lft' => 419,
			'rght' => 420,
			'disabled' => 0,
			'created' => '2010-12-20 11:28:41',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 207,
			'parent_id' => 165,
			'name' => 'Ancillary Support Service back-Up',
			'lft' => 41,
			'rght' => 42,
			'disabled' => 0,
			'created' => '2010-12-20 11:28:54',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 208,
			'parent_id' => 180,
			'name' => 'Maintenance Income',
			'lft' => 421,
			'rght' => 422,
			'disabled' => 0,
			'created' => '2010-12-20 11:29:00',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 209,
			'parent_id' => 167,
			'name' => '200% Poverty Level Packet',
			'lft' => 91,
			'rght' => 92,
			'disabled' => 0,
			'created' => '2010-12-20 11:29:17',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 210,
			'parent_id' => 181,
			'name' => 'Training Program',
			'lft' => 439,
			'rght' => 440,
			'disabled' => 0,
			'created' => '2010-12-20 11:29:18',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 211,
			'parent_id' => 167,
			'name' => 'Transitional Letters',
			'lft' => 89,
			'rght' => 90,
			'disabled' => 0,
			'created' => '2010-12-20 11:29:30',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 212,
			'parent_id' => 181,
			'name' => 'Budget',
			'lft' => 437,
			'rght' => 438,
			'disabled' => 0,
			'created' => '2010-12-20 11:29:31',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 213,
			'parent_id' => 181,
			'name' => 'Support Services Requests',
			'lft' => 435,
			'rght' => 436,
			'disabled' => 0,
			'created' => '2010-12-20 11:29:51',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 214,
			'parent_id' => 167,
			'name' => 'Transitional Termination Letter',
			'lft' => 87,
			'rght' => 88,
			'disabled' => 0,
			'created' => '2010-12-20 11:29:56',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 215,
			'parent_id' => 183,
			'name' => 'Credentials Recieved',
			'lft' => 431,
			'rght' => 432,
			'disabled' => 0,
			'created' => '2010-12-20 11:30:12',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 216,
			'parent_id' => 29,
			'name' => 'Archive',
			'lft' => 106,
			'rght' => 107,
			'disabled' => 0,
			'created' => '2010-12-20 11:30:24',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 217,
			'parent_id' => 183,
			'name' => 'Outcome Information',
			'lft' => 429,
			'rght' => 430,
			'disabled' => 0,
			'created' => '2010-12-20 11:30:29',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 218,
			'parent_id' => 29,
			'name' => 'Active',
			'lft' => 104,
			'rght' => 105,
			'disabled' => 0,
			'created' => '2010-12-20 11:30:31',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 219,
			'parent_id' => 183,
			'name' => 'Follow Up',
			'lft' => 427,
			'rght' => 428,
			'disabled' => 0,
			'created' => '2010-12-20 11:30:44',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 220,
			'parent_id' => 2,
			'name' => 'test',
			'lft' => 308,
			'rght' => 309,
			'disabled' => 0,
			'created' => '2010-12-21 11:15:48',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 221,
			'parent_id' => 20,
			'name' => 'test',
			'lft' => 110,
			'rght' => 111,
			'disabled' => 0,
			'created' => '2011-01-10 15:12:18',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 222,
			'parent_id' => NULL,
			'name' => 'test',
			'lft' => 19,
			'rght' => 32,
			'disabled' => 0,
			'created' => '2011-01-10 15:12:39',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 223,
			'parent_id' => NULL,
			'name' => 'State Stuff',
			'lft' => 1,
			'rght' => 2,
			'disabled' => 0,
			'created' => '2011-01-10 15:13:02',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 224,
			'parent_id' => 21,
			'name' => 'test',
			'lft' => 304,
			'rght' => 305,
			'disabled' => 0,
			'created' => '2011-01-10 15:36:14',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 225,
			'parent_id' => 39,
			'name' => 'Test Archive',
			'lft' => 351,
			'rght' => 352,
			'disabled' => 0,
			'created' => '2011-01-10 16:10:50',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 226,
			'parent_id' => 39,
			'name' => 'Test ',
			'lft' => 349,
			'rght' => 350,
			'disabled' => 0,
			'created' => '2011-01-10 16:13:42',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 227,
			'parent_id' => 39,
			'name' => 'Test 2',
			'lft' => 347,
			'rght' => 348,
			'disabled' => 0,
			'created' => '2011-01-10 16:13:52',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 228,
			'parent_id' => 41,
			'name' => 'Test',
			'lft' => 127,
			'rght' => 128,
			'disabled' => 0,
			'created' => '2011-01-11 10:10:55',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 229,
			'parent_id' => 36,
			'name' => 'test',
			'lft' => 384,
			'rght' => 385,
			'disabled' => 0,
			'created' => '2011-01-11 10:25:52',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 230,
			'parent_id' => 222,
			'name' => 'test child',
			'lft' => 20,
			'rght' => 21,
			'disabled' => 0,
			'created' => '2011-01-11 10:56:01',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 231,
			'parent_id' => 44,
			'name' => 'test',
			'lft' => 121,
			'rght' => 122,
			'disabled' => 0,
			'created' => '2011-01-11 11:11:06',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 232,
			'parent_id' => 44,
			'name' => 'test 2',
			'lft' => 119,
			'rght' => 120,
			'disabled' => 0,
			'created' => '2011-01-11 11:12:46',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 233,
			'parent_id' => 25,
			'name' => 'test',
			'lft' => 234,
			'rght' => 235,
			'disabled' => 0,
			'created' => '2011-01-11 12:30:33',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 234,
			'parent_id' => 38,
			'name' => 'From Fax',
			'lft' => 376,
			'rght' => 379,
			'disabled' => 0,
			'created' => '2011-01-11 13:44:31',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 235,
			'parent_id' => NULL,
			'name' => 'test Cat',
			'lft' => 3,
			'rght' => 8,
			'disabled' => 0,
			'created' => '2011-01-11 14:24:18',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 236,
			'parent_id' => 235,
			'name' => 'child cat',
			'lft' => 4,
			'rght' => 5,
			'disabled' => 0,
			'created' => '2011-01-11 14:24:28',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 237,
			'parent_id' => 235,
			'name' => 'test cat 2',
			'lft' => 6,
			'rght' => 7,
			'disabled' => 0,
			'created' => '2011-01-11 14:24:49',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 238,
			'parent_id' => 222,
			'name' => 'test2',
			'lft' => 30,
			'rght' => 31,
			'disabled' => 0,
			'created' => '2011-01-11 16:02:20',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 239,
			'parent_id' => NULL,
			'name' => 'test3',
			'lft' => 17,
			'rght' => 18,
			'disabled' => 0,
			'created' => '2011-01-11 16:02:40',
			'modified' => '2011-05-09 10:05:10'
		),
		array(
			'id' => 240,
			'parent_id' => NULL,
			'name' => 'test root',
			'lft' => 11,
			'rght' => 16,
			'disabled' => 0,
			'created' => '2011-01-12 16:04:54',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 241,
			'parent_id' => 240,
			'name' => 'root child',
			'lft' => 12,
			'rght' => 15,
			'disabled' => 0,
			'created' => '2011-01-12 16:05:07',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 242,
			'parent_id' => 241,
			'name' => 'root grand child',
			'lft' => 13,
			'rght' => 14,
			'disabled' => 0,
			'created' => '2011-01-12 16:05:21',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 243,
			'parent_id' => NULL,
			'name' => 'test another root',
			'lft' => 9,
			'rght' => 10,
			'disabled' => 0,
			'created' => '2011-01-12 16:05:34',
			'modified' => '2011-05-09 09:55:42'
		),
		array(
			'id' => 244,
			'parent_id' => 234,
			'name' => 'Test',
			'lft' => 377,
			'rght' => 378,
			'disabled' => 0,
			'created' => '2011-01-12 16:12:43',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 245,
			'parent_id' => 222,
			'name' => 'test1',
			'lft' => 24,
			'rght' => 29,
			'disabled' => 0,
			'created' => '2011-01-12 21:46:34',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 246,
			'parent_id' => 222,
			'name' => 'test3',
			'lft' => 22,
			'rght' => 23,
			'disabled' => 0,
			'created' => '2011-01-12 21:46:49',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 247,
			'parent_id' => 245,
			'name' => 'test 1-1',
			'lft' => 27,
			'rght' => 28,
			'disabled' => 0,
			'created' => '2011-01-12 21:46:58',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 248,
			'parent_id' => 245,
			'name' => 'test 1-2',
			'lft' => 25,
			'rght' => 26,
			'disabled' => 0,
			'created' => '2011-01-12 21:47:20',
			'modified' => '2011-03-04 16:45:54'
		),
		array(
			'id' => 249,
			'parent_id' => 133,
			'name' => 'Credential',
			'lft' => 143,
			'rght' => 144,
			'disabled' => 0,
			'created' => '2011-03-04 17:09:58',
			'modified' => '2011-03-04 17:11:02'
		),
		array(
			'id' => 250,
			'parent_id' => NULL,
			'name' => 'VPK',
			'lft' => 443,
			'rght' => 456,
			'disabled' => 0,
			'created' => '2011-04-04 19:57:28',
			'modified' => '2011-04-04 19:57:35'
		),
		array(
			'id' => 251,
			'parent_id' => 250,
			'name' => 'Enrollment',
			'lft' => 444,
			'rght' => 455,
			'disabled' => 0,
			'created' => '2011-04-04 19:58:13',
			'modified' => '2011-04-04 19:58:13'
		),
		array(
			'id' => 252,
			'parent_id' => 251,
			'name' => 'Birth Proof',
			'lft' => 445,
			'rght' => 446,
			'disabled' => 0,
			'created' => '2011-04-04 19:58:47',
			'modified' => '2011-04-04 19:58:47'
		),
		array(
			'id' => 253,
			'parent_id' => 251,
			'name' => 'Residency',
			'lft' => 447,
			'rght' => 448,
			'disabled' => 0,
			'created' => '2011-04-04 19:59:00',
			'modified' => '2011-04-07 19:40:56'
		),
		array(
			'id' => 254,
			'parent_id' => 251,
			'name' => 'Rejected',
			'lft' => 449,
			'rght' => 450,
			'disabled' => 0,
			'created' => '2011-04-04 19:59:10',
			'modified' => '2011-04-07 19:49:32'
		),
		array(
			'id' => 255,
			'parent_id' => 251,
			'name' => 'Application',
			'lft' => 451,
			'rght' => 452,
			'disabled' => 0,
			'created' => '2011-04-25 18:18:45',
			'modified' => '2011-04-25 18:18:45'
		),
		array(
			'id' => 256,
			'parent_id' => 251,
			'name' => 'COE',
			'lft' => 453,
			'rght' => 454,
			'disabled' => 0,
			'created' => '2011-04-25 18:18:58',
			'modified' => '2011-04-25 18:18:58'
		),
	);
}
