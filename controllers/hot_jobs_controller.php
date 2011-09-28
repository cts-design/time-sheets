<?php
class HotJobsController extends AppController {

	var $name = 'HotJobs';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index', 'apply');
	}

	function index() {
		$this->HotJob->recursive = 0;
		$this->set('hotJobs', $this->paginate());
	}

	function admin_index() {
		$this->HotJob->recursive = 0;
		$this->set('hotJobs', $this->paginate());
	}

  function admin_create() {
		if ($this->RequestHandler->isAjax()) {
		  FireCake::log($this->params);
			if (!empty($this->params)) {
				$this->data['HotJob'] = json_decode($this->params['form']['hot_jobs'], true);

				$this->HotJob->create();
				if ($this->HotJob->save($this->data)) {
					$id = $this->HotJob->getLastInsertId();
					$rfp = $this->HotJob->read(null, $id);
					$this->Transaction->createUserTransaction('HotJob', null, null,
                                        'Created rfp ID ' . $this->HotJob->id);

					$data['hot_jobs'][] = $rfp['HotJob'];
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
			$hot_jobs = array();
			$allHotJobs = $this->HotJob->find('all');
			
			if (!$allHotJobs) {
				$hot_jobs['success'] = false;
			} else {
				$i = 0;
				foreach ($allHotJobs as $rfp) {
					$hot_jobs['hot_jobs'][$i] = $rfp['HotJob'];
					$i++;
				}
				
				$hot_jobs['success'] = true;
				$hot_jobs['total'] = count($hot_jobs);
			}
			
			$this->set('data', $hot_jobs);
			$this->render(null, null, '/elements/ajaxreturn');
		}		
	}
	
	function admin_update() {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($this->params)) {
				$this->data['HotJob'] = json_decode($this->params['form']['hot_jobs'], true);
				
				if ($this->HotJob->save($this->data)) {
					$this->Transaction->createUserTransaction('HotJob', null, null,
                                        'Updated rfp ID ' . $this->HotJob->id);
					$rfp = $this->HotJob->read(null, $this->data['HotJob']['id']);
					$data['hot_jobs'] = $rfp['HotJob'];
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
			$rfpId = json_decode($this->params['form']['hot_jobs'], true);

			if ($this->HotJob->delete($rfpId)) {
				$this->Transaction->createUserTransaction('HotJob', null, null,
                                        'Deleted rfp ID ' . $this->HotJob->id);
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
	    $abs_path = WWW_ROOT . 'files/public/hot_jobs/';
	    $rel_path = 'files/public/hot_jobs/';
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
	
	function apply($jobId) {
		if (!$jobId) {
			$this->Session->setFlash(__('No job specified', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		
		$this->HotJob->recursive = 0;
		$job = $this->HotJob->read(null, $jobId);
		
		if (!$job) {
			$this->Session->setFlash(__('We couldn\'t find the job you were looking for', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		
		$this->set(compact('job'));
	}
}
?>