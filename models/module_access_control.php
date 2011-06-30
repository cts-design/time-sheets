<?php

class ModuleAccessControl extends Model {

    function checkPermissions($controllerName){
        $record = $this->find('first', array('conditions' => array('name' => Inflector::camelize($controllerName))));
        if($record['ModuleAccessControl']['permission'] == 1 ) {
            return true;
        }
        else return false;
    }
}
