<?php
/* I18n Fixture generated on: 2011-08-02 15:56:21 : 1312314981 */
class I18nFixture extends CakeTestFixture {
	var $name = 'I18n';
	var $table = 'i18n';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'locale' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 6, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'field' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'content' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'locale' => array('column' => 'locale', 'unique' => 0), 'model' => array('column' => 'model', 'unique' => 0), 'row_id' => array('column' => 'foreign_key', 'unique' => 0), 'field' => array('column' => 'field', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'locale' => 'en_us',
			'model' => 'Event',
			'foreign_key' => 1,
			'field' => 'title',
			'content' => 'Board Meeting'
		),
		array(
			'id' => 2,
			'locale' => 'es_es',
			'model' => 'Event',
			'foreign_key' => 1,
			'field' => 'title',
			'content' => 'Reunión de la Junta'
		),
		array(
			'id' => 3,
			'locale' => 'en_us',
			'model' => 'Event',
			'foreign_key' => 1,
			'field' => 'description',
			'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat gravida sapien id accumsan. Donec ultricies est et enim consectetur cursus. Morbi metus leo, lobortis a aliquam vel, accumsan ut velit.'
		),
		array(
			'id' => 4,
			'locale' => 'es_es',
			'model' => 'Event',
			'foreign_key' => 1,
			'field' => 'description',
			'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat gesta sapiens Identificación accumsan. Donec ultricies est enim et consectetur cursus. Morbi metus leo, lobortis un aliquam vel, accumsan ut velita.'
		),
		array(
			'id' => 5,
			'locale' => 'en_us',
			'model' => 'Event',
			'foreign_key' => 2,
			'field' => 'title',
			'content' => 'TBWA Career Fair'
		),
		array(
			'id' => 6,
			'locale' => 'es_es',
			'model' => 'Event',
			'foreign_key' => 2,
			'field' => 'title',
			'content' => 'TBWA Feria de Carreras'
		),
		array(
			'id' => 7,
			'locale' => 'en_us',
			'model' => 'Event',
			'foreign_key' => 2,
			'field' => 'description',
			'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat gravida sapien id accumsan. Donec ultricies est et enim consectetur cursus. Morbi metus leo, lobortis a aliquam vel, accumsan ut velit.'
		),
		array(
			'id' => 8,
			'locale' => 'es_es',
			'model' => 'Event',
			'foreign_key' => 2,
			'field' => 'description',
			'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat gesta sapiens Identificación accumsan. Donec ultricies est enim et consectetur cursus. Morbi metus leo, lobortis un aliquam vel, accumsan ut velita.'
		),
		array(
			'id' => 9,
			'locale' => 'en_us',
			'model' => 'Event',
			'foreign_key' => 3,
			'field' => 'title',
			'content' => 'Workforce Meeting'
		),
		array(
			'id' => 10,
			'locale' => 'es_es',
			'model' => 'Event',
			'foreign_key' => 3,
			'field' => 'title',
			'content' => 'Fuerza de trabajo de reuniones'
		),
		array(
			'id' => 11,
			'locale' => 'en_us',
			'model' => 'MasterKioskButton',
			'foreign_key' => 14,
			'field' => 'name',
			'content' => 'Orientations'
		),	
		array(
			'id' => 13,
			'locale' => 'en_us',
			'model' => 'MasterKioskButton',
			'foreign_key' => 1,
			'field' => 'name',
			'content' => 'Cash Assistance & Food Stamps'
		),
		array(
			'id' => 15,
			'locale' => 'en_us',
			'model' => 'MasterKioskButton',
			'foreign_key' => 10,
			'field' => 'name',
			'content' => 'Veteran Services'
		),
		array(
			'id' => 17,
			'locale' => 'en_us',
			'model' => 'MasterKioskButton',
			'foreign_key' => 45,
			'field' => 'name',
			'content' => 'Scan Documents'
		),
		array(
			'id' => 19,
			'locale' => 'en_us',
			'model' => 'MasterKioskButton',
			'foreign_key' => 6,
			'field' => 'name',
			'content' => 'Look For A Job'
		),
		array(
			'id' => 21,
			'locale' => 'en_us',
			'model' => 'MasterKioskButton',
			'foreign_key' => 73,
			'field' => 'name',
			'content' => 'Register To Win A Kindle'
		),
		array(
			'id' => 23,
			'locale' => 'en_us',
			'model' => 'MasterKioskButton',
			'foreign_key' => 26,
			'field' => 'name',
			'content' => 'Cash Assistance (WTP)'
		)		
	);
}
?>