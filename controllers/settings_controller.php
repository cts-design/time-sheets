<?php
class SettingsController extends AppController {

	public $name = 'Settings';
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('admin_esign_options');
		$this->Auth->allow('admin_translation', 'admin_translation_mode');
	}

	public function admin_index() {

		if($this->RequestHandler->isAjax())
		{
			$this->autoRender = false;
			if( isset($this->params['pass'][0]) && isset($this->params['pass'][1]) && isset($_GET['action']))
			{
				$module 	= $this->params['pass'][0];
				$name 		= $this->params['pass'][1];
				$action 	= $_GET['action'];

				switch($action)
				{
					case 'set':

						if( isset($_GET['value']) )
						{
							$value = $_GET['value'];
						}
						else
						{
							$this->ajaxMessage(FALSE, 'Value is not set');
						}

						$setting = $this->Setting->find('first', array(
							'conditions' => array(
								'module' => $module,
								'name' => $name,
							)
						));

						if($setting)
						{
							$setting['Setting']['value'] = $value;
						}
						else
						{
							$setting['Setting'] = array(
								'module' => $module,
								'name' => $name,
								'value' => $value
							);
						}

						$is_saved = $this->Setting->save($setting);

						if($is_saved)
						{
							$this->ajaxMessage(TRUE, 'Setting has been saved');
						}
						else
						{
							$this->ajaxMessage(FALSE, 'Could not save Setting');
						}

						break;
					case 'get':

						$setting = $this->Setting->find('first', array(
							'conditions' => array(
								'module' => $module,
								'name' => $name,
							)
						));
						if($setting)
						{
							$this->ajaxMessage(TRUE, $setting);
						}
						else
						{
							$this->ajaxMessage(FALSE, 'No Module-Name Combo');
						}
						break;
				}
			}
			else
			{
				$this->ajaxMessage(FALSE, 'Not all Parameters set');
			}
		}
		else
		{
			$this->Setting->recursive = 0;
			$this->set('settings', $this->paginate());
		}
	}

	public function admin_translation_mode()
	{
		if($this->RequestHandler->isAjax())
		{
			$settings = $this->Setting->findByName('Translation', array('id','value'));
			$fields = array();
			if(isset($this->params['form']['fields']) && $this->params['form']['fields'] != '')
			{
				$arr = explode(',', $this->params['form']['fields']);
				$i = 0;

				foreach ($arr as $key => $value)
				{
					$fields[$i]['field'] = $value;
					$i++;
				}

				if($settings)
				{
					$this->data['Setting']['id'] = $settings['Setting']['id'];
				}			
				
				$this->data['Setting']['value'] = json_encode($fields);
				$this->data['Setting']['module'] = 'SelfSign';
				$this->data['Setting']['name'] = 'KioskRegistration';				
				
				if($this->Setting->save($this->data))
				{
					Cache::delete('settings');
					$data['success'] = true;
					$data['message'] = 'Kiosk registration settings updated successfully';
					$settings = $this->Setting->findByName('KioskRegistration', array('id','value'));
				}
				else
				{
					$data['success'] = false;
					$data['message'] = 'Kiosk registration settings could not be updated';					
				}				
			}
			else
			{
				$data['fields'] = json_decode($settings['Setting']['value'], true);
			}
			$this->set(compact('data'));	
			$this->render(null, null, '/elements/ajaxreturn');	
		}
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
				if($settings) {
					$this->data['Setting']['id'] = $settings['Setting']['id'];
				}			
				
				$this->data['Setting']['value'] = json_encode($fields);
				$this->data['Setting']['module'] = 'SelfSign';
				$this->data['Setting']['name'] = 'KioskRegistration';				
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

	public function admin_kiosk_survey() {
		$setting = $this->Setting->find('first', array(
			'conditions' => array(
				'module' => 'Kiosk',
				'name' => 'Survey',
			)
		));

		$data['kiosk_survey']['value'] = $setting['Setting']['value'];

		$setting['Setting']['value'] = $this->params['form']['kiosk_survey'];

		$this->Setting->save($setting);
		Cache::delete('settings');

		$data = array(
			'success' => TRUE,
			'message' => 'Kiosk Survey has been saved'
		);

		$this->set(compact('data'));
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_kiosk_survey_expiration() {
		$data 		= array();
		$date 		= array($this->params['form']['numeric'], $this->params['form']['label']);
		$setting 	= $this->Setting->find('first', array(
			'conditions' => array(
				'module' => 'Kiosk',
				'name' => 'SurveyExpiration',
			)
		));

		if(isset($this->params['form']['numeric']) && isset($this->params['form']['label'])) {

			if($setting) {
				$setting['Setting']['value'] = json_encode($date);
			} else {
				$this->Setting->create();
				$setting = array(
					'module' 	=> 'Kiosk',
					'name' 		=> 'SurveyExpiration',
					'value' 	=> json_encode($date)
				);
			}

			$this->Setting->save($setting);
			Cache::delete('settings');

			$data = array(
				'success' => TRUE,
				'message' => 'Kiosk Survey Expiration date has been saved'
			);
		}
		else
		{
			$value = json_decode($setting['Setting']['value']);
			$data['numeric'][0]['value'] = $value[0];
			$data['label'][0]['value'] = $value[1];
		}		

		$this->set(compact('data'));
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_kiosk_confirmation() {
		if($this->RequestHandler->isAjax()) {
			$settings = $this->Setting->findByName('KioskConfirmation', array('id','value'));
			if(isset($this->params['form']['confirmation']) && $this->params['form']['confirmation'] != '') {
				$this->data['Setting']['value'] = $this->params['form']['confirmation'];
				if($settings) {
					$this->data['Setting']['id'] = $settings['Setting']['id'];
				}	
				$this->data['Setting']['module'] = 'SelfSign';
				$this->data['Setting']['name'] = 'KioskConfirmation';				
				if($this->Setting->save($this->data)) {
					Cache::delete('settings');
					$data['success'] = true;
					$data['message'] = 'Kiosk customer info confirmation settings updated successfully';
					$settings = $this->Setting->findByName('KioskConfirmation', array('id','value'));
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Kiosk customer info confirmation settings could not be updated';					
				}				
			}
			else {
				$data['confirmation'][0]['value'] = $settings['Setting']['value'];
			}
			$this->set(compact('data'));	
			$this->render(null, null, '/elements/ajaxreturn');	
		}
	}
	
	public function admin_kiosk_time_out() {
		if($this->RequestHandler->isAjax()) {
			$settings = $this->Setting->findByName('KioskTimeOut', array('id','value'));
			if(isset($this->params['form']['timeout']) && $this->params['form']['timeout'] != '') {
				// convert seconds to milliseconds
				$values[0]['value'] = $this->params['form']['timeout'] * 1000;
				$values[1]['value'] = $this->params['form']['reminder'] * 1000;

				$this->data['Setting']['value'] = json_encode($values);
				if($settings) {
					$this->data['Setting']['id'] = $settings['Setting']['id'];
				}	
				$this->data['Setting']['module'] = 'SelfSign';
				$this->data['Setting']['name'] = 'KioskTimeOut';				
				if($this->Setting->save($this->data)) {
					Cache::delete('settings');
					$data['success'] = true;
					$data['message'] = 'Kiosk time out settings updated successfully';
					$settings = $this->Setting->findByName('KioskTimeOut', array('id','value'));
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Kiosk time out settings could not be updated';					
				}				
			}
			else {
				$data['timeout'] = json_decode($settings['Setting']['value'], true);
				foreach($data['timeout'] as $k => $v) {
					//convert milliseconds to seconds
					$data['timeout'][$k]['value'] = $v['value'] / 1000;
				}
			}
			$this->set(compact('data'));	
			$this->render(null, null, '/elements/ajaxreturn');	
		}
	}

	public function admin_login_text() {
		if($this->RequestHandler->isAjax()) {
			$settings = $this->Setting->findByName('LoginAdditionalText', array('id','value'));
			if(!empty($this->params['form'])) {
				foreach($this->params['form'] as $k => $v) {
					$values[]['value'] = $v;
				}
				if($settings) {
					$this->data['Setting']['id'] = $settings['Setting']['id'];
				}	
				$this->data['Setting']['value'] = json_encode($values);
				$this->data['Setting']['module'] = 'Users';
				$this->data['Setting']['name'] = 'LoginAdditionalText';				
				if($this->Setting->save($this->data)) {
					Cache::delete('settings');
					$data['success'] = true;
					$data['message'] = 'Login additional settings updated successfully';
					$settings = $this->Setting->findByName('KioskConfirmation', array('id','value'));
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Login additional settings could not be updated';					
				}				
			}
			else {
				$data['login_additional_text'] = json_decode($settings['Setting']['value'], true);
			}
			$this->set(compact('data'));	
			$this->render(null, null, '/elements/ajaxreturn');	
		}
	}

	public function admin_emailcc($action = 'get')
	{
		$this->autoRender = FALSE;

		if($this->RequestHandler->isAjax())
		{

			if($action == 'get')
			{
				$setting = $this->Settings->find('first', array(
					'conditions' => array(
						'module' => 'Email',
						'name' => 'CC'
					)
				));

				$this->ajaxMessage(TRUE, $setting['Settings']);
			}
			else if($action == 'set')
			{
				$data = $this->params['form'];
				$this->ajaxMessage(TRUE, '{}');
			}
		}
	}

	public function admin_esign_options()
	{
		$this->autoRender = FALSE;
		$value = $_GET['value'];

		$esign_option = $this->Setting->findByName('EsignOption');

		$this->Setting->create();
		if($esign_option === FALSE)
		{
			$now = date('Y/m/d H:i:s');
			$settings = array(
				'module' => 'Esign',
				'name' => 'EsignOption',
				'created' => $now,
				'modified' => $now,
				'value' => $value
			);

			$created = $this->Setting->save($settings);
		}
		else
		{
			$this->Setting->id = $esign_option['Setting']['id'];
			$this->Setting->saveField('value', $value);
		}

		$message = array(
			'output' => 'Updated the version type',
			'success' => TRUE
		);

		echo json_encode($message);
	}

	public function admin_survey_required() {
		if($this->RequestHandler->isAjax()) {
			$settings = $this->Setting->findByName('Survey', array('id','value'));
			

			if(!empty($this->params['form'])) {

				if($settings) {
					$this->data['Setting']['id'] = $settings['Setting']['id'];
				}	
				$this->data['Setting']['value'] = $this->params['form']['kiosk_survey'];
				$this->data['Setting']['module'] = 'Kiosk';
				$this->data['Setting']['name'] = 'Survey';

				$this->Setting->save($this->data);

				$data['success'] = true;
				$data['message'] = 'The survey prompt setting was saved';
			} else {
				$data['success'] = true;
				$data['message'] = '';
			}

			$data['kiosk_survey'][0]['value'] = $settings['Setting']['value'];

			$this->set(compact('data'));	
			$this->render(null, null, '/elements/ajaxreturn');	
		}
	}

	public function admin_survey_numeric() {
		$settings = $this->Setting->findByName('SurveyExpiration', array('id','value'));
		if(!empty($this->params['form'])) {
			foreach($this->params['form'] as $k => $v) {
				$values[]['value'] = $v;
			}
			if($settings) {
				$this->data['Setting']['id'] = $settings['Setting']['id'];
			}	
			$this->data['Setting']['value'] = json_encode($values);
			$this->data['Setting']['module'] = 'Survey';
			$this->data['Setting']['name'] = 'SurveyExpiration';				
			if($this->Setting->save($this->data)) {
				Cache::delete('settings');
				$data['success'] = true;
				$data['message'] = 'Survey expiration was saved correctly';
				$settings = $this->Setting->findByName('SurveyExpiration', array('id','value'));
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Survey expiration was not saved correctly';					
			}				
		}
		else {			
			$data['survey_expiration'] = json_decode($settings['Setting']['value'], true);
		}
		$this->set(compact('data'));	
		$this->render(null, null, '/elements/ajaxreturn');	
	}

	public function admin_ask_once() {
		$settings = $this->Setting->findByName('KioskAskOnce', array('id','value'));
		if(!empty($this->params['form'])) {
			foreach($this->params['form'] as $k => $v) {
				$values[]['value'] = $v;
			}
			if($settings) {
				$this->data['Setting']['id'] = $settings['Setting']['id'];
			}	
			$this->data['Setting']['value'] = json_encode($values);
			$this->data['Setting']['module'] = 'Survey';
			$this->data['Setting']['name'] = 'KioskAskOnce';				
			if($this->Setting->save($this->data)) {
				Cache::delete('settings');
				$data['success'] = true;
				$data['message'] = 'Survey ask once was saved correctly';
				$settings = $this->Setting->findByName('KioskAskOnce', array('id','value'));
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Survey ask once was not saved correctly';					
			}				
		}
		else {			
			$data['survey_ask_once'] = json_decode($settings['Setting']['value'], true);
		}
		$this->set(compact('data'));	
		$this->render(null, null, '/elements/ajaxreturn');	
	}

}
?>
