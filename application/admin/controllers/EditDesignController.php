<?php

class Admin_EditDesignController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
	  	private $textBlock = null;
	  	private $imageBlock = null;
	  	private $gallery = null;
	  	private $sliders = null;

	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'template-editor-layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
          $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");

		ini_set("max_execution_time",(60*300));

          $this->textBlock = new Application_Model_TextBlocks();
          $this->imageBlock = new Application_Model_ImageBlocks();
          $this->gallery = new Application_Model_Photos();
          $this->slide = new Application_Model_Sliders();

		if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");
		}

            $auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect("/admin/login/login");
        }
		Application_Model_ViewSettings::common($this->view, $this->user_session);
		
	}

	public function indexAction(){

	$sliders = new Application_Model_sliders();
	$this->view->slides = $sliders->getAllSlides();

	$textBlock = new Application_Model_TextBlocks();
	$this->view->text = $textBlock->getAllTextBlocks();
	
	$imageBlock = new Application_Model_ImageBlocks();
	$this->view->image = $imageBlock->getAllImageBlocks();

	$gallery = new Application_Model_Photos();
	$this->view->gallery = $gallery->getAllGalleryPhotos();
	
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
            return $this->_forward('/admin/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }

}