<?php
/**
 * Atlas Translate Behavior
 *
 * Adds a fix to the __getLocale method so it adheres to
 * the language set in the session, as well as the configuration
 *
 * Note: This can be removed when they put the fix into the core
 *
 * @author Brandon Cordell
 * @copyright 2011, Complete Technology Solutions
 * @version 0.1
 */
App::import('Behavior', 'Translate');

class AtlasTranslateBehavior extends TranslateBehavior {
/**
 * Get selected locale for model
 *
 * @param Model $model Model the locale needs to be set/get on.
 * @return mixed string or false
 * @access protected
 */
	function _getLocale(&$model) {
		if (!isset($model->locale) || is_null($model->locale)) {
			if (!class_exists('I18n')) {
				App::import('Core', 'i18n');
			}
			
			if (!class_exists('SessionComponent')) {
				App::import('Component', 'Session');
			}
			
			$I18n =& I18n::getInstance();
			$Session = new SessionComponent();
			
			// debug($Session->read('Config'));die();
			
			if ($Session->read('Config.language')) {
				$language = $Session->read('Config.language');
			} else {
				$language = Configure::read('Config.language');
			}
			
			//debug($language);
			
			$I18n->l10n->get($language);
			$model->locale = $I18n->l10n->locale;
		}
		//debug($model->locale);die;
		return $model->locale;
	}
}

