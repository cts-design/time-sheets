<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<?php

echo $session->flash('auth');
echo $form->create('User', array('action' => 'login'));
echo $form->input('username', array('label' => __('Last Name', true)));
echo $form->input('dob');
echo $form->input('password', array('label' => __('Last 4 Digits of Your SSN', true)));
echo $form->end('Login');
?>

