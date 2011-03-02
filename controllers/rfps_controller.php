<?php
App::import('Vendor', 'DebugKit.FireCake');
class RfpsController extends AppController {

	var $name = 'Rfps';

	function index() {
		$this->Rfp->recursive = 0;
		$this->set('rfps', $this->paginate());
	}

	function admin_index() {
		$this->Rfp->recursive = 0;
		$this->set('rfps', $this->paginate());
	}

	function admin_create() {
		FireCake::log($_FILES, 'in create');
		if ($this->RequestHandler->isAjax()) {
			if (!empty($this->params)) {
				$this->data['Rfp'] = json_decode($this->params['form']['rfps'], true);
				
				$deadline = $this->data['Rfp']['deadline'];
				$expires  = $this->data['Rfp']['expires'];
				$this->data['Rfp']['deadline'] = date('Y-m-d H-i-s', strtotime($deadline));
				$this->data['Rfp']['expires']  = date('Y-m-d H-i-s', strtotime($expires));
				
				$this->Rfp->create();
				if ($this->Rfp->save($this->data)) {
					$id = $this->Rfp->getLastInsertId();
					$rfp = $this->Rfp->read(null, $id);
					$rfp['Rfp']['deadline'] = date('m/d/Y', strtotime($rfp['Rfp']['deadline']));
					$rfp['Rfp']['expires'] = date('m/d/Y', strtotime($rfp['Rfp']['expires']));
					$data['rfps'][] = $rfp['Rfp'];
					$data['success'] = true;
				} else {
					$data['success'] = false;
				}
				$this->set(compact('data'));
			}
			
			$this->render(null, null, '/elements/ajaxreturn');
		} else {
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
				
				$deadline = $this->data['Rfp']['deadline'];
				$expires  = $this->data['Rfp']['expires'];
				$this->data['Rfp']['deadline'] = date('Y-m-d H-i-s', strtotime($deadline));
				$this->data['Rfp']['expires']  = date('Y-m-d H-i-s', strtotime($expires));
				
				if ($this->Rfp->save($this->data)) {
					$rfp = $this->Rfp->read(null, $this->data['Rfp']['id']);
					$rfp['Rfp']['deadline'] = date('m/d/Y', strtotime($rfp['Rfp']['deadline']));
					$rfp['Rfp']['expires'] = date('m/d/Y', strtotime($rfp['Rfp']['expires']));
					$data['rfps'][] = $rfp['Rfp'];
					$data['success'] = true;
				} else {
					$data['success'] = false;
				}
				$this->set(compact('data'));
			}
			
			$this->render(null, null, '/elements/ajaxreturn');
		} else {
			exit;
		}		
	}
	
	function admin_destroy() {
		if ($this->RequestHandler->isAjax()) {
			$rfpId = json_decode($this->params['form']['rfps'], true);

			if ($this->Rfp->delete($rfpId)) {
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}
			
			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
		} else {
			exit;
		}
	}
	
	function admin_upload() {
		FireCake::log($_FILES);

		// get the document relative path to the inital storage folder
        $abs_path = WWW_ROOT . 'files/public/rfps/';
        $rel_path = 'files/public/rfps/';
        $file_ext = '';
        $filename = '';
		
		FireCake::log('sss all good');
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
            	FireCake::log('failing');
                $data['success'] = false;
			} else {
				FireCake::log('didnt fail');		
				$data['success'] = true;
				$data['url'] = $url;
			}
        }
		
		$this->header('Content-type: text/html');
		$this->render(null, null, '/elements/ajaxreturn');
	}
}
?>