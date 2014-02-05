<?php
class RfpsController extends AppController {

	var $name = 'Rfps';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	function index() {
        $this->paginate = array(
            'order' => array(
                'Rfp.created' => 'desc'
            )
        );

		$this->Rfp->recursive = 0;
		$this->set('rfps', $this->paginate());
	}

	function admin_index() {
		$this->Rfp->recursive = 0;
		$this->set('rfps', $this->paginate());
	}

	function admin_create() {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($this->params)) {
				$this->data['Rfp'] = json_decode($this->params['form']['rfps'], true);
				
				if (!empty($this->data['Rfp']['deadline'])) {
					$deadline = $this->data['Rfp']['deadline'];
					FireCake::log($deadline);
					$this->data['Rfp']['deadline'] = date('Y-m-d H-i-s', strtotime($deadline));
				}
				
				if (!empty($this->data['Rfp']['expires'])) {
					$expires  = $this->data['Rfp']['expires'];
					FireCake::log($expires);
					$this->data['Rfp']['expires']  = date('Y-m-d H-i-s', strtotime($expires));
				}

				$this->Rfp->create();
				if ($this->Rfp->save($this->data)) {
					$id = $this->Rfp->getLastInsertId();
					$rfp = $this->Rfp->read(null, $id);
					$this->Transaction->createUserTransaction('Rfp', null, null,
                                        'Created rfp ID ' . $this->Rfp->id);
					$rfp['Rfp']['deadline'] = date('m/d/Y', strtotime($rfp['Rfp']['deadline']));
					$rfp['Rfp']['expires'] = date('m/d/Y', strtotime($rfp['Rfp']['expires']));
					$data['rfps'][] = $rfp['Rfp'];
					$data['success'] = true;
				} else {
					$data['success'] = false;
				}
				$this->set(compact('data'));
			}
			
			return $this->render(null, null, '/elements/ajaxreturn');
		} else {
			FireCake::log($this->params);
			exit;
		}
	}
	
	function admin_read() {
		if ($this->RequestHandler->isAjax()) {
			$rfps = array();
			$allRfps = $this->Rfp->find('all');
			
			if (!$allRfps) {
				$rfps['success'] = false;
			} else {
				$i = 0;
				foreach ($allRfps as $rfp) {
					$rfp['Rfp']['deadline'] = date('m/d/Y', strtotime($rfp['Rfp']['deadline']));
					$rfp['Rfp']['expires'] = date('m/d/Y', strtotime($rfp['Rfp']['expires']));
					$rfps['rfps'][$i] = $rfp['Rfp'];
					$i++;
				}
				
				$rfps['success'] = true;
				$rfps['total'] = count($rfps);
			}
			
			$this->set('data', $rfps);
			$this->render(null, null, '/elements/ajaxreturn');
		}		
	}
	
	function admin_update() {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($this->params)) {
				$this->data['Rfp'] = json_decode($this->params['form']['rfps'], true);
				
				if (!empty($this->data['Rfp']['deadline'])) {
					$deadline = $this->data['Rfp']['deadline'];
					$this->data['Rfp']['deadline'] = date('Y-m-d H-i-s', strtotime($deadline));
				}
				
				if (!empty($this->data['Rfp']['expires'])) {
					$expires  = $this->data['Rfp']['expires'];
					$this->data['Rfp']['expires']  = date('Y-m-d H-i-s', strtotime($expires));
				}
				
				if ($this->Rfp->save($this->data)) {
					$this->Transaction->createUserTransaction('Rfp', null, null,
                                        'Updated rfp ID ' . $this->Rfp->id);
					$rfp = $this->Rfp->read(null, $this->data['Rfp']['id']);
					$rfp['Rfp']['deadline'] = date('m/d/Y', strtotime($rfp['Rfp']['deadline']));
					$rfp['Rfp']['expires'] = date('m/d/Y', strtotime($rfp['Rfp']['expires']));
					$data['rfps'] = $rfp['Rfp'];
					$data['success'] = true;
				} else {
					$data['success'] = false;
				}
				$this->set(compact('data'));
			}
			
			return $this->render(null, null, '/elements/ajaxreturn');
		} else {
			exit;
		}		
	}
	
	function admin_destroy() {
		if ($this->RequestHandler->isAjax()) {
			$rfpId = json_decode($this->params['form']['rfps'], true);

			if ($this->Rfp->delete($rfpId)) {
				$this->Transaction->createUserTransaction('Rfp', null, null,
                                        'Deleted rfp ID ' . $this->Rfp->id);
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}
			
			$this->set('data', $data);
			return $this->render(null, null, '/elements/ajaxreturn');
		} else {
			exit;
		}
	}
	
	function admin_upload() {
		// although this function doesnt get called from ajax,
		// we need to return our data without a layout so that
		// ext file upload doesnt break
		$this->layout = 'ajax';
		
		// get the document relative path to the inital storage folder
	    $abs_path = WWW_ROOT . 'files/public/rfps/';
	    $rel_path = 'files/public/rfps/';
	    $file_ext = '';
	    $filename = '';
		
	    switch($_FILES['file']['type']) {
	        case 'application/pdf':
	            $file_ext = '.pdf';
	            break;
	        case 'application/msword':
	            $file_ext = '.doc';
	            break;
	        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
	            $file_ext = '.docx';
	            break;
	    }
	
	    $filename = date('YmdHis') . $file_ext;
	
	    // check to see if the directory exists
	    if (!is_dir($abs_path)) {
	        mkdir($abs_path);
	    }
	
	    if (!file_exists($abs_path . $filename)) {
	        $full_url = $abs_path . $filename;
	        $url = $rel_path . $filename;
	
	        if (!move_uploaded_file($_FILES['file']['tmp_name'], $url)) {
	            $data['success'] = false;
			} else {
				$data['success'] = true;
				$data['url'] = $url;
			}
	    }
	
		$this->set(compact('data'));
		return $this->render(null, null, '/elements/ajaxreturn');
	}
}
?>
