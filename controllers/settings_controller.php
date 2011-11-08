<?php
class SettingsController extends AppController {

	public $name = 'Settings';
	
	public function admin_index() {
		$this->Setting->recursive = 0;
		$this->set('settings', $this->paginate());
	}
	
	public function admin_kiosk_registration() {
		if($this->RequestHandler->isAjax()) {
			$settings = $this->Setting->findByName('KioskRegistration', array('id','value'));
			$fields = array();
			if(isset($this->params['form']['fields']) && $this->params['form']['fields'] != '') {
				$arr = explode(',', $this->params['form']['fields']);
				$i = 0;
				foreach ($arr as $key => $value) {
					$fields[$i]['field'] = $value;
					$i++;
				}			
				$this->data['Setting']['id'] = $settings['Setting']['id'];
				$this->data['Setting']['value'] = json_encode($fields);			
				if($this->Setting->save($this->data)) {
					Cache::delete('settings');
					$data['success'] = true;
					$data['message'] = 'Kiosk registration settings updated successfully';
					$settings = $this->Setting->findByName('KioskRegistration', array('id','value'));
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Kiosk registration settings could not be updated';					
				}				
			}
			else {
				$data['fields'] = json_decode($settings['Setting']['value'], true);
			}
			$this->set(compact('data'));	
			$this->render(null, null, '/elements/ajaxreturn');	
		}
	}
}
?>