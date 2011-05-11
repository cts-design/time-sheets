<?php
class MultivalidatableBehavior extends ModelBehavior {

    /**
     * Stores previous validation ruleset
     *
     * @var Array
     */
    var $__oldRules = array();

    /**
     * Stores Model default validation ruleset
     *
     * @var unknown_type
     */
    var $__defaultRules = array();

    function setUp(&$Model, $config = array()) {
        $this->__defaultRules[$Model->alias] = $Model->validate;
    }

    /**
     * Installs a new validation ruleset
     *
     * If $rules is an array, it will be set as current validation ruleset,
     * otherwise it will look into Model::validationSets[$rules] for the ruleset to install
     *
     * @param Object $Model
     * @param Mixed $rules
     */
    function setValidation(&$Model, $rules = array()) {
        if (is_array($rules)){
            $this->_setValidation($Model, $rules);
        } elseif (isset($Model->validationSets[$rules])) {
            $this->setValidation($Model, $Model->validationSets[$rules]);
        }
    }

    /**
     * Restores previous validation ruleset
     *
     * @param Object $Model
     */
    function restoreValidation(&$Model) {
        $Model->validate = $this->__oldRules[$Model->alias];
    }

    /**
     * Restores default validation ruleset
     *
     * @param Object $Model
     */
    function restoreDefaultValidation(&$Model) {
        $Model->validate = $this->__defaultRules[$Model->alias];
    }

    /**
     * Sets a new validation ruleset, saving the previous
     *
     * @param Object $Model
     * @param Array $rules
     */
    function _setValidation(&$Model, $rules) {
            $this->__oldRules[$Model->alias] = $Model->validate;
            $Model->validate = $rules;
    }

} 