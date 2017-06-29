<?php

class IndexController extends Zend_Controller_Action {
    private $baseurl = '';
	private $member_session = null;
	private $db = null;
    private $cookie = null;
    private $results = null;
    private $checkout_session = null;
	private $members_model = null;
	
    public function init() {
		$this->_helper->layout->setLayout('layout');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->member_session = new Zend_Session_Namespace("member_session");
        $this->checkout_session = new Zend_Session_Namespace("checkout_session");
        
        $this->members_model = new Application_Model_Members();
        
	    $this->view->user_role = $this->member_session->role_id;
        $this->view->logged_user_id = $this->member_session->member_id;
    } 
    public function indexAction() 
    {
  		$this->_helper->layout->setLayout('newlanding');
    }
	public function landingPageAction() 
    {
  		$this->_helper->layout->setLayout('home');
    }
	public function newlandingAction(){
		$this->_helper->layout->setLayout('newlanding');
	}
	public function newSearchAction() 
    {
  		$this->_helper->layout->setLayout('home');
		$query = $this->_request->getParam('q');
        
        $this->view->query = $query; 
        
        $pages = new Application_Model_Pages();
        $search_result = $pages->searchByCountry($this->db, $query);
        $this->Paginator($search_result);   
    }
	public function subscribersAction() 
    {
		
		if ($this->_request->isPost()) 
        {  
			$formData = Array();
			if(isset($_POST['full_name'])){ 
				$formData['full_name'] = $_POST['full_name'];
			}
			if(isset($_POST['phone'])){
				$formData['phone'] = $_POST['phone']; 
			}
			if(isset($_POST['mem_id'])){
				$formData['member_id'] = $_POST['mem_id'];
			}
			$formData['email'] = $_POST['email'];
			$sub_modal = new Application_Model_Subscribers();
			$subscribe = $sub_modal->add($formData);
			
			if($subscribe){
				echo 'success';
			}else{
				echo 'error';
			}
		}
		
    }
	public function homepageSubscribersAction() 
    {
		
		if ($this->_request->isPost()) 
        {  
			$formData = Array();
			
			$formData['email'] = $_POST['email'];
			$sub_modal = new Application_Model_Subscribers();
			$subscribe = $sub_modal->addlandingpagesubscriber($formData);
			
			if($subscribe){
				echo 'success';
			}else{
				echo 'error';
			}
		}
		
    }
	public function becomeMemberAction(){
		$this->_helper->layout->setLayout('home');
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
            // $name = explode(" ", $formData['first_name'], 2);
            
            
            $formData['pwd'] = md5($pwd);
            $formData['parent_id'] = 0;
            $formData['role_id'] = 1;            
            $formData['root_id'] = 0;
            $formData['is_verified'] = 1;
            $arr_email = explode('@',$formData['email']);
            $dir_name = strtolower($arr_email[0]).'_'.rand(11111, 99999);
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
                // $master_page = new Application_Model_MemberPagesMaster();
                // $master_data = array('member_id'=>$member_id, 'status'=>'PAID', 'pages'=>$package,'price'=>$price, 'page_status'=>'OFFLINE');
                // $mster_page_id = $master_page->add($master_data);
                
                // create page detail
                // $page_detail = new Application_Model_Pages();
                // for($x=1; $x<=$package; $x++){
                    // $page_detail->addMemberPage(array('master_p_id'=>$mster_page_id,'member_id'=>$member_id));
                // }
                
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
                       <tr><td>Hello Member,<br/><br/>Thank You For Joining Us.<br/> <br/>
						Please refer to login details<br/>
						Email: ' . $formData['email'] . ' <br/>
						Password: ' . $pwd . ' <br/>
                        <br/>Best Regards<br/><Strong>Pageiz Support Team</Strong><br/></td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom('noreply@netefct.com', 'netefct.com');
                $mail->addTo(trim($formData['email']), 'Member');
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
            } //if end
            $this->view->msg = "Login details has been sent to your email";
			return;
            
            
        }
	}
	
    public function searchAction()
    {
        $this->_helper->layout->setLayout('home');
        
        $query = $this->_request->getParam('q');
        
        $this->view->query = $query; 
        
        $pages = new Application_Model_Pages();
        $search_result = $pages->searchByCountry($this->db, $query);
        $this->Paginator($search_result);        
    }
	public function paymentAction(){
		
		
	}
	
	public function cancelAction(){
		
		
		
	}
	public function businessPagesAction()
    {
		$state_list = Application_Model_Countries::getAllStatesList($this->db);
        $this->view->state_list = $state_list;
        
        $query = $this->_request->getParam('q');
        $state = $this->_request->getParam('state');
        $city = $this->_request->getParam('city');
        
        $this->view->query = $query; 
        $this->view->state = $state;
        $this->view->city = $city;
        
        $is_query_set = !empty($query);
        $this->view->is_query_set = $is_query_set;
         
        if($is_query_set){
            $pages = new Application_Model_Pages();
          
            $search_result = $pages->getBusinessSearch($this->db, $query, $state, $city);            
            $this->view->search_result = $search_result;
            
            $host  = $_SERVER['HTTP_HOST'];
            $path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $baseurl = 'http://' . $host . $path .'/';
            $this->view->url = $baseurl;
        }
	}
	
	 public function dailyDealsAction() {
    		
    }
	

    
    /*public function searchAction() 
    {
        $biz_pages = $this->_request->getParam('biz_pages');
  		
        $pages = new Application_Model_Pages();
        $result = $pages->getPageSearch($this->db, $biz_pages);
    
    }*/ // search function end
    
    public function packagesAction()
    {
		$this->_helper->layout->setLayout('newlanding');
        if(isset($this->member_session->msg)){
            $this->view->msg = $this->member_session->msg;
            unset($this->member_session->msg);
        }
        
        if ($this->_request->isPost()) 
        {            
            $formData = $this->_request->getPost();
            
			$this->checkout_session->package_name = $formData['package_name'];
            $this->checkout_session->package_price = $formData['package_price'];
			$this->checkout_session->pages = $formData['pages'];
			
            // $this->_redirect('/index/payment-success');
            
            $this->_redirect('/index/page-detail?p='.$formData['pages'].'');
        }	
    } // package function end
    public function pageDetailAction(){
		if(!isset($this->checkout_session->package_name)){
            $this->_redirect('/index/packages');	
        }
		$package = $this->_request->getParam('p');
		$this->view->package = $package;
	}
	public function checkoutAction(){
		if(!isset($this->checkout_session->package_name)){
            $this->_redirect('/index/packages');	
        }
		$this->view->name = $this->checkout_session->customer_name;
		$package = $this->_request->getParam('p');
		$this->view->package = $package;
	}
	public function saveDetailsAction(){
			// echo $_GET['name'];
			
			$this->checkout_session->customer_name = $_GET['name'];
			$this->checkout_session->phone = $_GET['phone'];
			$this->checkout_session->email = $_GET['email'];
			var_dump($this->checkout_session->customer_name);
	}
	public function successAction(){
			// echo $_GET['name'];
			if(!isset($this->checkout_session->package_name)){
            $this->_redirect('/index/packages');	
        }
			$package = $this->_request->getParam('p');
			$name = $this->checkout_session->customer_name;
			$phone = $this->checkout_session->phone;
			$email = $this->checkout_session->email;
			$pwd = 12345678;
            $name = explode(" ", $name, 2);
			
            $formData = Array();
			$formData['first_name'] = $name[0];
			$formData['last_name'] = $name[1];
			$formData['email'] = $email;
			$formData['contact_number'] = $phone;
			
            $formData['pwd'] = md5($pwd);
            $formData['parent_id'] = 0;
            $formData['role_id'] = 2;            
            $formData['root_id'] = 0;
            $formData['is_verified'] = 1;
            $arr_email = explode('@',$formData['email']);
            $dir_name = strtolower($name[0]).'_'.rand(11111, 99999);
            $formData['dir_name'] = $dir_name;
			
			
            
            $member_id = $this->members_model->add($formData);
            if (isset($member_id))
            {
                $dir_path = SYSTEM_PATH. 'images/uploads/'.$dir_name;
                //create directory if not exists
                if (!file_exists($dir_path)) {
                    mkdir($dir_path, 0777, true);
               	}
                if (!file_exists($dir_path.'/1000X1000')) {
                    mkdir($dir_path.'/1000X1000', 0777, true);
               	}
                
                if (!file_exists($dir_path.'/500X500')) {
                    mkdir($dir_path.'/500X500', 0777, true);
               	}
                
                if (!file_exists($dir_path.'/300X300')) {
                    mkdir($dir_path.'/300X300', 0777, true);
               	}
                
                if (!file_exists($dir_path.'/150X150')) {
                    mkdir($dir_path.'/150X150', 0777, true);
               	}
                $price = 0;
                if($package==1){
                    $price = 400;
                } else if($package==4) {
                    $price = 1500;
                } else if($package==8) {
                    $price = 2600;
                }
                
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
                
                $subject = "Pageiz Login Details";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>Pageiz Business Page Registration</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body>
					  <table>
                        <tr><td align="left"><img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td></tr>
                       <tr><td>Hello Member,<br/><br/>Thank You For Joining Us.<br/> <br/>
						Please refer to login details<br/>
						Email: ' . $formData['email'] . ' <br/>
						Password: ' . $pwd . ' <br/>
                        <br/>Best Regards<br/><Strong>Pageiz Support Team</Strong><br/></td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom('noreply@pageiz.com', 'Pageiz');
                $mail->addTo(trim($formData['email']), $formData['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
            } //if end
            
			return;
			
	}
    public function pageOrderAction()
    {
        //$this->emailConfig();
        
        if(!isset($this->checkout_session->package_name)){
            $this->_redirect('/index/packages');	
        }

        $this->view->package_name = $this->checkout_session->package_name;
        $this->view->package_price = $this->checkout_session->package_price;
		$this->view->pages = $this->checkout_session->pages;
        
        if($this->checkout_session->is_sample_order){
            $this->view->cat_name = $this->checkout_session->cat_name;
            $this->view->biz_name = $this->checkout_session->biz_name;
            $this->view->contact_number = $this->checkout_session->contact_number;
            $this->view->email = $this->checkout_session->email;
            $this->view->biz_requirements = $this->checkout_session->biz_requirements;
            $this->view->selected_samples = implode(", ",$this->checkout_session->photo_ids['photo_id']);
        }
	    
        $order_form = new Application_Form_PageOrderForm();
        $this->view->order_form = $order_form;
        
        if (!$this->_request->isPost()) {            
            return; 
		}
        
        $formData = $this->_request->getPost();
        if (!$order_form->isValid($formData)) {
            $this->view->form = $order_form;
                return;
        }
        
        $is_exist = $this->members_model->checkEmail($formData['email']);
        if ($is_exist)
        {
            $this->view->msg = "<div class='alert alert-warning'>" . $formData['email'] ." is already exists.</div>";
            return;
        }
        
        // if inputs are valid then store in session
        $this->checkout_session->name = $formData['name'];
        $this->checkout_session->contact_number = $formData['contact_number'];
        $this->checkout_session->email = $formData['email'];
        
        $page_orders = new Application_Model_PageOrders();
		 $order_id = $page_orders->add($formData);
        
        $html = "<strong>New Order</strong><br/>" . $this->checkout_session->package_name . 
                    "<br/> RM" .$this->checkout_session->package_price.
                    "<br/> from Name: " . $formData['name'] . "&nbsp;&nbsp;Email: ".
                    $formData['email'] ."&nbsp;&nbsp;Contact: " .$formData['contact_number'];
            
            if($this->checkout_session->is_sample_order){
                $img_urls = '';
                foreach($this->checkout_session->selected_img['selected_img'] as $p){
                    $img_urls .= "($p)<br/>";
                }
                
                $html .= "<br/><br/><strong>Banner/Poster Samples Information:</strong><br/>";
                $html .= "Business Name: ".$this->view->biz_name."
                        <br/>Business Requirements: ".$this->view->biz_requirements."
                        <br/>Sample Category: ".$this->view->cat_name."
                        <br/>Selected Samples(IDs): ".$this->view->selected_samples.
                        "<br/>Poster Url: ".$img_urls;
            }
            
            //to mussawir for new order        
            $mail = new Zend_Mail();
        	$mail->setFrom('sales@netefct.com', 'http://pageiz.com/');
        	$mail->addTo('mussawir20@gmail.com',"mussawir");
        	$mail->setSubject('New page order' . $this->checkout_session->package_name);
        	$mail->setBodyHtml($html);
        	$mail->send();
        	
       	 $this->_redirect('/index/page-order-confirmation');
         
         //$this->_redirect('/index/package-payment');
				
    } // packageOrder function end

    public function packagePaymentAction(){
        if(!isset($this->checkout_session->package_name)){
            $this->_redirect('/index/packages');	
        }	
        $this->view->pp_info = $this->checkout_session;
    }
	
	public function pageOrderConfirmationAction(){
	   if(!isset($this->checkout_session->package_name)){
	       $this->_redirect('/index/page-order');
	   }
	   
       //$response = $this->getResponse()->getParams('response');
       //var_dump($response);
       
       $this->checkout_session->is_sample_order = false;
       unset($this->checkout_session->selected_img);
       unset($this->checkout_session->photo_ids);
       
       unset($this->checkout_session->package_name);
	   unset($this->checkout_session);
	}
    
    public function paymentSuccessAction(){
        $response = true;
        if($response)
        {
            // insert payment details into db and send email to admin 
            
            if ($this->_request->isPost()) 
            {
                $data = $this->_request->getPost();
                
                $this->checkout_session->new_page_url = trim($data['page_address']);
                
                if($this->checkout_session->is_sample_order){
                    $this->_redirect('/index/create-page');
                } else {
                    $this->_redirect('/index/poster-samples');   
                }
            }
        }
    }
    
    public function paymentCancelledAction(){
        if(!isset($this->checkout_session->package_name)){
            $this->_redirect('/index/packages');
        }
        unset($this->checkout_session->package_name);
        unset($this->checkout_session);
        
        $this->member_session->msg = "<div class='alert alert-info'>You have cancelled the payment!</div>";
        $this->_redirect('/index/packages');
    }
    
    public function paymentFailedAction()
    {
        if(!isset($this->checkout_session->package_name)){
            $this->_redirect('/index/packages');
        }
                
        $this->member_session->msg = "<div class='alert alert-danger'>We are sorry your payment failed on Paypal! Please try again.</div>";
        $this->_redirect('/index/page-order');
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

  
    // marketer registration
    public function mRegAction()
    {
        $package = $this->_request->getParam('p');
        
        $form = new Application_Form_BusinessRegForm();
        $this->view->form = $form;
        
        if ($this->_request->isPost()) 
        {            
            $formData = $this->_request->getPost();
            
            if (!$form->isValid($formData)) {
                $this->view->form = $form;
                return;
            }
            
            $is_exist = $this->members_model->checkEmail($formData['email']);
            if ($is_exist)
            {
                $this->view->msg = "<div class='alert alert-warning'>" . $formData['email'] ." is already exists.</div>";
                return;
            }
            
            if(strcmp($formData['pwd'], $formData['confirm_pass'] ) != 0){
                $this->view->msg = "<div class='alert alert-warning'>The password and confirm password do not match.</div>";
                $this->view->form = $form;
                return;	
            }
            
            $name = explode(" ", $formData['first_name'], 2);
            $formData['first_name'] = ucwords($name[0]);
            $formData['last_name'] = isset($name[1]) ? ucwords($name[1]) : '';
            
            $formData['pwd'] = md5($formData['pwd']);
            $formData['parent_id'] = 0;
            $formData['role_id'] = 2;            
            $formData['root_id'] = 0;
            $formData['is_verified'] = 1;
            
            $dir_name = strtolower($formData['first_name']).'_'.rand(11111, 99999);
            $formData['dir_name'] = $dir_name;
            unset($formData['confirm_pass']);
            unset($formData['submit']);
            
            $member_id = $members_model->add($formData);
            if (isset($member_id))
            {
                $dir_path = SYSTEM_PATH. 'images/uploads/'.$dir_name;
                //create directory if not exists
                if (!file_exists($dir_path)) {
                    mkdir($dir_path, 0777, true);
               	}
                
                $price = 0;
                if($package==1){
                    $price = 200;
                } else if($package==3) {
                    $price = 450;
                } else if($package==5) {
                    $price = 650;
                }
                
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
                $this->setEmailConfiguration();
                
                $subject = "NetEfct Business Page Registration";
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
                      <body><table>
                        <tr><td align="left"><img style="width:200px;" src="http://netefct.com/images/logo.png"/><td></tr>
                       <tr><td>Hello ' . $formData['first_name'] . ' ' . $formData['last_name'] .
                        ',<br/><br/>Thank You For Joinging Us.<br/>' .
                        'You have purchased '.$package.' business page(s) in RM'.$price.'.<br/>Purchase Date: '.date('m/d/Y') . 
                        '<br/>Please complete your business information to start your business'.
                        '<br/>Login into <a href="http://netefct.com/index/login">netefct.com</a>'.
                        '<br/><br/>Best Regards<br/><Strong>netefct.com</Strong><br/></td><tr>
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
            
            //save into authenticaion
            $authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
            $authAdapter->setTableName('members')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('pwd')
                    ->setIdentity($formData['email'])
                    ->setCredential($formData['pwd']);
            $auth = Zend_Auth::getInstance();
            $result = $authAdapter->authenticate();
    		
            if ($result->isValid()) 
            {
                $data = $authAdapter->getResultRowObject(null, 'password');
                $auth->getStorage()->write($data);
                
        			$this->member_session->first_name = $formData['first_name'];
        	    	$this->member_session->last_name = $formData['last_name'];
        	       $this->member_session->member_id = $member_id;
        			$this->member_session->email = $formData['email'];
                    $this->member_session->role_id = 2;
                    $this->member_session->dir_name = $formData['dir_name'];
            
                $this->_redirect('/members/member-list');
            } else {
                $this->_redirect('/index/login');
            }
            
        } //post request end
    } 
 
	 public function getStatesAction() {
   	$this->ajaxed();
     $country_id = $this->getRequest()->getParam('country_id');
   $results = Application_Model_Countries::getStatesList($this->db, $country_id);
	$rows = NULL;
	foreach($results as $result){
	$rows[] = $result;
		}
	$output  = json_encode($rows);
print $output;
    }
	
    public function getCitiesAction() {
        $this->ajaxed();
        $state_id = $this->getRequest()->getParam('state_id');
     //   echo $state_id;
		$results = Application_Model_Countries::getCitiesList($this->db,$state_id);
$rows = NULL;
	foreach($results as $result){
	$rows[] = $result;
		}
	
	$output  = json_encode($rows);
print $output;  
  } // getCities function end	
	
	
public function aboutAction() {
        /*for text blocks*/
        $this->_helper->layout->setLayout('newlanding');
	}	
	
    public function contactUsAction() 
    {
		$this->_helper->layout->setLayout('newlanding');
        
        
        if ($this->_request->isPost()) 
        {
            $formData = $this->_request->getPost();
            
            
            $subject = "Pageiz.com - Contact";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>Pageiz.com - Contact</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body><table>
                        <tr><td align="left"><img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td></tr>
                       <tr><td>Hello Admin,' . 
                        '<br/><br/>'.
                        'Name: '.$formData['cf_name'].
                        '<br/>Email: '.$formData['cf_email'].
                        '<br/>Message: '. $formData['cf_message'].
                        '</td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom(trim($formData['cf_email']), $formData['cf_name']);
                $mail->addTo('mussawir20@gmail.com', 'pageiz.com');
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
                $form->reset();
                
            $this->view->msg = "<div class='alert alert-info'><strong>Thank you. Our team will contact you shortly.</strong></div>";
        }
	}	
    
    function ajaxLoginAction() 
    {
        if(isset($this->member_session->member_id)) {
			$this->_redirect('/members/member-list');
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
            $member = $this->members_model->select(array('member_id','first_name','last_name','email', 'role_id', 'dir_name'))->where('email = ?', $email)->where('is_verified =?',1);
            $row = $this->members_model->fetchRow($member);
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
            
            // save activity after login
            if($formData['activity']=='like_page'){
                $like_page_model = new Application_Model_LikedPages();
                $result = $like_page_model->add($formData['page_id'], $this->member_session->member_id);
                
                if($result)
                {
                    $page_model = new Application_Model_Pages();
                    $page = $page_model->getPageLikes($formData['page_id']);
                    $page_model->setPageLikes($formData['page_id'], ($page['likes']+1));
                } 
            }
            
            $this->_redirect($formData['redirect_to']);
        } else {
            echo 'error';
        }
    } // ajax login function end
    
    public function getQuotesAction()
    {
        //unset($this->member_session->checkout_info);
	       
        $request = $this->getRequest();
        $form    = new Application_Form_GetQuotesForm();
        $countries_model = new Application_Model_Countries(); 
		$this->view->countries = $countries_model->sortedList();

        $this->view->form = $form; 
        
        if (!$this->getRequest()->isPost()) return;

	    $formData = $this->_request->getPost();

        	if (!$form->isValid($formData)) return;
        //	var_dump($formData);


			//$country_id = $this->_request->getParam('ncountry');
			//$state_id = $this->_request->getParam('nstates');
			//$this->site_session->country_id = $country_id;
		
        if($formData['nstates'] < 1){
			$this->view->msg = "<div class='alert alert-warning'><strong>Please select a state.</strong></div>"; 
            return;
		}
			
		/*	$this->member_session->checkout_info['business_name'] = $formData['bname'];
			$this->member_session->checkout_info['first_name'] = $formData['fname'];
			$this->member_session->checkout_info['last_name'] = $formData['lname'];
			//$this->member_session->checkout_info['promocode'] = $formData['promocode'];
			$this->member_session->checkout_info['city'] = $formData['location'];
			$this->member_session->checkout_info['phone'] = $formData['phone'];
			$this->member_session->checkout_info['topic'] = $formData['topic'];
		$price_range = NULL;
		if($formData['price'] == 1){
			$price_range = "$0-250";
			}elseif($formData['price'] == 2){
			$price_range = "$251-1000";
				}elseif($formData['price'] == 3){
			$price_range = "$1000+";
					}
			$this->member_session->checkout_info['price'] = $price_range;
			$this->member_session->checkout_info['postcode'] = $formData['postcode'];	
			$this->member_session->checkout_info['country'] = 'Australia';
			$this->member_session->checkout_info['state'] = $formData['nstates'];
			$this->member_session->checkout_info['email'] = $formData['email'];
			$code =$this->member_session->checkout_info['promocode'];
            //promotion code checking
            //check promotion code validation 
 		//if($formData['promocode'] != '' && strlen($formData['promocode']) > 7){
          if(false){  				
            		$promocode = new Application_Model_Promocodes();
            	   $result = $promocode->checkPromocode(trim($formData['email']), trim($formData['promocode']), 2);
            
            if($result["found"] == 1){
            	if($result["is_used"] == 1){
            $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry this promocode is already used! 
            <br/>To proceed please clear promocode field.
            <STRONG></div>";
            	return;
            		}
            
            
            if($result["is_expired"] == 1){
            $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry this promocode is expired! <STRONG>
            <br/>To proceed please clear promocode field.</div>";
            	return;
            		}
            
            if($result["value"] == 25 || $result["value"] > 25){
            $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry this is an invalid value promocode! <STRONG>
            <br/>To proceed please clear promocode field.</div>";
            	return;
            		}
            
            	$this->member_session->checkout_info['discount'] = $result["value"] ;
            	
            	}else{
            $this->view->msg = "<div class='alert alert-danger'><Strong>Please check! Have you entered right promocode? Enter correct promocode<br/> We have not found this promocode. To proceed please clear promocode field. <STRONG></div>";
            		$this->member_session->checkout_info['discount'] = 0;
            		return;
            		
          		}
 		     } // promocode validation ends                     
      		else{
                $this->member_session->checkout_info['discount'] = 0;
 		     }
			$this->member_session->checkout_info['total_payment'] = 25;
			$this->view->pp_info = $this->member_session->checkout_info;*/
			
            $subject = "Pageiz.com - Quote Finder";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>Pageiz.com - Quote Finder</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body><table>
                        <tr><td align="left"><img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td></tr>
                       <tr><td>Hello Admin,' . 
                        '<br/><br/>'.
                        '<strong>Quote Details:</strong><br/>'.
                        'First Name: '.ucfirst($formData['fname']).
                        '<br/>Last Name: '.ucfirst($formData['lname']).
                        '<br/>Business Name: '.$formData['bname'].
                        '<br/>Phone No.: '.$formData['phone'].
                        '<br/>Email: '.$formData['email'].
                        '<br/>Looking for: '.$formData['topic'].
                        '<br/>Country: Malaysia'.
                        '<br/>State: '.$formData['statesh'].
                        '<br/>City: '.$formData['location'].
                        '<br/>Postcode: '.$formData['postcode'].
                        '<br/>Price Range: '.$formData['price'].
                        '</td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom(trim($formData['email']), ucfirst($formData['fname']).' '.ucfirst($formData['lname']));
                $mail->addTo('mussawir20@gmail.com', 'pageiz.com');
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
            $this->view->msg = "<div class='alert alert-info'><strong>Thank you. Our team will contact you shortly.</strong></div>";
            $form->reset();
            //$this->_redirect("/index/get-quotes"); 
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
    
    public function newsletterAction()
    {
        $form = new Application_Form_NewsletterForm();
        $this->view->form = $form;
        
        if ($this->_request->isPost()) 
        {
            $formData = $this->_request->getPost();
            
            if (!$form->isValid($formData))
            {
                $this->view->form = $form;
                return;
            }
            
            $subject = "Pageiz.com - Newsletter";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>Pageiz.com - Newsletter</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body><table>
                        <tr><td align="left"><img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td></tr>
                       <tr><td>Hello Admin,' . 
                        '<br/><br/>'.
                        '<strong>Sign up details for Newsletter:</strong><br/>'.
                        'Name: '.$formData['name'].
                        '<br/>Email: '.$formData['email'].
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
            $this->view->msg = "<div class='alert alert-info'><strong>Thank you for subscription.</strong></div>";
        }
    }
    
    public function posterSamplesAction()
    {
        $this->_helper->layout->setLayout('newlanding');
        
    } // poster-samples function end
    
    public function showBizDetailsAction()
    {
        if ($this->_request->isPost())
        {
            $data = $this->_request->getPost();
            $this->checkout_session->cat_name = $data['category'];
            
            $this->_redirect('/index/biz-details');
        }
    }
    
    public function removePosterAction()
    {
        $arr_key = $this->getRequest()->getParam('arr_key');
        
        foreach($this->checkout_session->selected_img['selected_img'] as $key => $value){
            if($key==$arr_key){
                unset($this->checkout_session->selected_img['selected_img'][$arr_key]);
                unset($this->checkout_session->photo_ids['photo_id'][$arr_key]);
                break;
            }
        }
        $this->_redirect('/index/poster-samples');
    }
    
    public function bizDetailsAction()
    {
        $form = new Application_Form_SampleDetailsForm();
        $this->view->form = $form;
        
        $this->view->cat_name = $this->checkout_session->cat_name;
        
        $photos_model = new Application_Model_Photos();
        $result = $photos_model->getPhotosByIds($this->db, $this->checkout_session->photo_ids['photo_id']);
        $this->view->samples = $result;
        
        if ($this->_request->isPost()) 
        {
            $formData = $this->_request->getPost();
            
            if (!$form->isValid($formData))
            {
                $this->view->form = $form;
                return;
            }
            
            $is_exist = $this->members_model->checkEmail($formData['email']);
            if($is_exist){
                $this->view->msg = "<div class='alert alert-warning'>".$formData['email']." is already exists.</div>";
                return;
            }
            
            $this->checkout_session->name = trim($formData['name']);
            $this->checkout_session->biz_name = $formData['biz_name'];
            $this->checkout_session->contact_number = $formData['contact_number'];
            $this->checkout_session->email = $formData['email'];
            $this->checkout_session->biz_requirements = $formData['biz_requirements'];
            $this->checkout_session->is_sample_order = true;
            
            if(isset($this->checkout_session->package_name) && isset($this->checkout_session->new_page_url)){
                $this->_redirect('/index/create-page');
            } else {
                $this->_redirect('/index/packages');    
            }
        }
        
    } // biz-details funtion end
    
    public function createPageAction()
    {
		
        if(!isset($this->checkout_session->package_name) && !isset($this->checkout_session->new_page_url)){
            $this->_redirect('/index/packages');
        }
        
        $member_pwd = rand(111111, 99999999);
        $formData = array();
        $formData['pwd'] = md5($member_pwd);
	       $formData['role_id'] = 2;
           $formData['parent_id'] = 0;
           $formData['user_id'] = 1;
           $formData['root_id'] = 1;
           $formData['is_verified'] = 1;
           $formData['brochure_limit'] = 1;
           
           // split fullname into first and last name
           $member_name = explode(" ", $this->checkout_session->name, 2);
           $formData['first_name'] = $member_name[0];
           $formData['last_name'] = isset($member_name[1])?$member_name[1]:'';
           
           $dir_name = strtolower($formData['first_name']).'_'.rand(11111, 99999);
           $formData['dir_name'] = $dir_name;
           
           $member_id = $this->members_model->add($formData);
           if(isset($member_id)) 
           {
                $dir_path = SYSTEM_PATH. 'images/uploads/'.$dir_name;
                //create directory if not exists
                if (!file_exists($dir_path)) {
                    mkdir($dir_path, 0777, true);
               	}
                
                if (!file_exists($dir_path.'/800X800')) {
                    mkdir($dir_path.'/800X800', 0777, true);
               	}
                if (!file_exists($dir_path.'/500X500')) {
                    mkdir($dir_path.'/500X500', 0777, true);
               	}
                
                if (!file_exists($dir_path.'/300X300')) {
                    mkdir($dir_path.'/300X300', 0777, true);
               	}
                
                if (!file_exists($dir_path.'/150X150')) {
                    mkdir($dir_path.'/150X150', 0777, true);
               	}

                if (!file_exists($dir_path.'/images')) {
                    mkdir($dir_path.'/images', 0777, true);
               	}
            
            /*
                $subject = "Member Registration";
                $body = '
                 <!DOCTYPE html>
                 <html>
                  <head>
                   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                   <title>Member Registration</title>
                   <style type="text/css">
                        body {margin: 0; padding: 0; min-width: 100%!important;}
                   </style>
                  </head>
                  <body><table><tr><td align="left">
                    <img style="width:200px;" src="'.SYSTEM_PATH.'images/logo.png"/><td>
               		 </tr>
                   <tr><td>Hello ' . $formData['first_name'] . ' '. $formData['last_name'] . ','."\n\n".'Thank You For Joinging Us.'."\n".
                   'Following are your login credentials:'."\n".'Email: <strong>'.$formData['email']. '</strong>'."\n".'Password: <strong>'.$member_pwd.'</strong>'."\n".
                   'You can change your password after login. Please click on link to login: <a href="http://netefct.com/index/login" target="_blank">netefct.com</a>'.
                   "\n\n".'Best Regards'."\n".'<Strong>Netefct.com Team</Strong>
                  </td><tr>
                 </table>
                  </body>
                 </html>';
    
                //$subscribers = array($formData['first_name']=>$formData['email']); //'mussawir'=>'mussawir20@gmail.com'
                $mail = new Zend_Mail();
                $mail->setFrom('sales@netefct.com', 'netefct.com');
                $mail->addTo(trim($formData['email']), $formData['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                */
                $this->view->msg =  "<div class='alert alert-success'>Member created successfully!</div>";
                
                
           } else {
                $this->view->msg =  "<div class='alert alert-danger'>Some error occur. Please try again.</div>";
           }
        
    } // create-page function end
    
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
