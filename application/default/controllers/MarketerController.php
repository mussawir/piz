<?php

class MarketerController extends Zend_Controller_Action {
    private $baseurl = '';
	private $member_session = null;
	private $db = null;
    private $cookie = null;
    private $results = null;
    
	private $members_model = null;
	
    public function init() {
		$this->_helper->layout->setLayout('layout');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->member_session = new Zend_Session_Namespace("member_session");
        
        
        $this->members_model = new Application_Model_Members();
        
	    $this->view->user_role = $this->member_session->role_id;
        $this->view->logged_user_id = $this->member_session->member_id;
    }

    public function indexAction() 
    {
		$this->_helper->layout->setLayout('newlanding');
  		if ($this->_request->isPost()) 
        {            
            $formData = $this->_request->getPost();
            
            
            
            $is_exist = $this->members_model->checkEmail($formData['email']);
            if ($is_exist)
            {
                $this->view->msg = "<div class='alert alert-warning'>" . $formData['email'] ." is already exists.</div>";
                return;
            }
            
            // if(strcmp($formData['pwd'], $formData['confirm_pass'] ) != 0){
                // $this->view->msg = "<div class='alert alert-warning'>The password and confirm password do not match.</div>";
                // $this->view->form = $form;
                // return;	
            // }
            $pwd = rand(111111,999999);
            $name = explode(" ", $formData['first_name'], 2);
            $formData['first_name'] = ucwords($name[0]);
            $formData['last_name'] = isset($name[1]) ? ucwords($name[1]) : '';
            
            $formData['pwd'] = md5($pwd);
            $formData['parent_id'] = 0;
            $formData['role_id'] = 3;            
            $formData['root_id'] = 0;
            $formData['is_verified'] = 1;
            
            $dir_name = strtolower($formData['first_name']).'_'.rand(11111, 99999);
            $formData['dir_name'] = $dir_name;
			
			
            
            $member_id = $this->members_model->add($formData);
            if (isset($member_id))
            {
                $dir_path = SYSTEM_PATH. 'images/uploads/'.$dir_name;
                //create directory if not exists
                if (!file_exists($dir_path)) {
                    mkdir($dir_path, 0777, true);
               	}
                
                // $price = 0;
                // if($package==1){
                    // $price = 200;
                // } else if($package==3) {
                    // $price = 450;
                // } else if($package==5) {
                    // $price = 650;
                // }
                
                // create pages master
                $master_page = new Application_Model_MemberPagesMaster();
                $master_data = array('member_id'=>$member_id, 'status'=>'PAID', 'pages'=>$package,'price'=>$price, 'page_status'=>'OFFLINE');
                $mster_page_id = $master_page->add($master_data);
                
                // create page detail
                $page_detail = new Application_Model_Pages();
                for($x=1; $x<=$package; $x++){
                    $page_detail->addMemberPage(array('master_p_id'=>$mster_page_id,'member_id'=>$member_id));
                }
                
                // send mail
                $this->emailConfig();
                
                $subject = "Pageiz Marketing Dashboard Login Details";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>NetEfct Business Page Registration</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body>
					  <table>
                        <tr><td align="left"><img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td></tr>
                       <tr><td>Hello ' . $formData['first_name'] . ' ' . $formData['last_name'] .
                        '<br/><br/>Thank You For Joining Us.<br/> <br/>
						Please refer to login details<br/>
						Email: ' . $formData['email'] . ' <br/>
						Password: ' . $pwd . ' <br/>
                        <br/>Best Regards<br/><Strong>Pageiz Support Team</Strong><br/></td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom('noreply@netefct.com', 'netefct.com');
                $mail->addTo(trim($formData['email']), $formData['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
            } //if end
            $this->view->msg = "<div class='alert alert-warning'>Login details has been sent to your email</div>";
			return;
            
            
        }
    }
	private function emailConfig(){
					require (realpath(dirname(__file__) . '/../../..') .'/library/Zend/Mail/Transport/Smtp.php');
                $config = new Zend_Mail_Transport_Smtp('smtp.gmail.com', array(
                    'auth' => 'login',
                    'username' => 'valeedmahmood@gmail.com',
                    'password' => '786786786',
                    'port' => '587',
                    'ssl' => 'tls'));
                Zend_Mail::setDefaultTransport($config);	
	
    }
	public function registerAction() 
    {
		$this->_helper->layout->setLayout('home');
  		// $package = $this->_request->getParam('p');
        
        // $form = new Application_Form_BusinessRegForm();
        // $this->view->form = $form;
        
        if ($this->_request->isPost()) 
        {            
            $formData = $this->_request->getPost();
            
            
            
            $is_exist = $this->members_model->checkEmail($formData['email']);
            if ($is_exist)
            {
                $this->view->msg = $formData['email'] ." is already exists.";
                return;
            }
            
            // if(strcmp($formData['pwd'], $formData['confirm_pass'] ) != 0){
                // $this->view->msg = "<div class='alert alert-warning'>The password and confirm password do not match.</div>";
                // $this->view->form = $form;
                // return;	
            // }
            $pwd = rand(111111,999999);
            $name = explode(" ", $formData['first_name'], 2);
            $formData['first_name'] = ucwords($name[0]);
            $formData['last_name'] = isset($name[1]) ? ucwords($name[1]) : '';
            
            $formData['pwd'] = md5($pwd);
            $formData['parent_id'] = 0;
            $formData['role_id'] = 3;            
            $formData['root_id'] = 0;
            $formData['is_verified'] = 1;
            
            $dir_name = strtolower($formData['first_name']).'_'.rand(11111, 99999);
            $formData['dir_name'] = $dir_name;
			
			
            
            $member_id = $this->members_model->add($formData);
            if (isset($member_id))
            {
                $dir_path = SYSTEM_PATH. 'images/uploads/'.$dir_name;
                //create directory if not exists
                if (!file_exists($dir_path)) {
                    mkdir($dir_path, 0777, true);
               	}
                
                // $price = 0;
                // if($package==1){
                    // $price = 200;
                // } else if($package==3) {
                    // $price = 450;
                // } else if($package==5) {
                    // $price = 650;
                // }
                
                // create pages master
                $master_page = new Application_Model_MemberPagesMaster();
                $master_data = array('member_id'=>$member_id, 'status'=>'PAID', 'pages'=>$package,'price'=>$price, 'page_status'=>'OFFLINE');
                $mster_page_id = $master_page->add($master_data);
                
                // create page detail
                $page_detail = new Application_Model_Pages();
                for($x=1; $x<=$package; $x++){
                    $page_detail->addMemberPage(array('master_p_id'=>$mster_page_id,'member_id'=>$member_id));
                }
                
                // send mail
                $this->emailConfig();
                
                $subject = "Pageiz Marketing Dashboard Login Details";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>NetEfct Business Page Registration</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body>
					  <table>
                        <tr><td align="left"><img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td></tr>
                       <tr><td>Hello ' . $formData['first_name'] . ' ' . $formData['last_name'] .
                        '<br/><br/>Thank You For Joining Us.<br/> <br/>
						Please refer to login details<br/>
						Email: ' . $formData['email'] . ' <br/>
						Password: ' . $pwd . ' <br/>
                        <br/>Best Regards<br/><Strong>Pageiz Support Team</Strong><br/></td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom('noreply@netefct.com', 'netefct.com');
                $mail->addTo(trim($formData['email']), $formData['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
            } //if end
            $this->view->msg = "Login details has been sent to your email";
			return;
            
            
        }
    }
	public function loginAction(){
		$this->_helper->layout->setLayout('home');
		if(isset($this->member_session->member_id)) {
			$this->_redirect('/mem-pages/index');
 		}

	    if (isset($this->member_session->msg)){
    	   $this->view->msg = $this->member_session->msg;
    	   unset($this->member_session->msg);
    	}
        
        // $form = new Application_Form_MemberLogin();
        // $form->submit->setAttrib("class", "cr-acnt-btn");
        // $this->view->form = $form;
      /*  
        $text_block = new Application_Model_TextBlocks();
        $this->view->text_block = $text_block->getAllTextBlocks();
        
        $image_block = new Application_Model_ImageBlocks();
       $this->view->image_block = $image_block->getAllImageBlocks();
        */
		
        // if (!$this->_request->isPost()) {
            // $this->view->form = $form;
            // return;
        // }
		if ($this->_request->isPost()) {
		$formData = $this->_request->getPost();
		
		// if (!$form->isValid($formData)) {
            // $this->view->form = $form;
            // return;
        // }
		
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
            
           
                $this->_redirect('/mem-pages/index');
           
            
	   } else {
            $this->view->msg = "Invalid email or passowrd. Please try again.";
            
        }
	}
	}
    public function sendTestimonialAction()
    {
        $form = new Application_Form_GetTestimonial();
        $this->view->form = $form;
        
        if ($this->_request->isPost()) 
        {
            $formData = $this->_request->getPost();
            
            if (!$form->isValid($formData))
            {
                $this->view->form = $form;
                return;
            }
            
            $subject = "Pageiz.com - Testimonial";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>Pageiz.com - Testimonial</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body><table>
                        <tr><td align="left"><img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td></tr>
                       <tr><td>Hello Admin,' . 
                        '<br/><br/>'.
                        '<strong>Testimonial details are as follows:</strong><br/>'.
                        'Name: '.$formData['name'].
                        '<br/>Email: '.$formData['email'].
                        '<br/>Testimonial: '. $formData['message'].
                        '</td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom(trim($formData['email']), $formData['name']);
                $mail->addTo('mussawir20@gmail.com', 'pageiz.com');
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
                $form->reset();
            $this->view->msg = "<div class='alert alert-info'><strong>Thank you for providing testimonial.</strong></div>";
        }
    }
    
    
    
    
    
   
    
     // biz-details funtion end
    
     // create-page function end
    
	/*for sending mail*/
public function SendMail($first_name, $to, $subject, $body)
	{
	$mail = new Zend_Mail();
	$mail->setFrom('ali@infotech.com', 'Pageiz.com');
	$mail->addTo($to,$first_name);
	$mail->setSubject($subject);
	$mail->setBodyHtml($body);
	$mail->send();
	} 


   public function Paginator($results, $items=20) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($items);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }

		public function siteMapAction() {
			$links =  new Application_Model_SocialLinks();
	$this->view->links = $links->getSocialLinks();
			}

 function getUrl(){
      return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],$_SERVER['REQUEST_URI']
      );
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
}
//.end of class
