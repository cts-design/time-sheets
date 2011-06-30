<?php
class ModuleAccessControlsController extends AppController {

	var $name = 'ModuleAccessControls';
    var $blackList = array('App', 'Locations', 'ModuleAccessControls', 'Permissions', 'ReleaseNotes', 'Roles', 'Users');

	function admin_index() {
        $title_for_layout = 'Module Access Control';
		$this->set(compact('modules', 'title_for_layout'));
    }

    function admin_read() {
		$this->ModuleAccessControl->recursive = 0;
        $moduleAccessControl = $this->ModuleAccessControl->find('all');

        // Retreive and sort a list of all the controllers in the application
        $modules = Set::sort(Configure::listObjects('controller'), '{n}', 'asc');
        $data = array();

        foreach ($modules as $key => $value) {
            if (in_array($value, $this->blackList)) {
                // Remove the permanent modules
                unset($modules[$key]);
                continue;
            }

            $checkExistsInMac = Set::extract("/ModuleAccessControl[name={$value}]", $moduleAccessControl);
            if ($checkExistsInMac) {
                if ($checkExistsInMac[0]['ModuleAccessControl']['permission'] == 1) {
                    $checked = false;
                } else {
                    $checked = true;
                }
            } else {
                $checked = true;
            }

            $data[] = array(
                'text' => Inflector::humanize($value),
                'leaf'  => true,
                'checked' => $checked
            );
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }

    function admin_update() {
        $params = $this->params['form'];
        $module = $this->ModuleAccessControl->findByName($params['module']);

        if (!$module && $params['state'] == 1) {
            $this->ModuleAccessControl->create();
            $this->data = array(
                'ModuleAccessControl' => array(
                    'name' => $params['module'],
                    'permission' => 1
                )
            );

            if ($this->ModuleAccessControl->save($this->data)) {
                $data['success'] = true;
            } else {
                $data['success'] = false;
            }
        } else if ($module) {
            $this->data = array(
                'ModuleAccessControl' => array(
                    'id' => $module['ModuleAccessControl']['id'],
                    'permission' => $params['state']
                )
            );

            if ($this->ModuleAccessControl->save($this->data)) {
                $data['success'] = true;
            } else {
                $data['success'] = false;
            }
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
    }
}

