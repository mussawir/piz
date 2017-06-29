<?php

class Admin_UserController extends Zend_Controller_Action
{
	
    protected $user_session = null;
    protected $db;
    protected $language_id = null;
    protected $filter = null;
    protected $user = null;
    protected $baseurl = '';

    public function init(){
          Zend_Layout::startMvc(
                        array('layoutPath' => APPLICATION_PATH . '/admin/layouts', 'layout' => 'layout')
        );
       
        $this->db = Zend_Db_Table::getDefaultAdapter();
		$this->user = new Application_Model_User();
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
        $this->language_id = Zend_Registry::get('lang_id'); //get the instance of database adapter
        $this->user_session = new Zend_Session_Namespace("user_session"); // default namespace
        $this->filter = new Zend_Filter_StripTags;
		//Zend_Registry::set('lang_id',2);

        ini_set("max_execution_time", 0);
        if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");
		}

            $auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect('/admin/login/login');
        }
					Application_Model_ViewSettings::common($this->view, $this->user_session);
	$this->view->role = $this->user_session->role_id;
	$this->view->name = $this->user_session->user_name;
    $this->view->logged_user_id = $this->user_session->user_id;
}


    // this is default output function
    public function indexAction() {	
	$results = $this->user->getAllUsers();
	$this->view->data = $results;
        }


public function newAction(){

	$form = new Application_Form_UserForm();
      $this->view->form = $form;
     
	  if (!$this->_request->isPost()) {
	   	   $this->view->form = $form;
    	   return;
               }
	 $formData = $this->_request->getPost();
					
       if (!$form->isValid($formData)) {
                       $this->view->form = $form;
                       return;
               }
			   
			   $exist = $this->user->checkEmail($formData['email']);
			   if($exist == true){
					$this->view->msg = "<div class='alert alert-danger'>Email (User ID) already exists. Please make another one .</div>";
					return;
			   }
	$formData['date_registered']= date("Y-m-d H:i:s");
			$this->view->msg = $this->user->addUser($formData);
			$this->user_session->msg = $this->view->msg;
			//clear all form fields 
			$form->reset();
	}

// for delete users
public function deleteUsersAction(){
    
	$user_id = $this->_request->getParam('id');
	$delete = $this->user->deleteUsers($user_id);
	$this->_redirect('/admin/user/list/');
		//var_dump($delete);
}

 
public function editAction(){

    $id = $this->_request->getParam('id');
    if(!isset($id)) $this->_redirect('admin/user/index');
	$form = new Application_Form_UserForm();
    $result = $this->user->getUser($id);
	$form->removeElement("password");		
    $this->view->id = $result->user_id;
    $form->email->setValue($result->email);
    $form->user_name->setValue($result->user_name);
	$form->role->setValue($result->role_id);
	$form->submit->setLabel("Update");
    $this->view->form =  $form;
 
     if (!$this->_request->isPost()) {
			$this->view->form = $form;
			return;
        }
        
        $formData = $this->_request->getPost();
        if (!$form->isValid($formData)) {
			$this->view->form = $form;
			return;
        }
		 $formData['user_id']= $id;
$result = $this->user->updateUser($formData);
$this->view->msg = $result;
//$this->_redirect('admin/user/list');
  }
  
       // for update user password 
  	public function updatePasswordAction(){

	$id = $this->_request->getParam('id');
	if(!isset($id)) $this->_redirect('admin/user/index');
	$form = new Application_Form_ChangePasswordForm();
		$this->view->form = $form;
		//$this->view->msg = "";
		
		if (!$this->_request->isPost()) {
			return;
		}
		$formData = $this->_request->getPost();
		if (!$form->isValid($formData)) {
			return;
		}
		//All business logics will come here
		if(strcmp($formData['pwd_current'],$formData['pwd'] ) == 0){
		$this->view->msg = "<div class='alert alert-danger'>Old and New password are same</div>";
		 $this->view->form = $form;
      		return;	
			}
		
		if(strcmp($formData['pwd'],$formData['pwd_confirm'] ) != 0){
		$this->view->msg = "<div class='alert alert-danger'>Passwords are not matching</div>";
		 $this->view->form = $form;
      		return;	
			}
      //  var_dump($formData);
		if($this->user->passUpdate($id, $formData['pwd'])){
				$this->view->msg = "<div class='alert alert-success'>Password successfully Updated</div>";
		}else{
				$this->view->msg = "<div class='alert alert-danger'>Password Update Failed. Try again</div>";
			}
		}
		
	public function activeUserAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->user->activeUser($this->db, $id)){
		 $this->_redirect("/admin/user/index");					} 
		}
		
	public function blockUserAction(){
		 $id = $this->_request->getParam('id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->user->blockUser($this->db, $id)){
		 $this->_redirect("/admin/user/index");		
				} 
		}
 
public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }
	
      public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_redirect('admin/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
}

