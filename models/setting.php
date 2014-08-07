<?php
class Setting extends AppModel {
	var $name = 'Setting';
	var $displayField = 'name';
	var $validate = array(
		'module' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'value' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		)
	);

	public function getEmails() {
		$setting = $this->find('first', array(
			'conditions' => array(
				'module' => 'Email',
				'name' => 'cc'
			)
		));

		$emails = explode(',', $setting['Setting']['value']);
		for($i = 0; $i < count($emails); $i++)
		{
			$emails[$i] = trim($emails[$i]);
		}

		return $emails;
	}
}
?>