<?php

class Admin_CommentsController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $post = null;
		private $page = null;
		private $comments = null;
		
	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
				
		ini_set("max_execution_time",(60*300));
		$this->post = new Application_Model_Posts();
		$this->page = new Application_Model_Pages();
		$this->comments = new Application_Model_PageComments();
		
		if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");			
		}
		$auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect("/admin/login/login");
	$this->view->role = $this->user_session->role_id;
	$this->view->name = $this->user_session->user_name;
    $this->view->logged_user_id = $this->user_session->user_id;
        }
		
		Application_Model_ViewSettings::common($this->view, $this->user_session);
	}

	
	public function indexAction()
    {
        if ($this->_request->isPost())
        {
            $bulkdata = $this->_request->getPost('bulkdata');
            
            for($i=0; $i < count($bulkdata); $i++) {
    		  $id = $bulkdata[$i];    
    		  $this->comments->deleteComment($id);
            }
        }
	   
       $by_page = $this->_request->getParam('status');
        if(isset($by_page))
        {
            $this->view->data = $this->comments->getPendingComments($this->db);
            return;
        }
       
       	$results = $this->comments->getAllComments($this->db);
    	$this->view->data = $results;
	}
	
	public function commentsListAction(){
       $page_id = $this->_request->getParam('id');
       $this->view->page_id = $page_id;
       
       $results = $this->comments->getCommentsByPageID($this->db,$page_id);
       $this->view->data = $results;
    }
	
	public function deleteAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->deleteComment($id)){
		 $this->_redirect("/admin/comments");					
				} 
		}
		
	public function approveAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->approveComment($this->db, $id)){
		 $this->_redirect("/admin/comments");					
				} 
		}
		
	public function rejectAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->rejectComment($this->db, $id)){
		 $this->_redirect("/admin/comments");
				} 
		}
		
	public function editAction(){
		
		$form = new Application_Form_CommentForm();
		$id = $this->_request->getParam('id');
        $page_title = $this->_request->getParam('p');
        $page_id = $this->_request->getParam('p_id');
        
		$result = $this->comments->getComment($id);
		
		$this->view->page_id = $page_id;
		$this->view->comment_id = $result->comment_id;
        $this->view->status = $result->status;
        $this->view->page_title = $page_title;
        
		$form->name->setValue($result->name);
		$form->email->setValue($result->email);
		$form->comment->setValue($result->comment);
		$this->view->form = $form; 
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		
		if (!$form->isValid($formData)) return;
		
		$results = $this->comments->updateComment($this->db, $formData);
		$this->view->msg = $results;
	}

	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
	
	 public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
	
}