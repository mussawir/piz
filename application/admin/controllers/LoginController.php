<?php
class Admin_LoginController extends Zend_Controller_Action
{
	    private $user_session = null;
	    private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $user = null;
		private $from_email = "ali@aliinfotech.com";
		private $site_name = "Aliinfotech.com";
		private $site_url = "http://aliinfotech.com";
		
		

	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'single'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
		ini_set("max_execution_time",(60*300));
		$this->user = new Application_Model_User();
	
	}

	public function indexAction()
{
	$this->_redirect("/admin/login/login");			
}


	function loginAction(){
		$this->view->title = "Login";
		$form = new Application_Form_UserLoginForm();
        $this->view->form = $form;
		
		// Post and validation section 
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		if (!$form->isValid($formData)) return;
		
		$email = $formData['email'];
		$password  = md5($formData['password']);
	
		$this->authAdapter->setTableName('users')
		->setIdentityColumn('email')
		->setCredentialColumn('pwd')
		->setIdentity($email)
		->setCredential($password);
		$auth = Zend_Auth::getInstance();
		$result = $this->authAdapter->authenticate();
		if ($result->isValid()){
			$data = $this->authAdapter->getResultRowObject(null,'pwd');
			$auth->getStorage()->write($data);
			//fetch user info
			$user = new Application_Model_User();
			$select = $user->select(array('user_id', 'user_name','role_id','is_active'))->where('email = ?',$email);
			$row = $user->fetchRow($select);
			$this->user_session = new Zend_Session_Namespace('user_session'); // default namespace
			$this->user_session->user_name = $row->user_name;
			$this->user_session->user_id = $row->user_id;
			$this->user_session->role_id = $row->role_id;
			
            if($row->is_active == 1){
		    $this->_redirect('/admin/index');
			}
			else{
				$this->view->msg = "<div class='alert alert-danger'> You have been Block By Admin. </div>";
			$this->view->form = $form;
			}
			
		} 

		else{
			$this->view->msg = "<div class='alert alert-danger'> Invalid User Name or Passowrd </div>";
			$this->view->form = $form;
		}

	}// login action ends

	public function logoutAction(){
		$this->_helper->viewRenderer->setNoRender();
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity(); #1
		
        unset($this->user_session->user_id); 
        unset($this->user_session->role_id);
		
        $this->_redirect('/admin/login/admin-login');
	}
	
	
		public function recoverPassAction() {
        $form = new Application_Form_RecoverPassForm();
        $this->view->form = $form;

        if (!$this->_request->isPost()) {
            $this->view->form = $form;
            return;
        }

        $formData = $this->_request->getPost();
        $email = trim($formData['email']);

        if($email=='demo@netefct.com')
        {
            $this->view->msg = "<div class='alert alert-warning'>demo user can not recover password.</div>";
            return;
        }
        if (!$form->isValid($formData)) {
            $this->view->form = $form;
            return;
        }
return;
        $user = new Application_Model_User();
        $select = $user->select(array('user_id', 'pwd', 'email', 'user_name'))->where('email = ?', $email);
		
        $row = $user->fetchRow($select);
        if (is_object($row)) {
            $new_pass = rand(111111, 99999999);
            $data = array("user_id" => $row->user_id, "email" => $row->email, "pwd" => md5($new_pass));
            $user->updatePass($data);

			$subject = $this->site_name . " Password Recovery.";
    $body = '
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>'.$this->site_name.'</title>
   <style type="text/css">
   body {margin: 0; padding: 0; min-width: 100%!important;}
   </style>
  </head>
  <body><table><tr><td align="right" style="background-color:#E4308B;"> <td>
   </tr>
   <tr><td>Hi,'.trim($row->user_name).'</td></tr>
   <tr><td><h2>Your Password has been recover succussfully.</h2><td><tr>
 <tr><td>You can use the below code as your new password for login</td><tr>
 <tr><td>&nbsp;</td><tr>
 <tr><td>Your new password for '. $this->site_name.' is '.$new_pass.'.</td></tr>
 <tr><td>&nbsp;</td><tr>
 <tr><td><strong>Note : You are requested to change your password immediately for better security.</strong></td></tr>
<tr><td>&nbsp;</td><tr>
<tr><td>Best Regards </td><tr>
 <tr><td>'. $this->site_name.'
  </td><tr>
 </table>
  </body>
 </html>';
 $mail = new Zend_Mail();
   $mail->setFrom($this->from_email, $this->site_url);
   $mail->addTo(trim($formData['email']), trim($row->user_name));
   $mail->setSubject($subject);
   $mail->setBodyHtml($body);
   $mail->send();
         	
			$this->view->msg = "<div class='alert alert-success'>A new password has been sent to your email, please check your inbox and also check spam and other folders.</div>";
		
        } else {
            $this->view->msg = "<div class='alert alert-danger'>Sorry wrong email address.</div>";
        }
		
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