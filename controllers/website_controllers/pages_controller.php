<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
 
 App::import('Vendor', 'DebugKit.FireCake');
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'Pages';

/**
 * Default helper
 *
 * @var array
 * @access public
 */
	var $helpers = array('Html', 'Session');

        function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('dynamicDisplay');
        }

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}

/**
 * Takes a request and checks the database for a matching slug
 * and renders the content with dynamic_display.ctp
 * or renders a 404 if no page is found
 *
 * @param none
 * @access public
 */
	function dynamicDisplay() {
		if (!empty($this->params['requested'])) {
			// requested is only set if this page was retreived
			// using requestAction. In our case this means that
			// the request originated from the homepage element
			return $this->Page->findPublishedBySlug($this->params['pass'][0]);
		} else {
			// can't explode $this->params['url'] because its an array
			$request = explode('/', $this->params['url']['url']);
			$slug = 0;
			if(isset($request[1])) {
				$slug = $request[1];
			}
			$page = $this->Page->findPublishedBySlug($slug);

			if (!$page) {
				// throw the custom 404 error
				$this->cakeError('error404');
			}
			else {
				// if the page requires authentication and the user is not logged in
				if ($page['Page']['authentication_required'] == '1' && !$this->Auth->user()) {
					$this->Session->write('Auth.redirect', '/' . $this->params['url']['url']);
					$this->redirect(array('controller' => 'users', 'action' => 'login'));
				} else {
					if ($page['Page']['landing_page']) {
						$title_for_layout = $page['Page']['title'];

						$subpages = $this->Page->find('all', array(
							'conditions' => array(
								'Page.parent_id' => $page['Page']['id'],
								'Page.published' => '1'
							)
						));

						$this->set(compact('page', 'subpages', 'title_for_layout'));
						$this->render('landing_page');
					} else {
						$data = array(
							'title_for_layout' => $page['Page']['title'],
							'content' => $page['Page']['content']
						);
						$this->set($data);
					}
				}
			}
		}
	}

	function admin_index() {
		if ($this->RequestHandler->isAjax()) {
			$this->Page->recursive = 0;
			$pages = $this->Page->find('all');

			if ($pages) {
				foreach ($pages as $page) {
					$data['pages'][] = $page['Page'];
					$data['success'] = true;
				}
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}
	}

	public function admin_add() {
		$landingPages = $this->Page->findLandingPages();

		if (!empty($this->data)) {
			if (isset($this->data['Page']['image_url'])) {
				if ($this->data['Page']['image_url']['error'] === 0) {
					$this->uploadPageImage();
				} elseif ($this->data['Page']['image_url']['error'] === 4) {
					unset($this->data['Page']['image_url']);
				}
			}

			$this->Page->create();

			if ($this->Page->save($this->data)) {
				$this->Transaction->createUserTransaction('CMS', null, null,
					'Created page: ' . $this->data['Page']['title'] . ' (ID ' . $this->Page->id . ')');
				$this->Session->setFlash(__('The page has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.', true), 'flash_failure');
			}
		}

		$this->set(compact('landingPages'));
	}

	public function admin_edit($id = null) {
		$landingPageList = $this->Page->findLandingPages();

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid page', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}

		if (!empty($this->data)) {
			$this->log($this->data, 'debug');
			if (isset($this->data['Page']['image_url'])) {
				if ($this->data['Page']['image_url']['error'] === 0) {
					$this->uploadPageImage();
				} elseif ($this->data['Page']['image_url']['error'] === 4) {
					unset($this->data['Page']['image_url']);
				}
			}

			if ($this->Page->save($this->data)) {
				if ($this->data['Page']['slug'] === 'homepage') {
					Cache::delete('homepage_middle');
				} elseif (Cache::read($this->data['Page']['slug'])) {
					Cache::delete($this->data['Page']['slug']);
				}

				$this->Transaction->createUserTransaction('CMS', null, null,
					'Edit page ID: ' . $id);
				$this->Session->setFlash(__('The page has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Page->read(null, $id);
		}

		$this->set(compact('landingPageList'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for page', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
        }

        $page = $this->Page->read(null, $id);

        if ($page['Page']['locked'] == 1) {
            $this->Session->setFlash(__('This page cannot be deleted because it is locked', true), 'flash_failure');
            $this->redirect(array('action' => 'index'));
        } else {
            if ($this->Page->delete($id)) {
                            $this->Transaction->createUserTransaction('CMS', null, null,
                                            'Deleted page ID: ' . $id);
                $this->Session->setFlash(__('Page deleted', true), 'flash_success');
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('Page was not deleted', true), 'flash_failure');
                $this->redirect(array('action' => 'index'));
            }
        }

	}

	// returns a list of pages. on returns the title and slug field
	// used from admin/navigations
	function admin_get_short_list() {
		$pages = $this->Page->find('all', array('fields' => array('Page.title', 'Page.slug'), 'conditions' => array('Page.published' => 1)));
		
		foreach ($pages as $key => $value) {
			$data['pages'][$key]['title'] = $value['Page']['title'];
			$data['pages'][$key]['slug'] = $value['Page']['slug'];
		}
		
		$this->set(compact('data'));
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	private function uploadPageImage() {
		// get the document relative path to the inital storage folder
		$abs_path = WWW_ROOT . 'img/public/pages/';
		$rel_path = 'img/public/pages/';

		$pathinfo = pathinfo($_FILES['data']['name']['Page']['image_url']);
		$fileExt = ".{$pathinfo['extension']}";

		$filename = date('YmdHis') . $fileExt;

		// check to see if the directory exists
		if (!is_dir($abs_path)) {
			mkdir($abs_path);
		}

		$absFileLocation = $abs_path . $filename;

		if (!move_uploaded_file($_FILES['data']['tmp_name']['Page']['image_url'], $absFileLocation)) {
			return false;
		}

		$this->data['Page']['image_url'] = $filename;
		return true;
	}
}
?>
