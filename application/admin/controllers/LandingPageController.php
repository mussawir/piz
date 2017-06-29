<?php

class Admin_LandingPageController extends Zend_Controller_Action
{
    private $baseurl = '';
    var $user_session = null;
    private $db = null;
    private $cookie = null;
    private $text_block = null;
    private $results = null;
    private $slides = null;
    private $image_block = null;
    private $gallery = null;

    public function init()
    {
        Zend_Layout::startMvc(
        array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'template-editor-layout'));
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
        $this->user_session = new Zend_Session_Namespace("user_session");
        
        ini_set("max_execution_time",(60*300));
        
        $this->text_block = new Application_Model_TextBlocks();
        $this->image_block = new Application_Model_ImageBlocks();
        $this->slides = new Application_Model_Sliders();
        $this->gallery = new Application_Model_Photos();
                
        if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");
		}

            $auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect("/admin/login/login");
        }
    }

    public function indexAction()
    {
        $results = $this->slides->getAllSlides();
        $this->view->slides = $results;
       
        $text_block = new Application_Model_TextBlocks();
        $this->view->text_block = $text_block->getAllTextBlocks();
       
        /*for image blocks*/
        $image_block = new Application_Model_ImageBlocks();
        $this->view->image_block = $image_block->getAllImageBlocks();
       
        /*for gallery*/
        $gallery = new Application_Model_Photos();
        $this->view->gallery = $gallery->getAllGalleryPhotos();

    }


    public function Paginator($results)
    {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

    public function __call($method, $args)
    {
        if ('Action' == substr($method, -6))
        {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "' . $method . '" called', 500);
    }

    public function ajaxed()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest())
            return; // if not a ajax request leave function

    }

} //end class
