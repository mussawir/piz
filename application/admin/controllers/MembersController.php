<?php
class Admin_MembersController extends Zend_Controller_Action
{
	protected $members = null;
	protected $db;
    var $user_session = null;
    private $authAdapter = null;
    protected $baseurl = '';
    private $results = null;
	public function init(){
	  Zend_Layout::startMvc(
                        array('layoutPath' => APPLICATION_PATH . '/admin/layouts', 'layout' => 'layout')
        );

        
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->members = new Application_Model_Members();

        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
        $this->user_session = new Zend_Session_Namespace("user_session");

		$auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
			
			$this->_redirect('/admin/login/login');
                }
       
        if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");			
		}
	
	   Application_Model_ViewSettings::common($this->view, $this->user_session);
    	$this->view->role = $this->user_session->role_id;
    	$this->view->name = $this->user_session->user_name;
        $this->view->logged_user_id = $this->user_session->user_id;
	
	} // init function end
	  
		
		public function listAction()
        {
			
		 if(isset($this->user_session->msg)){
          $this->view->msg = $this->user_session->msg; 
          unset($this->user_session->msg);
        		}	
        			
            $results = $this->members->getMembersList();
               if (count($results) > 0) {
        		 $this->Paginator($results);
                } else {
                $this->view->empty_rec = true;
 		     }
        }

		 public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
	public function newPageAction(){
		$this->view->marketers = $this->members->getMarketerList();
		$this->view->members = $this->members->getMembersList();
		if ($this->_request->isPost()) 
        {
			$formData = $this->_request->getPost();
			$slider = 1;
			$video = 1;
			$pop = 1;
			$inpage = 1;
			if(isset($formData['slider'])){
				$slider = 0;
			}
			if(isset($formData['video'])){
				$video = 0;
			}
			
			if(isset($formData['pop'])){
				$pop = 0;
			}
			if(isset($formData['inpage'])){
				$inpage = 0;
			}
			// create pages master
			$master_page = new Application_Model_MemberPagesMaster();
			$master_data = array('member_id'=>$formData['member'], 'status'=>$formData['status'], 'pages'=>$formData['pages'], 'price'=>$formData['total_price'], 'page_status'=>'OFFLINE','end_date'=>$formData['end_date'],);
			$mster_page_id = $master_page->addAdminMemberMaster($master_data);
			
			// create page detail
			$page_detail = new Application_Model_Pages();
			for($x=1; $x<=$formData['pages']; $x++){
				$page_detail->addAdminMemberPage(array(
					'master_p_id' => $mster_page_id,
					'member_id' => $formData['member'],
					'marketer_id' => $formData['marketer_id'],
					'expiry_date' => $formData['end_date'],
					'video_hidden' => $video,
					'slider_hidden' => $slider,
					'pop' => $pop,
					'inpage' => $inpage,
					'total_posts' => $formData['pages_post'],
					'notes' => $formData['notes']
				));
			}
			$this->view->msg = "<div class='alert alert-success'>Page has been created successfully!.</div>";
			return;
		}
	}

    public function blockAllAction(){
    $this->noRender();

	$id = $this->_request->getParam('id');
	/* 
	* Update records and make is_block true in following tables 
	members 
	bd_list
	hot_offers
	ho_profile
	*/
	$where = $this->db->quoteInto('member_id = ?', $id);
	$data = array('is_blocked' => 1);
	$this->db->update('members',$data,  $where);
	$this->db->update('bd_list',$data, $where);
	$this->db->update('hot_offers',$data, $where);
	$this->db->update('ho_profile',$data, $where);
	$this->user_session->msg = "Member all records are blocked!";
	$this->_redirect("/admin/members/list");					
	}

	public function unblockAllAction(){
$this->noRender();

	$id = $this->_request->getParam('id');
	/* 
	* Update records and make is_block true in following tables 
	members 
	bd_list
	hot_offers
	ho_profile
	*/
	$where = $this->db->quoteInto('member_id = ?', $id);
	$data = array('is_blocked' => 0);
	$this->db->update('members',$data,  $where);
	$this->db->update('bd_list',$data, $where);
	$this->db->update('hot_offers',$data, $where);
	$this->db->update('ho_profile',$data, $where);
	$this->user_session->msg = "Member all records are unblocked!";
	$this->_redirect("/admin/members/list");					
	}	

	private function noRender(){
        // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();

	}

    // registerd memeber list
    public function indexAction() 
    {
        if(isset($this->user_session->msg)){
            $this->view->msg = $this->user_session->msg; 
            unset($this->user_session->msg);
  		}	
        
        $transactions = new Application_Model_Transactions();
        $this->view->trans_list = $transactions->getAllTransactions();
        
        $member_list = array();
        //$member_model = new Application_Model_RegMembers();
        if($this->user_session->role_id==1) {
            $member_list = $this->members->getAllMembers();
        } else if($this->user_session->role_id==6) {
            $member_list = $this->members->getMembersByUser($this->user_session->user_id);    
        }
        
        $this->view->data = $member_list;
        /*if (count($member_list) > 0) {
    		 $this->Paginator($member_list);
        } else {
            $this->view->empty_rec = true;
    	}*/
    }    
	public function newPageMemberAction(){
		if ($this->_request->isPost()) 
        {
			
			$name = $_POST['name'];
			$role_id = $_POST['role'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$pwd = rand(111111,999999);
			
			
			$is_exist = $this->members->checkEmail($email);
			if ($is_exist)
			{
				$contentArray = Array(
						'content' => 'Email already exist'
					);
				$jsonResults = json_encode($contentArray);
				// you can always send header('Content-Type: application/json'); instead of using simple die function.
				die($jsonResults);
			}else{
			$formData = Array();
			$name = explode(" ", $name, 2);
			$formData['first_name'] = $name[0];
			$formData['last_name'] = $name[1];
			$formData['email'] = $email;
			$formData['contact_number'] = $phone;
			
            $formData['pwd'] = md5($pwd); 
            $formData['parent_id'] = 0;
            $formData['role_id'] = $role_id;            
            $formData['root_id'] = 0;
            $formData['is_verified'] = 1;
            // $arr_email = explode('@',$formData['email']);
            $dir_name = strtolower($name[0]).'_'.rand(11111, 99999);
            $formData['dir_name'] = $dir_name;
            $member_id = $this->members->add($formData);
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
				
			}
			// $this->emailConfig();
                // $subject = "Pageiz Login Details";
                // $body = '
                     // <!DOCTYPE html>
                     // <html>
                      // <head>
                       // <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       // <title>Pageiz Business Page Registration</title>
                       // <style type="text/css">
                            // body {margin: 0; padding: 0; min-width: 100%!important;}
                       // </style>
                      // </head>
                      // <body>
					  // <table>
                        // <tr><td align="left"><img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td></tr>
                       // <tr><td>Hello '.$formData['first_name'].',<br/><br/>Thank You For Joining Us.<br/> <br/>
						// Please refer to login details<br/>
						// Email: ' . $email . ' <br/>
						// Password: ' . $pwd . ' <br/>
                        // <br/>Best Regards<br/><Strong>Pageiz Support Team</Strong><br/></td><tr>
                     // </table>
                      // </body>
                     // </html>';

                // $mail = new Zend_Mail();
                // $mail->setFrom('noreply@pageiz.com', 'Pageiz');
                // $mail->addTo(trim($email), $name[0]);
                // $mail->setSubject($subject);
                // $mail->setBodyHtml($body);
                // $mail->send();
				$contentArray = Array(
						'content' => 'Member has been created successfully!',
						'mem_id' => $member_id,
						'email' => $email,
						'password' => $pwd
					);
				$jsonResults = json_encode($contentArray);
				die($jsonResults);
			}
		}
	}
    public function newAction() 
    {
        $member_form = new Application_Form_MembersForm();
        $this->view->form = $member_form;
        
        if ($this->_request->isPost()) 
        {			   
	       $formData = $this->_request->getPost();
	       
           if (!$member_form->isValid($formData)) {
                $this->view->form = $member_form;
                return;
           }
           
           //$member_model = new Application_Model_RegMembers();
           $is_exist = $this->members->checkEmail($formData['email']);
	       if($is_exist){
	           $this->view->msg = "<div class='alert alert-warning'>".$formData['email']." is already exists.</div>";
                return;
		   }
           
           //For Images
           if($_FILES['photo']['size']>0) {            
            $file_name = NULL;
            try 
            {    
                $photo_name = $_FILES['photo']['name'];
                $random = rand(9,999999);
    
                $file_name = $random . $photo_name;    
                $formData["photo"] = $file_name;
    
                move_uploaded_file($_FILES["photo"]['tmp_name'], SYSTEM_PATH."images/user/members/".$file_name);
                $thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/user/members/".$file_name);
                $thumb->resize(200,200);
                $thumb->save(SYSTEM_PATH."images/user/members/200X200/".$file_name);    
    	   } 
           catch (Zend_File_Transfer_Exception $e)        
           {
              throw new Exception('Bad data: '.$e->getMessage());        
           }
        }
           
         //  $points = $formData["points"];
         //  $amount = $formData["amount"];
           
           unset($formData["amount"]);
           unset($formData["points"]);
           unset($formData["MAX_FILE_SIZE"]);
           unset($formData["Save"]);
           
           //$member_pwd = rand(111111, 99999999); 
           $formData['pwd'] = md5 ($formData['password']);
		   unset($formData["password"]);
           $formData['parent_id'] = 0;
           $formData['user_id'] = $this->user_session->user_id;
           $formData['root_id'] = $this->user_session->user_id;
           $formData['is_verified'] = 1;
          // $formData['brochure_limit'] = 1;
           
           $dir_name = strtolower($formData['first_name']).'_'.rand(11111, 99999);
           $formData['dir_name'] = $dir_name;
           
           $member_id =  $this->members->add($formData);
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
            
                /*$transactions = new Application_Model_Transactions();
                $trans_data = array('member_id'=>$member_id, 'points_debit'=>$points, 'points_credit'=>0, 'amount_debit'=>0, 'amount_credit'=>$amount);
                $transactions->add($trans_data);
                
                $root_deals = new Application_Model_RootDeals();
                $deals_data = array('root_id'=>$member_id, 'trans_date'=>date('Y-m-d'), 'user_id'=>$this->user_session->user_id);
                $root_deals->add($deals_data);*/
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
    
                $this->setEmailConfiguration();
    
                //$subscribers = array($formData['first_name']=>$formData['email']); //'mussawir'=>'mussawir20@gmail.com'
                $mail = new Zend_Mail();
                $mail->setFrom('sales@netefct.com', 'netefct.com');
                $mail->addTo(trim($formData['email']), $formData['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                */
                $this->view->msg =  "<div class='alert alert-success'>Member created successfully!</div>";
                
                $member_form->Reset();
           } else {
                $this->view->msg =  "<div class='alert alert-danger'>Some error occur. Please try again.</div>";
           }
        }
    } // new action end
    
    public function transferPointsAction()
    {
        $member_id = $this->_request->getParam('id');
        $member_name = $this->_request->getParam('name');
        
        $this->view->member_name = $member_name;
                
        if ($this->_request->isPost()) 
        {			   
	       $formData = $this->_request->getPost();
	       
           $transactions = new Application_Model_Transactions();
           $trans_data = array('member_id'=>$member_id, 'points_debit'=>$formData['points'], 'points_credit'=>0);
           $transactions->add($trans_data);
           
           $this->view->msg =  "<div class='alert alert-success'>Points successfully added for <strong>$member_name<strong></div>";
        }
    }
    
    public function transferHistoryAction()
    {
        $member_id = $this->_request->getParam('id');
        $member_name = $this->_request->getParam('name');
        
        $transactions = new Application_Model_Transactions();
        $this->view->data = $transactions->getMemeberTransactions($member_id);
        $this->view->member_name = $member_name;
    }
    
    public function addPagesAction()
    {
        $member_id = $this->_request->getParam('id');
        $member_name = $this->_request->getParam('name');
        
        $this->view->member_name = $member_name;
        
        if ($this->_request->isPost()) 
        {			   
	       $formData = $this->_request->getPost();
           
           $amount = (((int)$formData['pages'])*$formData['amount']);
           $activation_date = date('Y-m-d', strtotime($formData['start_date']));
           $expiry_date = date('Y-m-d', strtotime($formData['end_date']));
           
           // create pages master record
           $master_page = new Application_Model_MemberPagesMaster();
           $master_data = array('member_id'=>$member_id, 'status'=>$formData['status'], 'pages'=>$formData['pages'], 'price'=>$amount, 'page_status'=>'OFFLINE', "start_date" => $activation_date, "end_date" => $expiry_date);
           $mster_page_id = $master_page->addMaster($master_data);
                      
           $is_pages_saved = false;
           $member_pages = new Application_Model_Pages();
           for($x=0; $x<((int)$formData['pages']); $x++)
           {
                $page_data = array('member_id' => $member_id, 'master_p_id'=>$mster_page_id, 'start_date' => $activation_date, 'expiry_date'=>$expiry_date, 'date_created' => date('Y-m-d H:i:s'), 'is_member_pg' => 1, 'is_in_draft' => 1, 'creator_id'=>$this->user_session->user_id);                    
                $is_pages_saved = $member_pages->assingPages($page_data);        
           }
           
           if($is_pages_saved)
           {
                /*$member_data = $this->members->getDetails($member_id);
            
                $subject = "Buy pages - netefct.com";
                $body = '
                 <!DOCTYPE html>
                 <html>
                  <head>
                   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                   <title>Buy pages - netefct.com</title>
                   <style type="text/css">
                        body {margin: 0; padding: 0; min-width: 100%!important;}
                   </style>
                  </head>
                  <body><table><tr><td align="left">
                    <img style="width:200px;" src="'.SYSTEM_PATH.'images/logo.png"/><td>
               		 </tr>
                   <tr><td>Hello ' . $member_data['first_name'] . ' '. $member_data['last_name'] . ',<br/><br/>'.
                   $formData['pages']. ' page(s) assigned to you.<br/>Cost per page: '.$formData['amount'].
                   "<br/>Total amount: ".$amount."<br/>Activation date: ".$formData['start_date']."<br/>Expiry date: ".$formData['end_date']. "<br/>Assigned on : ".date('m/d/Y').
                   '<br/><br/>Best Regards<br/><Strong>netefct.com</Strong></td><tr>
                 </table>
                  </body>
                 </html>';
    
                $this->setEmailConfiguration();
    
                $mail = new Zend_Mail();
                $mail->setFrom('mussawir20@gmail.com', 'netefct.com');
                $mail->addTo(trim($member_data['email']), $member_data['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();*/
           }
           $this->view->msg =  "<div class='alert alert-success'>Pages successfully assigned to <strong>$member_name<strong></div>";
        }
    } // add pages action end
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
    private function setEmailConfiguration()
    {
        require (realpath(dirname(__file__) . '/../../..') . '/library/Zend/Mail/Transport/Smtp.php');
                $config = new Zend_Mail_Transport_Smtp('smtp.gmail.com', array(
                    'auth'     => 'login',
                    'username' => 'valeedmahmood@gmail.com',
                    'password' => '786786786',
                    'port'     => '587',
                    'ssl'      => 'tls'
                ));
                Zend_Mail::setDefaultTransport($config);
    }

}
?>