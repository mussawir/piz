<?php

class Admin_PostCommentsController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $post = null;
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
		$this->comments = new Application_Model_PostComments();
		
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
	   
        $by_post = $this->_request->getParam('status');
        if($this->user_session->role_id==3||$this->user_session->role_id==4)
        {
            if(isset($by_post))
            {
                $this->view->data = $this->comments->getPendingCommentsByUser($this->db, $this->user_session->user_id);
            }
            else
            {
                $this->view->data = $this->comments->getAllCommentsByUser($this->db, $this->user_session->user_id);    
            }
        }
        else{
            if(isset($by_post))
            {
                $this->view->data = $this->comments->getPendingComments($this->db);
            }
            else
            {
                $this->view->data = $this->comments->getAllComments($this->db);    
            }
        }
    }
 
 public function commentsListAction(){
   $data = $this->_request->getParam('id');
   //var_dump($data);
   //return;
   $results = $this->comments->getCommentsByPostID($this->db,$data); 
        $this->view->data = $results;
		//$this->view->post_title = $results->heading;
 }
	 
	 public function deletePostCommentAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->deleteComment($id)){
		 $this->_redirect("/admin/post-comments/comments-list/id/".$id);					
				} 
		}
		
	public function approvePostCommentAction(){
		 $id = $this->_request->getParam('id');
		 //var_dump($id);
		 //return;
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->approveComment($this->db, $id)){
			 $this->_redirect("/admin/posts/index");
		/* $this->_redirect("/admin/post-comments/comments-list/id/".$id);	*/				
				} 
		}
		
	public function rejectPostCommentAction(){
		 $id = $this->_request->getParam('id');

		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->rejectComment($this->db, $id)){
		$this->_redirect("/admin/posts/index"); /*$this->_redirect("/admin/post-comments/comments-list/id/".$id);	*/				
				} 
		}
	 
	 
	 /*delete post comment from post-comments/index*/
	public function deleteCommentAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->deleteComment($id)){
		 $this->_redirect("/admin/post-comments/index");					
				} 
		}
		
	public function approveCommentAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->approveComment($this->db, $id)){
		 $this->_redirect("/admin/post-comments/index");					
				} 
		}
		
	public function rejectCommentAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->comments->rejectComment($this->db, $id)){
		 $this->_redirect("/admin/post-comments/index");					
				} 
		}
		
	public function editAction(){
		
		$form = new Application_Form_CommentForm();
		$id = $this->_request->getParam('id');
		$result = $this->comments->getComment($id);
		
		
		$this->view->pc_id = $result->pc_id;
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