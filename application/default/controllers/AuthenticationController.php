<?php
class AuthenticationController extends Zend_Controller_Action {
    private $baseurl = '';
	private $member_session = null;
	private $db = null;
    private $cookie = null;
    private $results = null;
    private $checkout_session = null;
	
	
    public function init() {
		$this->_helper->layout->setLayout('layout');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->member_session = new Zend_Session_Namespace("member_session");
        $this->checkout_session = new Zend_Session_Namespace("checkout_session");
		}
		
		public function index(){
			$this->_forward('login');	
		}
		
		 function loginAction() 
        {
		$this->_helper->layout->setLayout('home');
        if(isset($this->member_session->member_id)) {
			$this->_redirect('/mem-pages/index');
 		}

	    if (isset($this->member_session->msg)){
    	   $this->view->msg = $this->member_session->msg;
    	   unset($this->member_session->msg);
    	}
        
        $form = new Application_Form_MemberLogin();
        $form->submit->setAttrib("class", "cr-acnt-btn");
        $this->view->form = $form;
      /*  
        $text_block = new Application_Model_TextBlocks();
        $this->view->text_block = $text_block->getAllTextBlocks();
        
        $image_block = new Application_Model_ImageBlocks();
       $this->view->image_block = $image_block->getAllImageBlocks();
        */
		
        if (!$this->_request->isPost()) {
            $this->view->form = $form;
            return;
        }
		
		$formData = $this->_request->getPost();
		
		if (!$form->isValid($formData)) {
            $this->view->form = $form;
            return;
        }
		
        $email = $formData['email'];
        $password = md5($formData['password']);
		
        //save into authenticaion
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
        $authAdapter->setTableName('members')
                ->setIdentityColumn('email')
                ->setCredentialColumn('pwd')
                ->setIdentity($email)
                ->setCredential($password);
        $auth = Zend_Auth::getInstance();
        $result = $authAdapter->authenticate();
		
        if ($result->isValid()) {
			$this->member_session->getLpw = $formData['password'];
            $data = $authAdapter->getResultRowObject(null, 'password');
            $auth->getStorage()->write($data);
            
            //fetch user info
            $members = new Application_Model_Members();
            $member = $members->select(array('member_id','first_name','last_name','email', 'role_id', 'dir_name'))->where('email = ?', $email)->where('is_verified = ?', 1);
            $row = $members->fetchRow($member);
            
            if(isset($row)){
    			$this->member_session->first_name = $row->first_name;
    	    	$this->member_session->last_name = $row->last_name;
    	       $this->member_session->member_id = $row->member_id;
    			$this->member_session->email = $row->email;
                $this->member_session->role_id = $row->role_id;
                $this->member_session->dir_name = $row->dir_name;
			} else {
                $this->member_session->msg = "<tr><td colspan='2' style='color:red'><strong>Invalid email or passowrd. Please try again.</strong></td></tr>";                
			}
            
            if(isset($this->checkout_session->package)) {
    			$this->_redirect('/index/payment-options');
     		} else {
                $this->_redirect('/mem-pages/index');
            }
            
	   } else {
            $this->view->msg = "Invalid email or passowrd. Please try again";
            $this->view->form = $form;
        }
    } //login function end
	
	
    public function logoutAction() {
        $this->_helper->viewRenderer->setNoRender();
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity(); #1
        unset($this->member_session->member_id); //on logout unset all sessions values
        unset($this->member_session->role_id); 
        unset($this->member_session);
        
        $this->_redirect('/');
    }
    
    public function recoverPassAction() 
    {
        $this->view->title = "Recover Password";
        $form = new Application_Form_RecoverPassForm();
        $form->submit->setLabel("Recover Now");
        $form->submit->setAttrib("class", "cr-acnt-btn");
        $this->view->form = $form;
        
        /*$text_block = new Application_Model_TextBlocks();
        $this->view->text_block = $text_block->getAllTextBlocks();
        
        $image_block = new Application_Model_ImageBlocks();
       $this->view->image_block = $image_block->getAllImageBlocks();*/

        if (!$this->_request->isPost()) {
            $this->view->form = $form;
            return;
        }

        $formData = $this->_request->getPost();
        $email = $formData['email'];

        if (!$form->isValid($formData)) {
            $this->view->form = $form;
            return;
        }

        $user = new Application_Model_Members();
        $select = $user->select(array('member_id', 'pwd', 'email', 'first_name'))->where('email = ?', $email);
		
        $row = $user->fetchRow($select);
        if (is_object($row)) 
        {
            $new_pass = rand(111111, 99999999);
            $data = array("member_id" => $row->member_id, "email" => $row->email, "pwd" => md5($new_pass));
            $user->updatePass($data);
            
            $this->setEmailConfiguration();
            
            $subject = "pageiz.com Password Recovery";
            $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>pageiz.com Password Recovery</title>
                <style type="text/css">body {margin: 0; padding: 0; min-width: 100%!important;}.content {width: 100%; max-width: 600px;}</style>
            </head>
            <body yahoo bgcolor="#f6f8f1">
                <table width="100%" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="60%">&nbsp;</td>
                        <td><img src="http://pageiz.com/images/logo.png"/><td>
                    </tr>
                </table>
            <table>
            <tr><td>Hi '. $row->first_name .'</td></tr>
            <tr><td><h2>Password Reset </h2><td><tr>.
        <tr><td>Here is your new password : '. $new_pass .'</td><tr>
        <tr><td>You can change your password by logging in at <a href="http://pageiz.com/authentication/login">netefct.com</a>.
         </td><tr>
        <tr><td>&nbsp;</td><tr>
        <tr><td>Thank you </td><tr>
        <tr><td>pageiz.com</td><tr>
        </table></body></html>';
        		
        		$mail = new Zend_Mail();
                $mail->setFrom('mussawir20@gmail.com', 'pageiz.com');
                $mail->addTo(trim($email),$row->first_name);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
        
        			$this->view->msg = "<div class='alert alert-success'>A new password has been sent to your inbox, please also check spam and other folders.</div>";
        			
        		} else {
                    $this->view->msg = "<div class='alert alert-danger'>Email address not found.</div>";
                }
		
    }

	  private function setEmailConfiguration()
    {
        require (realpath(dirname(__file__) . '/../../..') .'/library/Zend/Mail/Transport/Smtp.php');
                $config = new Zend_Mail_Transport_Smtp('smtp.gmail.com', array(
                    'auth' => 'login',
                    'username' => 'colinkr.test@gmail.com',
                    'password' => 'colinkr123',
                    'port' => '587',
                    'ssl' => 'tls'));
                Zend_Mail::setDefaultTransport($config);
    }
       
    function ajaxLoginAction() 
    {
        if(isset($this->member_session->member_id)) {
			$this->_redirect('/mem-pages/index');
 		}
        
        if (!$this->_request->isPost()) {            
            return;
        }
		
		$formData = $this->_request->getPost();
		
        $email = $formData['email'];
        $password = md5($formData['password']);
		
        //save into authenticaion
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
        $authAdapter->setTableName('members')
                ->setIdentityColumn('email')
                ->setCredentialColumn('pwd')
                ->setIdentity($email)
                ->setCredential($password);
        $auth = Zend_Auth::getInstance();
        $result = $authAdapter->authenticate();
		
        if ($result->isValid()) {
            $data = $authAdapter->getResultRowObject(null, 'password');
            $auth->getStorage()->write($data);
            
            //fetch user info
            $members = new Application_Model_Members();
            $member = $members->select(array('member_id','first_name','last_name','email', 'role_id', 'dir_name'))->where('email = ?', $email)->where('is_verified =?',1);
            $row = $members->fetchRow($member);
            if(isset($row)){
    			$this->member_session->first_name = $row->first_name;
    	    	$this->member_session->last_name = $row->last_name;
    	       $this->member_session->member_id = $row->member_id;
    			$this->member_session->email = $row->email;
                $this->member_session->role_id = $row->role_id;
                $this->member_session->dir_name = $row->dir_name;
			} else {
			  return;
			}
            $this->_redirect($formData['redirect_to']);
        } else {
            echo 'error';
        }
    } // ajax login function end

    public function doVerificationAction()
    {
        $verification_code = $this->_request->getParam('code');
        $redirect_to = $this->_request->getParam('ref');
        
        $members = new Application_Model_Members();
        $member_result = $members->getMemberByCode($verification_code);
        
        if($member_result['is_verified']==0) 
        {        
            $member_pwd = rand(111111, 99999999);
            $password = md5($member_pwd);
            
            $dir_name = strtolower($member_result['first_name']).'_'.rand(11111, 99999);
                
                //fetch user info
                $members->updateVerification($member_result['member_id'], $dir_name, $password);
                
                $dir_path = SYSTEM_PATH. 'images/uploads/'.$dir_name;
                //create directory if not exists
                if (!file_exists($dir_path)) {
                    mkdir($dir_path, 0777, true);
               	}            
            
            //save into authenticaion
            $authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
            $authAdapter->setTableName('members')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('pwd')
                    ->setIdentity($member_result['email'])
                    ->setCredential($password);
            $auth = Zend_Auth::getInstance();
            $result = $authAdapter->authenticate();
    		
            if ($result->isValid()) 
            {
                $data = $authAdapter->getResultRowObject(null, 'password');
                $auth->getStorage()->write($data);
                
        			$this->member_session->first_name = $member_result['first_name'];
        	    	$this->member_session->last_name = $member_result['last_name'];
        	       $this->member_session->member_id = $member_result['member_id'];
        			$this->member_session->email = $member_result['email'];
                    $this->member_session->role_id = $member_result['role_id'];
                    $this->member_session->dir_name = $member_result['dir_name'];
    			
                $this->setEmailConfiguration();

                $subject = "netefct.com login credentials";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>netefct.com login credentials</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body><table><tr><td align="left">
                        <img style="width:200px;" src="http://netefct.com/images/logo-new.png"/><td>
                   		 </tr>
                       <tr><td>Hello ' . $member_result['first_name'] . ' ' . $member_result['last_name'] .
                    ', Following are your login credentials:<br/>Email: <strong>' . $member_result['email'] .
                    '</strong><br/>Password: <strong>' . $member_pwd . '</strong>' .
                    '<br/>You can change your password after login.' .
                    '<br/><br/>Best Regards<br/><Strong>netefct.com</Strong>
                      </td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom('noreply@netefct.com', 'netefct.com');
                $mail->addTo(trim($member_result['email']), $member_result['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
                $this->_redirect($redirect_to);
            }
        } else {
            $this->_redirect('/');
        }
    }

  

		/*for sending mail*/
public function SendMail($first_name, $to, $subject, $body)
	{
	$mail = new Zend_Mail();
	$mail->setFrom('ali@infotech.com', 'http://aliinfotech.com/netefct/');
	$mail->addTo($to,$first_name);
	$mail->setSubject($subject);
	$mail->setBodyHtml($body);
	$mail->send();
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

 public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }

	} ?>