<?php
/* Aco Fixture generated on: 2011-06-06 16:12:11 : 1307391131 */
class AcoFixture extends CakeTestFixture {
	var $name = 'Aco';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'parent_id' => NULL,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'controllers',
			'lft' => 1,
			'rght' => 844
		),
		array(
			'id' => 2,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Pages',
			'lft' => 2,
			'rght' => 27
		),
		array(
			'id' => 3,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'display',
			'lft' => 3,
			'rght' => 4
		),
		array(
			'id' => 4,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'dynamicDisplay',
			'lft' => 5,
			'rght' => 6
		),
		array(
			'id' => 5,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 7,
			'rght' => 8
		),
		array(
			'id' => 6,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 9,
			'rght' => 10
		),
		array(
			'id' => 7,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 11,
			'rght' => 12
		),
		array(
			'id' => 8,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 13,
			'rght' => 14
		),
		array(
			'id' => 9,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 15,
			'rght' => 16
		),
		array(
			'id' => 10,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 17,
			'rght' => 18
		),
		array(
			'id' => 11,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 19,
			'rght' => 20
		),
		array(
			'id' => 17,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'ChairmanReports',
			'lft' => 28,
			'rght' => 49
		),
		array(
			'id' => 18,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 29,
			'rght' => 30
		),
		array(
			'id' => 19,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 31,
			'rght' => 32
		),
		array(
			'id' => 20,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 33,
			'rght' => 34
		),
		array(
			'id' => 21,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 35,
			'rght' => 36
		),
		array(
			'id' => 22,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 37,
			'rght' => 38
		),
		array(
			'id' => 23,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 39,
			'rght' => 40
		),
		array(
			'id' => 24,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 41,
			'rght' => 42
		),
		array(
			'id' => 25,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 43,
			'rght' => 44
		),
		array(
			'id' => 30,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'DeletedDocuments',
			'lft' => 50,
			'rght' => 67
		),
		array(
			'id' => 31,
			'parent_id' => 30,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 51,
			'rght' => 52
		),
		array(
			'id' => 32,
			'parent_id' => 30,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_view',
			'lft' => 53,
			'rght' => 54
		),
		array(
			'id' => 33,
			'parent_id' => 30,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_restore',
			'lft' => 55,
			'rght' => 56
		),
		array(
			'id' => 34,
			'parent_id' => 30,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 57,
			'rght' => 58
		),
		array(
			'id' => 35,
			'parent_id' => 30,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 59,
			'rght' => 60
		),
		array(
			'id' => 36,
			'parent_id' => 30,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 61,
			'rght' => 62
		),
		array(
			'id' => 42,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'DocumentFilingCategories',
			'lft' => 68,
			'rght' => 97
		),
		array(
			'id' => 43,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 69,
			'rght' => 70
		),
		array(
			'id' => 44,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 71,
			'rght' => 72
		),
		array(
			'id' => 46,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 73,
			'rght' => 74
		),
		array(
			'id' => 50,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 75,
			'rght' => 76
		),
		array(
			'id' => 51,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 77,
			'rght' => 78
		),
		array(
			'id' => 52,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 79,
			'rght' => 80
		),
		array(
			'id' => 58,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'DocumentQueueCategories',
			'lft' => 98,
			'rght' => 117
		),
		array(
			'id' => 59,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 99,
			'rght' => 100
		),
		array(
			'id' => 60,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 101,
			'rght' => 102
		),
		array(
			'id' => 61,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 103,
			'rght' => 104
		),
		array(
			'id' => 62,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 105,
			'rght' => 106
		),
		array(
			'id' => 63,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 107,
			'rght' => 108
		),
		array(
			'id' => 64,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 109,
			'rght' => 110
		),
		array(
			'id' => 65,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 111,
			'rght' => 112
		),
		array(
			'id' => 71,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'FiledDocuments',
			'lft' => 118,
			'rght' => 145
		),
		array(
			'id' => 72,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 119,
			'rght' => 120
		),
		array(
			'id' => 73,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_view',
			'lft' => 121,
			'rght' => 122
		),
		array(
			'id' => 74,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 123,
			'rght' => 124
		),
		array(
			'id' => 75,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 125,
			'rght' => 126
		),
		array(
			'id' => 76,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_upload_document',
			'lft' => 127,
			'rght' => 128
		),
		array(
			'id' => 77,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_scan_document',
			'lft' => 129,
			'rght' => 130
		),
		array(
			'id' => 78,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 131,
			'rght' => 132
		),
		array(
			'id' => 79,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 133,
			'rght' => 134
		),
		array(
			'id' => 80,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 135,
			'rght' => 136
		),
		array(
			'id' => 86,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'FtpDocumentScanners',
			'lft' => 146,
			'rght' => 165
		),
		array(
			'id' => 87,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 147,
			'rght' => 148
		),
		array(
			'id' => 88,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 149,
			'rght' => 150
		),
		array(
			'id' => 89,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 151,
			'rght' => 152
		),
		array(
			'id' => 90,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 153,
			'rght' => 154
		),
		array(
			'id' => 91,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 155,
			'rght' => 156
		),
		array(
			'id' => 92,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 157,
			'rght' => 158
		),
		array(
			'id' => 93,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 159,
			'rght' => 160
		),
		array(
			'id' => 99,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Kiosks',
			'lft' => 166,
			'rght' => 199
		),
		array(
			'id' => 100,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 167,
			'rght' => 168
		),
		array(
			'id' => 101,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 169,
			'rght' => 170
		),
		array(
			'id' => 102,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 171,
			'rght' => 172
		),
		array(
			'id' => 103,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 173,
			'rght' => 174
		),
		array(
			'id' => 111,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 175,
			'rght' => 176
		),
		array(
			'id' => 112,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 177,
			'rght' => 178
		),
		array(
			'id' => 113,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 179,
			'rght' => 180
		),
		array(
			'id' => 119,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'KioskButtons',
			'lft' => 200,
			'rght' => 219
		),
		array(
			'id' => 120,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 201,
			'rght' => 202
		),
		array(
			'id' => 121,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_enable_button',
			'lft' => 203,
			'rght' => 204
		),
		array(
			'id' => 122,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_disable_button',
			'lft' => 205,
			'rght' => 206
		),
		array(
			'id' => 123,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_reorder_buttons_ajax',
			'lft' => 207,
			'rght' => 208
		),
		array(
			'id' => 124,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 209,
			'rght' => 210
		),
		array(
			'id' => 125,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 211,
			'rght' => 212
		),
		array(
			'id' => 126,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 213,
			'rght' => 214
		),
		array(
			'id' => 132,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Locations',
			'lft' => 220,
			'rght' => 245
		),
		array(
			'id' => 133,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 221,
			'rght' => 222
		),
		array(
			'id' => 134,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 223,
			'rght' => 224
		),
		array(
			'id' => 135,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 225,
			'rght' => 226
		),
		array(
			'id' => 136,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 227,
			'rght' => 228
		),
		array(
			'id' => 138,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 229,
			'rght' => 230
		),
		array(
			'id' => 139,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 231,
			'rght' => 232
		),
		array(
			'id' => 140,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 233,
			'rght' => 234
		),
		array(
			'id' => 143,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 235,
			'rght' => 236
		),
		array(
			'id' => 146,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'MasterKioskButtons',
			'lft' => 246,
			'rght' => 265
		),
		array(
			'id' => 147,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 247,
			'rght' => 248
		),
		array(
			'id' => 148,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 249,
			'rght' => 250
		),
		array(
			'id' => 149,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 251,
			'rght' => 252
		),
		array(
			'id' => 150,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 253,
			'rght' => 254
		),
		array(
			'id' => 151,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 255,
			'rght' => 256
		),
		array(
			'id' => 152,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 257,
			'rght' => 258
		),
		array(
			'id' => 153,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 259,
			'rght' => 260
		),
		array(
			'id' => 159,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Navigations',
			'lft' => 266,
			'rght' => 293
		),
		array(
			'id' => 160,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 267,
			'rght' => 268
		),
		array(
			'id' => 161,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_nodes',
			'lft' => 269,
			'rght' => 270
		),
		array(
			'id' => 162,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_reorder',
			'lft' => 271,
			'rght' => 272
		),
		array(
			'id' => 163,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_reparent',
			'lft' => 273,
			'rght' => 274
		),
		array(
			'id' => 164,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_rename_node',
			'lft' => 275,
			'rght' => 276
		),
		array(
			'id' => 165,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete_node',
			'lft' => 277,
			'rght' => 278
		),
		array(
			'id' => 167,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 279,
			'rght' => 280
		),
		array(
			'id' => 168,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 281,
			'rght' => 282
		),
		array(
			'id' => 169,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 283,
			'rght' => 284
		),
		array(
			'id' => 175,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Permissions',
			'lft' => 294,
			'rght' => 311
		),
		array(
			'id' => 176,
			'parent_id' => 175,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 295,
			'rght' => 296
		),
		array(
			'id' => 177,
			'parent_id' => 175,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_set_permissions',
			'lft' => 297,
			'rght' => 298
		),
		array(
			'id' => 178,
			'parent_id' => 175,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete_permissions',
			'lft' => 299,
			'rght' => 300
		),
		array(
			'id' => 179,
			'parent_id' => 175,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 301,
			'rght' => 302
		),
		array(
			'id' => 180,
			'parent_id' => 175,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 303,
			'rght' => 304
		),
		array(
			'id' => 181,
			'parent_id' => 175,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 305,
			'rght' => 306
		),
		array(
			'id' => 187,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'PressReleases',
			'lft' => 312,
			'rght' => 333
		),
		array(
			'id' => 188,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 313,
			'rght' => 314
		),
		array(
			'id' => 189,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 315,
			'rght' => 316
		),
		array(
			'id' => 190,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 317,
			'rght' => 318
		),
		array(
			'id' => 191,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 319,
			'rght' => 320
		),
		array(
			'id' => 192,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 321,
			'rght' => 322
		),
		array(
			'id' => 193,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 323,
			'rght' => 324
		),
		array(
			'id' => 194,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 325,
			'rght' => 326
		),
		array(
			'id' => 195,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 327,
			'rght' => 328
		),
		array(
			'id' => 200,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'QueuedDocuments',
			'lft' => 334,
			'rght' => 359
		),
		array(
			'id' => 201,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 335,
			'rght' => 336
		),
		array(
			'id' => 202,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_reassign_queue',
			'lft' => 337,
			'rght' => 338
		),
		array(
			'id' => 203,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_view',
			'lft' => 339,
			'rght' => 340
		),
		array(
			'id' => 204,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_file_document',
			'lft' => 341,
			'rght' => 342
		),
		array(
			'id' => 205,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 343,
			'rght' => 344
		),
		array(
			'id' => 206,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_desktop_scan_document',
			'lft' => 345,
			'rght' => 346
		),
		array(
			'id' => 207,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 347,
			'rght' => 348
		),
		array(
			'id' => 208,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 349,
			'rght' => 350
		),
		array(
			'id' => 209,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 351,
			'rght' => 352
		),
		array(
			'id' => 215,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Roles',
			'lft' => 360,
			'rght' => 379
		),
		array(
			'id' => 216,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 361,
			'rght' => 362
		),
		array(
			'id' => 217,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 363,
			'rght' => 364
		),
		array(
			'id' => 218,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 365,
			'rght' => 366
		),
		array(
			'id' => 219,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 367,
			'rght' => 368
		),
		array(
			'id' => 220,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 369,
			'rght' => 370
		),
		array(
			'id' => 221,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 371,
			'rght' => 372
		),
		array(
			'id' => 222,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 373,
			'rght' => 374
		),
		array(
			'id' => 228,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'SelfScanCategories',
			'lft' => 380,
			'rght' => 401
		),
		array(
			'id' => 229,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 381,
			'rght' => 382
		),
		array(
			'id' => 230,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_view',
			'lft' => 383,
			'rght' => 384
		),
		array(
			'id' => 231,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 385,
			'rght' => 386
		),
		array(
			'id' => 232,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 387,
			'rght' => 388
		),
		array(
			'id' => 233,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 389,
			'rght' => 390
		),
		array(
			'id' => 234,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 391,
			'rght' => 392
		),
		array(
			'id' => 235,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 393,
			'rght' => 394
		),
		array(
			'id' => 236,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 395,
			'rght' => 396
		),
		array(
			'id' => 242,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'SelfSignLogs',
			'lft' => 402,
			'rght' => 425
		),
		array(
			'id' => 243,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 403,
			'rght' => 404
		),
		array(
			'id' => 244,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_logs_ajax',
			'lft' => 405,
			'rght' => 406
		),
		array(
			'id' => 245,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_logs_ajax_json',
			'lft' => 407,
			'rght' => 408
		),
		array(
			'id' => 246,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_services_ajax',
			'lft' => 409,
			'rght' => 410
		),
		array(
			'id' => 247,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_services_ajax_air',
			'lft' => 411,
			'rght' => 412
		),
		array(
			'id' => 248,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_toggle_status',
			'lft' => 413,
			'rght' => 414
		),
		array(
			'id' => 249,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 415,
			'rght' => 416
		),
		array(
			'id' => 250,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 417,
			'rght' => 418
		),
		array(
			'id' => 251,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 419,
			'rght' => 420
		),
		array(
			'id' => 257,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'SelfSignLogArchives',
			'lft' => 426,
			'rght' => 447
		),
		array(
			'id' => 258,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 427,
			'rght' => 428
		),
		array(
			'id' => 259,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_report',
			'lft' => 429,
			'rght' => 430
		),
		array(
			'id' => 260,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_parent_buttons_ajax',
			'lft' => 431,
			'rght' => 432
		),
		array(
			'id' => 261,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_child_buttons_ajax',
			'lft' => 433,
			'rght' => 434
		),
		array(
			'id' => 262,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_grand_child_buttons_ajax',
			'lft' => 435,
			'rght' => 436
		),
		array(
			'id' => 263,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 437,
			'rght' => 438
		),
		array(
			'id' => 264,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 439,
			'rght' => 440
		),
		array(
			'id' => 265,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 441,
			'rght' => 442
		),
		array(
			'id' => 271,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Users',
			'lft' => 448,
			'rght' => 497
		),
		array(
			'id' => 272,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 449,
			'rght' => 450
		),
		array(
			'id' => 273,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 451,
			'rght' => 452
		),
		array(
			'id' => 274,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 453,
			'rght' => 454
		),
		array(
			'id' => 277,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'logout',
			'lft' => 455,
			'rght' => 456
		),
		array(
			'id' => 280,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_login',
			'lft' => 457,
			'rght' => 458
		),
		array(
			'id' => 281,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_logout',
			'lft' => 459,
			'rght' => 460
		),
		array(
			'id' => 282,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index_admin',
			'lft' => 461,
			'rght' => 462
		),
		array(
			'id' => 283,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add_admin',
			'lft' => 463,
			'rght' => 464
		),
		array(
			'id' => 284,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit_admin',
			'lft' => 465,
			'rght' => 466
		),
		array(
			'id' => 286,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_dashboard',
			'lft' => 467,
			'rght' => 468
		),
		array(
			'id' => 287,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_password_reset',
			'lft' => 469,
			'rght' => 470
		),
		array(
			'id' => 288,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 471,
			'rght' => 472
		),
		array(
			'id' => 289,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 473,
			'rght' => 474
		),
		array(
			'id' => 290,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 475,
			'rght' => 476
		),
		array(
			'id' => 296,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'UserTransactions',
			'lft' => 498,
			'rght' => 511
		),
		array(
			'id' => 297,
			'parent_id' => 296,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 499,
			'rght' => 500
		),
		array(
			'id' => 298,
			'parent_id' => 296,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 501,
			'rght' => 502
		),
		array(
			'id' => 299,
			'parent_id' => 296,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 503,
			'rght' => 504
		),
		array(
			'id' => 300,
			'parent_id' => 296,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 505,
			'rght' => 506
		),
		array(
			'id' => 318,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_resolve_login_issues',
			'lft' => 477,
			'rght' => 478
		),
		array(
			'id' => 319,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_admin_list',
			'lft' => 479,
			'rght' => 480
		),
		array(
			'id' => 320,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_request_ssn_change',
			'lft' => 481,
			'rght' => 482
		),
		array(
			'id' => 321,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_reparent_categories',
			'lft' => 81,
			'rght' => 82
		),
		array(
			'id' => 323,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_toggle_disabled',
			'lft' => 83,
			'rght' => 84
		),
		array(
			'id' => 324,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'HotJobs',
			'lft' => 512,
			'rght' => 535
		),
		array(
			'id' => 325,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 513,
			'rght' => 514
		),
		array(
			'id' => 326,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 515,
			'rght' => 516
		),
		array(
			'id' => 327,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 517,
			'rght' => 518
		),
		array(
			'id' => 328,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 519,
			'rght' => 520
		),
		array(
			'id' => 329,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 521,
			'rght' => 522
		),
		array(
			'id' => 330,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'apply',
			'lft' => 523,
			'rght' => 524
		),
		array(
			'id' => 331,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 525,
			'rght' => 526
		),
		array(
			'id' => 332,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 527,
			'rght' => 528
		),
		array(
			'id' => 333,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 529,
			'rght' => 530
		),
		array(
			'id' => 338,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Rfps',
			'lft' => 536,
			'rght' => 561
		),
		array(
			'id' => 339,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 537,
			'rght' => 538
		),
		array(
			'id' => 340,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 539,
			'rght' => 540
		),
		array(
			'id' => 344,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 541,
			'rght' => 542
		),
		array(
			'id' => 345,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 543,
			'rght' => 544
		),
		array(
			'id' => 346,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 545,
			'rght' => 546
		),
		array(
			'id' => 351,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_short_list',
			'lft' => 21,
			'rght' => 22
		),
		array(
			'id' => 352,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_reorder_categories',
			'lft' => 85,
			'rght' => 86
		),
		array(
			'id' => 353,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_child_cats',
			'lft' => 87,
			'rght' => 88
		),
		array(
			'id' => 354,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_grand_child_cats',
			'lft' => 89,
			'rght' => 90
		),
		array(
			'id' => 355,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'FeaturedEmployers',
			'lft' => 562,
			'rght' => 583
		),
		array(
			'id' => 356,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 563,
			'rght' => 564
		),
		array(
			'id' => 360,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 565,
			'rght' => 566
		),
		array(
			'id' => 361,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 567,
			'rght' => 568
		),
		array(
			'id' => 362,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 569,
			'rght' => 570
		),
		array(
			'id' => 363,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 571,
			'rght' => 572
		),
		array(
			'id' => 364,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 573,
			'rght' => 574
		),
		array(
			'id' => 365,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 575,
			'rght' => 576
		),
		array(
			'id' => 366,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 577,
			'rght' => 578
		),
		array(
			'id' => 368,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'HelpfulArticles',
			'lft' => 584,
			'rght' => 605
		),
		array(
			'id' => 369,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 585,
			'rght' => 586
		),
		array(
			'id' => 370,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 587,
			'rght' => 588
		),
		array(
			'id' => 371,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 589,
			'rght' => 590
		),
		array(
			'id' => 372,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 591,
			'rght' => 592
		),
		array(
			'id' => 373,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 593,
			'rght' => 594
		),
		array(
			'id' => 374,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 595,
			'rght' => 596
		),
		array(
			'id' => 375,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 597,
			'rght' => 598
		),
		array(
			'id' => 376,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 599,
			'rght' => 600
		),
		array(
			'id' => 381,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'InTheNews',
			'lft' => 606,
			'rght' => 627
		),
		array(
			'id' => 382,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 607,
			'rght' => 608
		),
		array(
			'id' => 383,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 609,
			'rght' => 610
		),
		array(
			'id' => 384,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 611,
			'rght' => 612
		),
		array(
			'id' => 385,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 613,
			'rght' => 614
		),
		array(
			'id' => 386,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 615,
			'rght' => 616
		),
		array(
			'id' => 387,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 617,
			'rght' => 618
		),
		array(
			'id' => 388,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 619,
			'rght' => 620
		),
		array(
			'id' => 389,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 621,
			'rght' => 622
		),
		array(
			'id' => 394,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_update',
			'lft' => 285,
			'rght' => 286
		),
		array(
			'id' => 395,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_create',
			'lft' => 287,
			'rght' => 288
		),
		array(
			'id' => 396,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_create',
			'lft' => 547,
			'rght' => 548
		),
		array(
			'id' => 397,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_read',
			'lft' => 549,
			'rght' => 550
		),
		array(
			'id' => 398,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_update',
			'lft' => 551,
			'rght' => 552
		),
		array(
			'id' => 399,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_destroy',
			'lft' => 553,
			'rght' => 554
		),
		array(
			'id' => 400,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_upload',
			'lft' => 555,
			'rght' => 556
		),
		array(
			'id' => 401,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_view_all_docs',
			'lft' => 137,
			'rght' => 138
		),
		array(
			'id' => 402,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_cats',
			'lft' => 91,
			'rght' => 92
		),
		array(
			'id' => 403,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_report',
			'lft' => 139,
			'rght' => 140
		),
		array(
			'id' => 404,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_location_list',
			'lft' => 237,
			'rght' => 238
		),
		array(
			'id' => 405,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'facilities',
			'lft' => 239,
			'rght' => 240
		),
		array(
			'id' => 406,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_view_thumbnail',
			'lft' => 353,
			'rght' => 354
		),
		array(
			'id' => 416,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 23,
			'rght' => 24
		),
		array(
			'id' => 417,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 45,
			'rght' => 46
		),
		array(
			'id' => 418,
			'parent_id' => 30,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 63,
			'rght' => 64
		),
		array(
			'id' => 419,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 93,
			'rght' => 94
		),
		array(
			'id' => 420,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 113,
			'rght' => 114
		),
		array(
			'id' => 421,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Events',
			'lft' => 628,
			'rght' => 653
		),
		array(
			'id' => 422,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'view',
			'lft' => 629,
			'rght' => 630
		),
		array(
			'id' => 423,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 631,
			'rght' => 632
		),
		array(
			'id' => 424,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_create',
			'lft' => 633,
			'rght' => 634
		),
		array(
			'id' => 425,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_read',
			'lft' => 635,
			'rght' => 636
		),
		array(
			'id' => 426,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_update',
			'lft' => 637,
			'rght' => 638
		),
		array(
			'id' => 427,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_destroy',
			'lft' => 639,
			'rght' => 640
		),
		array(
			'id' => 428,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 641,
			'rght' => 642
		),
		array(
			'id' => 429,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 643,
			'rght' => 644
		),
		array(
			'id' => 430,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 645,
			'rght' => 646
		),
		array(
			'id' => 431,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 647,
			'rght' => 648
		),
		array(
			'id' => 434,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 649,
			'rght' => 650
		),
		array(
			'id' => 436,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'EventCategories',
			'lft' => 654,
			'rght' => 675
		),
		array(
			'id' => 441,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_get_all_categories',
			'lft' => 655,
			'rght' => 656
		),
		array(
			'id' => 442,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 657,
			'rght' => 658
		),
		array(
			'id' => 443,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 659,
			'rght' => 660
		),
		array(
			'id' => 444,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 661,
			'rght' => 662
		),
		array(
			'id' => 445,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 663,
			'rght' => 664
		),
		array(
			'id' => 451,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 579,
			'rght' => 580
		),
		array(
			'id' => 452,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 141,
			'rght' => 142
		),
		array(
			'id' => 453,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 161,
			'rght' => 162
		),
		array(
			'id' => 454,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 601,
			'rght' => 602
		),
		array(
			'id' => 455,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 531,
			'rght' => 532
		),
		array(
			'id' => 456,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 623,
			'rght' => 624
		),
		array(
			'id' => 457,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 181,
			'rght' => 182
		),
		array(
			'id' => 458,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 215,
			'rght' => 216
		),
		array(
			'id' => 459,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 241,
			'rght' => 242
		),
		array(
			'id' => 460,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 261,
			'rght' => 262
		),
		array(
			'id' => 461,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 289,
			'rght' => 290
		),
		array(
			'id' => 462,
			'parent_id' => 175,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 307,
			'rght' => 308
		),
		array(
			'id' => 463,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 329,
			'rght' => 330
		),
		array(
			'id' => 464,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 355,
			'rght' => 356
		),
		array(
			'id' => 465,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 557,
			'rght' => 558
		),
		array(
			'id' => 466,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 375,
			'rght' => 376
		),
		array(
			'id' => 467,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 397,
			'rght' => 398
		),
		array(
			'id' => 468,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 421,
			'rght' => 422
		),
		array(
			'id' => 469,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 443,
			'rght' => 444
		),
		array(
			'id' => 470,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 483,
			'rght' => 484
		),
		array(
			'id' => 471,
			'parent_id' => 296,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 507,
			'rght' => 508
		),
		array(
			'id' => 494,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'CareerSeekersSurveys',
			'lft' => 676,
			'rght' => 699
		),
		array(
			'id' => 495,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 677,
			'rght' => 678
		),
		array(
			'id' => 496,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'add',
			'lft' => 679,
			'rght' => 680
		),
		array(
			'id' => 497,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'success',
			'lft' => 681,
			'rght' => 682
		),
		array(
			'id' => 498,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 683,
			'rght' => 684
		),
		array(
			'id' => 499,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_read',
			'lft' => 685,
			'rght' => 686
		),
		array(
			'id' => 500,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_destroy',
			'lft' => 687,
			'rght' => 688
		),
		array(
			'id' => 501,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 689,
			'rght' => 690
		),
		array(
			'id' => 502,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 691,
			'rght' => 692
		),
		array(
			'id' => 503,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 693,
			'rght' => 694
		),
		array(
			'id' => 504,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 695,
			'rght' => 696
		),
		array(
			'id' => 508,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'EmployersSurveys',
			'lft' => 700,
			'rght' => 723
		),
		array(
			'id' => 509,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 701,
			'rght' => 702
		),
		array(
			'id' => 510,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'add',
			'lft' => 703,
			'rght' => 704
		),
		array(
			'id' => 511,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'success',
			'lft' => 705,
			'rght' => 706
		),
		array(
			'id' => 512,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 707,
			'rght' => 708
		),
		array(
			'id' => 513,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_read',
			'lft' => 709,
			'rght' => 710
		),
		array(
			'id' => 514,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_destroy',
			'lft' => 711,
			'rght' => 712
		),
		array(
			'id' => 515,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 713,
			'rght' => 714
		),
		array(
			'id' => 516,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 715,
			'rght' => 716
		),
		array(
			'id' => 517,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 717,
			'rght' => 718
		),
		array(
			'id' => 518,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 719,
			'rght' => 720
		),
		array(
			'id' => 613,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_create',
			'lft' => 665,
			'rght' => 666
		),
		array(
			'id' => 614,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_read',
			'lft' => 667,
			'rght' => 668
		),
		array(
			'id' => 615,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_update',
			'lft' => 669,
			'rght' => 670
		),
		array(
			'id' => 616,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_destroy',
			'lft' => 671,
			'rght' => 672
		),
		array(
			'id' => 966,
			'parent_id' => 159,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 291,
			'rght' => 292
		),
		array(
			'id' => 967,
			'parent_id' => 338,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 559,
			'rght' => 560
		),
		array(
			'id' => 968,
			'parent_id' => 175,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 309,
			'rght' => 310
		),
		array(
			'id' => 631,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'ProgramFields',
			'lft' => 724,
			'rght' => 745
		),
		array(
			'id' => 632,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 725,
			'rght' => 726
		),
		array(
			'id' => 633,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_create',
			'lft' => 727,
			'rght' => 728
		),
		array(
			'id' => 634,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_read',
			'lft' => 729,
			'rght' => 730
		),
		array(
			'id' => 635,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_update',
			'lft' => 731,
			'rght' => 732
		),
		array(
			'id' => 636,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_destroy',
			'lft' => 733,
			'rght' => 734
		),
		array(
			'id' => 637,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 735,
			'rght' => 736
		),
		array(
			'id' => 638,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 737,
			'rght' => 738
		),
		array(
			'id' => 639,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 739,
			'rght' => 740
		),
		array(
			'id' => 640,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 741,
			'rght' => 742
		),
		array(
			'id' => 962,
			'parent_id' => 508,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 721,
			'rght' => 722
		),
		array(
			'id' => 963,
			'parent_id' => 355,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 581,
			'rght' => 582
		),
		array(
			'id' => 964,
			'parent_id' => 324,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 533,
			'rght' => 534
		),
		array(
			'id' => 965,
			'parent_id' => 494,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 697,
			'rght' => 698
		),
		array(
			'id' => 650,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'AclExtras',
			'lft' => 746,
			'rght' => 747
		),
		array(
			'id' => 651,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'DebugKit',
			'lft' => 748,
			'rght' => 765
		),
		array(
			'id' => 652,
			'parent_id' => 651,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'ToolbarAccess',
			'lft' => 749,
			'rght' => 764
		),
		array(
			'id' => 653,
			'parent_id' => 652,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'history_state',
			'lft' => 750,
			'rght' => 751
		),
		array(
			'id' => 654,
			'parent_id' => 652,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'sql_explain',
			'lft' => 752,
			'rght' => 753
		),
		array(
			'id' => 655,
			'parent_id' => 652,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 754,
			'rght' => 755
		),
		array(
			'id' => 656,
			'parent_id' => 652,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 756,
			'rght' => 757
		),
		array(
			'id' => 657,
			'parent_id' => 652,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 758,
			'rght' => 759
		),
		array(
			'id' => 658,
			'parent_id' => 652,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 760,
			'rght' => 761
		),
		array(
			'id' => 986,
			'parent_id' => 228,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 399,
			'rght' => 400
		),
		array(
			'id' => 987,
			'parent_id' => 86,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 163,
			'rght' => 164
		),
		array(
			'id' => 988,
			'parent_id' => 30,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 65,
			'rght' => 66
		),
		array(
			'id' => 946,
			'parent_id' => 2,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 25,
			'rght' => 26
		),
		array(
			'id' => 947,
			'parent_id' => 58,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 115,
			'rght' => 116
		),
		array(
			'id' => 948,
			'parent_id' => 215,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 377,
			'rght' => 378
		),
		array(
			'id' => 949,
			'parent_id' => 421,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 651,
			'rght' => 652
		),
		array(
			'id' => 950,
			'parent_id' => 187,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 331,
			'rght' => 332
		),
		array(
			'id' => 951,
			'parent_id' => 132,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 243,
			'rght' => 244
		),
		array(
			'id' => 952,
			'parent_id' => 368,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 603,
			'rght' => 604
		),
		array(
			'id' => 953,
			'parent_id' => 257,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 445,
			'rght' => 446
		),
		array(
			'id' => 954,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_self_sign_confirm',
			'lft' => 183,
			'rght' => 184
		),
		array(
			'id' => 955,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_self_sign_edit',
			'lft' => 185,
			'rght' => 186
		),
		array(
			'id' => 956,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_self_sign_service_selection',
			'lft' => 187,
			'rght' => 188
		),
		array(
			'id' => 957,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_self_sign_other',
			'lft' => 189,
			'rght' => 190
		),
		array(
			'id' => 958,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_self_scan_program_selection',
			'lft' => 191,
			'rght' => 192
		),
		array(
			'id' => 959,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_self_scan_document',
			'lft' => 193,
			'rght' => 194
		),
		array(
			'id' => 960,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_self_scan_another_document',
			'lft' => 195,
			'rght' => 196
		),
		array(
			'id' => 961,
			'parent_id' => 99,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 197,
			'rght' => 198
		),
		array(
			'id' => 969,
			'parent_id' => 42,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 95,
			'rght' => 96
		),
		array(
			'id' => 970,
			'parent_id' => 631,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 743,
			'rght' => 744
		),
		array(
			'id' => 971,
			'parent_id' => 200,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 357,
			'rght' => 358
		),
		array(
			'id' => 972,
			'parent_id' => 381,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 625,
			'rght' => 626
		),
		array(
			'id' => 973,
			'parent_id' => 71,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 143,
			'rght' => 144
		),
		array(
			'id' => 974,
			'parent_id' => 17,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 47,
			'rght' => 48
		),
		array(
			'id' => 975,
			'parent_id' => 146,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 263,
			'rght' => 264
		),
		array(
			'id' => 976,
			'parent_id' => 296,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 509,
			'rght' => 510
		),
		array(
			'id' => 977,
			'parent_id' => 119,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 217,
			'rght' => 218
		),
		array(
			'id' => 978,
			'parent_id' => 436,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 673,
			'rght' => 674
		),
		array(
			'id' => 979,
			'parent_id' => 242,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 423,
			'rght' => 424
		),
		array(
			'id' => 980,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_self_sign_login',
			'lft' => 485,
			'rght' => 486
		),
		array(
			'id' => 981,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'login',
			'lft' => 487,
			'rght' => 488
		),
		array(
			'id' => 982,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'registration',
			'lft' => 489,
			'rght' => 490
		),
		array(
			'id' => 983,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_auto_logout',
			'lft' => 491,
			'rght' => 492
		),
		array(
			'id' => 984,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'kiosk_mini_registration',
			'lft' => 493,
			'rght' => 494
		),
		array(
			'id' => 985,
			'parent_id' => 271,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 495,
			'rght' => 496
		),
		array(
			'id' => 989,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'ProgramResponses',
			'lft' => 766,
			'rght' => 797
		),
		array(
			'id' => 990,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 767,
			'rght' => 768
		),
		array(
			'id' => 991,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'required_docs',
			'lft' => 769,
			'rght' => 770
		),
		array(
			'id' => 992,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'response_complete',
			'lft' => 771,
			'rght' => 772
		),
		array(
			'id' => 993,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'view_cert',
			'lft' => 773,
			'rght' => 774
		),
		array(
			'id' => 994,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'provided_docs',
			'lft' => 775,
			'rght' => 776
		),
		array(
			'id' => 995,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 777,
			'rght' => 778
		),
		array(
			'id' => 996,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_view',
			'lft' => 779,
			'rght' => 780
		),
		array(
			'id' => 997,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_approve',
			'lft' => 781,
			'rght' => 782
		),
		array(
			'id' => 998,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_generate_form',
			'lft' => 783,
			'rght' => 784
		),
		array(
			'id' => 999,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_toggle_expired',
			'lft' => 785,
			'rght' => 786
		),
		array(
			'id' => 1000,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 787,
			'rght' => 788
		),
		array(
			'id' => 1001,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 789,
			'rght' => 790
		),
		array(
			'id' => 1002,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 791,
			'rght' => 792
		),
		array(
			'id' => 1003,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 793,
			'rght' => 794
		),
		array(
			'id' => 1004,
			'parent_id' => 989,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 795,
			'rght' => 796
		),
		array(
			'id' => 1005,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'ProgramEmails',
			'lft' => 798,
			'rght' => 817
		),
		array(
			'id' => 1006,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 799,
			'rght' => 800
		),
		array(
			'id' => 1007,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_add',
			'lft' => 801,
			'rght' => 802
		),
		array(
			'id' => 1008,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit',
			'lft' => 803,
			'rght' => 804
		),
		array(
			'id' => 1009,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_delete',
			'lft' => 805,
			'rght' => 806
		),
		array(
			'id' => 1010,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 807,
			'rght' => 808
		),
		array(
			'id' => 1011,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 809,
			'rght' => 810
		),
		array(
			'id' => 1012,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 811,
			'rght' => 812
		),
		array(
			'id' => 1013,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 813,
			'rght' => 814
		),
		array(
			'id' => 1014,
			'parent_id' => 1005,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 815,
			'rght' => 816
		),
		array(
			'id' => 1015,
			'parent_id' => 1,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'Programs',
			'lft' => 818,
			'rght' => 843
		),
		array(
			'id' => 1016,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'index',
			'lft' => 819,
			'rght' => 820
		),
		array(
			'id' => 1017,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'get_started',
			'lft' => 821,
			'rght' => 822
		),
		array(
			'id' => 1018,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'view_media',
			'lft' => 823,
			'rght' => 824
		),
		array(
			'id' => 1019,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'load_media',
			'lft' => 825,
			'rght' => 826
		),
		array(
			'id' => 1020,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_index',
			'lft' => 827,
			'rght' => 828
		),
		array(
			'id' => 1021,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_instructions_index',
			'lft' => 829,
			'rght' => 830
		),
		array(
			'id' => 1022,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_edit_instructions',
			'lft' => 831,
			'rght' => 832
		),
		array(
			'id' => 1023,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'forceSSL',
			'lft' => 833,
			'rght' => 834
		),
		array(
			'id' => 1024,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 835,
			'rght' => 836
		),
		array(
			'id' => 1025,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_first_ajax',
			'lft' => 837,
			'rght' => 838
		),
		array(
			'id' => 1026,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_last_ajax',
			'lft' => 839,
			'rght' => 840
		),
		array(
			'id' => 1027,
			'parent_id' => 1015,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'admin_auto_complete_ssn_ajax',
			'lft' => 841,
			'rght' => 842
		),
		array(
			'id' => 1028,
			'parent_id' => 652,
			'model' => NULL,
			'foreign_key' => NULL,
			'alias' => 'isModuleEnabled',
			'lft' => 762,
			'rght' => 763
		),
	);
}
