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
                return $this->Page->findPublishedBySlug('homepage');
            } else {
                // can't explode $this->params['url'] because its an array
                $request = explode('/', $this->params['url']['url']);
                $slug = $request[1];
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
                    }
                    $data = array(
                        'title_for_layout' => $page['Page']['title'],
                        'content' => $page['Page']['content']
                    );
                    $this->set($data);
                }
            }
        }

	function admin_index() {
		$this->Page->recursive = 0;
		$this->set('pages', $this->paginate());
	}

	function admin_add() {
		if($this->Acl->check(array('model' => 'User',
								   'foreign_key' => $this->Auth->user('id')), 'Pages/admin_add', '*')){
			$_SESSION['ck_authorized'] = true;
	    }
		if (!empty($this->data)) {
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
	}

	function admin_edit($id = null) {
		if($this->Acl->check(array('model' => 'User',
								   'foreign_key' => $this->Auth->user('id')), 'Pages/admin_edit', '*')){
			$_SESSION['ck_authorized'] = true;
	    }
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid page', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Page->save($this->data)) {
                if ($this->data['Page']['slug'] === 'homepage') {
                    Cache::delete('homepage_middle');
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
        }

		if ($this->Page->delete($id)) {
                        $this->Transaction->createUserTransaction('CMS', null, null,
                                        'Deleted page ID: ' . $id);
			$this->Session->setFlash(__('Page deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Page was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}

	// returns a list of pages. on returns the title and slug field
	// used from admin/navigations
	function admin_get_short_list() {
		$pages = $this->Page->find('all', array('fields' => array('Page.title', 'Page.slug'), 'conditions' => array('Page.published' => 1)));
		
		foreach ($pages as $key => $value) {
// 			FireCake::log($key);
//			FireCake::log($value);
			$data['pages'][$key]['title'] = $value['Page']['title'];
			$data['pages'][$key]['slug'] = $value['Page']['slug'];
		}
		
		FireCake::log(json_encode($data));
		
		$this->set(compact('data'));
		return $this->render(null, null, '/elements/ajaxreturn');
	}
}
?>
