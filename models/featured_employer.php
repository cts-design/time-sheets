<?php
class FeaturedEmployer extends AppModel {
	var $name = 'FeaturedEmployer';
	var $displayField = 'name';
	var $actsAs = array(
		'Translatable' => array(
			'description'
		),
		'AtlasTranslate' => array(
			'description'
		)
	);
}
?>