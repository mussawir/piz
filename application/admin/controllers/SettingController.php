<?php
 
class Admin_SettingController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;

	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		if(!isset($this->user_session->user_id)){
		$this->user_session = new Zend_Session_Namespace("user_session");
		}
		ini_set("max_execution_time",(60*300));
		
		if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");			
		}
		$auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect("/admin/login/login");
        }
		
		Application_Model_ViewSettings::common($this->view, $this->user_session);
	$this->view->role = $this->user_session->role_id;
	$this->view->name = $this->user_session->user_name;
    $this->view->logged_user_id = $this->user_session->user_id;
	}


	// this is default output function
	public function indexAction()
	{
		if($this->user_session->msg!=null)
        {
          $this->view->msg = $this->user_session->msg;
          $this->user_session->msg = null;
        }
	}	
	
    public function resetDefaultAction()
    {
        
    }
    
	// this is for cleaning all cache
	public function cleanCacheAction()
	{
		$this->_helper->viewRenderer->setNoRender();
     	$this->_helper->layout()->disableLayout();
		//for getting all cache
		// $cache = Zend_Registry::get('cache');
		//cleaning all cache
		$cache = Zend_Cache::factory(
                        'Core', 'File', array(
                    'lifetime' => 7200, 
                    'automatic_serialization' => true
                        ), array('cache_dir' => APPLICATION_PATH . '/../tmp')
        );

		if(isset($cache)){
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		$this->view->msg = "<div class='alert alert-success'>Cache Cleaned. </div>";
		}
		else{
			$this->view->msg = "<div class='alert alert-danger'>Cache already Clean. </div>";
		}
		$this->_redirect("/admin/setting/index");
	}	
    
    public function addCodeAction()
    {
        $this->_helper->viewRenderer->setNoRender();
     	$this->_helper->layout()->disableLayout();
        
        // code here
        
        $this->_redirect("/admin/setting/index");
    }
    
		// Paginator action
  	public function Paginator($results, $records) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($records);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

    public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('admin/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
}