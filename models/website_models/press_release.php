<?php
class PressRelease extends AppModel {
	var $name = 'PressRelease';
	var $displayField = 'title';

        var $validate = array(
            'title' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Title cannot be left blank'
                ),
                'alphaNumericPlusSpaces' => array(
                    'rule' => '/^[a-zA-Z0-9\s]+$/',
                    'message' => 'Title must only contain letters, numbers, or spaces'
                )
            ),
            'file' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'You must include a file'
                )
            )
        );
}
?>