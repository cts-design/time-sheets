<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2012
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class PluginPermissionsHelper extends AppHelper {
    public $helpers = array('Form');
    private $viewVars = null;

    public function beforeRender() {
        $this->viewVars = ClassRegistry::getObject('view')->viewVars;
    }

    public function buildFieldset($type) {
        $existingPermissions = $this->viewVars['controllers'];
        $pluginPermissions = $this->viewVars['pluginPermissions'];
        $output = '';

        if (!$pluginPermissions || !isset($pluginPermissions[$type])) return false;

        foreach ($pluginPermissions[$type] as $plugin => $pluginControllers) {
            foreach ($pluginControllers as $pluginController => $pluginActions) {
                $pluginTitle = Inflector::humanize($pluginController);
                $output .= '<fieldset class="left right-mar-10">';
                $output .= "<legend>$pluginTitle</legend>";

                foreach ($pluginActions as $pluginAction) {
                    $controllerName = Inflector::camelize($pluginController);
                    $actionName = Inflector::humanize(substr($pluginAction, 6));

                    $output .= $this->Form->input("$controllerName.$pluginAction", array(
                        'type' => 'checkbox',
                        'label' => $actionName,
                        'checked' => (isset($existingPermissions[$controllerName][$pluginAction])) ? $existingPermissions[$controllerName][$pluginAction] : ''
                    ));
                }

                $output .= '</fieldset>';
            }
        }

        return $output;
    }
}

