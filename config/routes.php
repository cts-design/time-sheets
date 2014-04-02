<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

	Router::connect(
		'/kiosk/survey/cancel',
		array(
			'controller' => 'kiosk_surveys',
			'action' => 'cancel'
		)
	);
	Router::connect(
		'/kiosk/survey/:survey_id',
		array(
			'controller' => 'kiosk_surveys',
			'action'	 => 'start'
		)
	);


    Router::connect(
        '/kiosk/survey/:survey_id/question/:question_number',
        array(
            'controller' => 'kiosk_survey_questions',
            'action'     => 'question'
        )
    );
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
        Router::connect('/404', array('controller' => 'pages', 'action' => 'display', '404'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'dynamicDisplay'));

	Router::connect('/admin', array('controller' => 'users', 'action' => 'login', 'admin' => true));
	
	/*
	if(Configure::read('Kiosk.login_type') == 'id_card') {
		Router::connect('/kiosk', array('controller' => 'users', 'action' => 'id_card_login', 'kiosk' => true));
	}
	else {
		Router::connect('/kiosk', array('controller' => 'users', 'action' => 'self_sign_login', 'kiosk' => true));
	}*/
	Router::connect('/kiosk', array('controller' => 'users', 'action' => 'sign_in_redirect', 'kiosk' => true));

	Router::connect('/mobile', array('controller' => 'mobile_link', 'action' => 'upload'));


