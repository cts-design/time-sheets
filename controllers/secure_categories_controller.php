<?php
class SecureCategoriesController extends AppController {

	var $name = 'SecureCategories';
	
	var $uses = array();
	

	function admin_index() {
		
	}

	function admin_get_secure_filing_cats() {
		if($this->RequestHandler->isAjax()) {
			$this->loadModel('DocumentFilingCategory');
			$cats = $this->DocumentFilingCategory->find('all', 
				array('conditions' => array('DocumentFilingCategory.secure' => 1)));
			$data['cats'] = array();
			if($cats) {
				$i = 0;
				foreach($cats as $cat) {
					$data['cats'][$i]['id'] = $cat['DocumentFilingCategory']['id'];
					$data['cats'][$i]['name'] = $cat['DocumentFilingCategory']['name'];
					$data['cats'][$i]['user'] = $cat['DocumentFilingCategory']['users'];
					$i++;
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

}