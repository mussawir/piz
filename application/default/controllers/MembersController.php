<?php

/*
comments 
*/
class MembersController extends Zend_Controller_Action
{
    //following controller level variables and uper init level initializations are common to all default controllers
    private $baseurl = '';
    private $member_session = null;
    private $db = null;
    private $client = null;
    private $cookie = null;
    protected $members = null;
    protected $bdlist = null;
    private $adapter = null;
    private $pages_model = null;
    private $country_session = null;
    private $page_comments = null;
    
    public function init()
    {
        //authorization for this controller
        $auth = Zend_Auth::getInstance();
        //if not loggedin redirect to login page
        if (!$auth->hasIdentity())
        {
            $this->_redirect('/authentication/login');
            return;
        }
        //$this->_helper->layout->setLayout('dashboard');
        $this->members = new Application_Model_Members();
        $this->bdlist = new Application_Model_MemberPages();
        $this->pages_model = new Application_Model_Pages();
        $this->page_comments = new Application_Model_PageComments();
        
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->member_session = new Zend_Session_Namespace("member_session");
        $this->country_session = new Zend_Session_Namespace("country_session");
        
        ini_set("max_execution_time", (60 * 300));
        
        $this->client = new Zend_Http_Client();
        $this->_helper->layout->setLayout('dashboard');

        if (!isset($this->member_session->member_id))
        {
            $this->_redirect("/authentication/login");
            return;
        }
        $this->view->email = $this->member_session->email;
        $this->view->password = $this->member_session->getLpw;
        $this->view->role = $this->member_session->role_id;
        $this->view->logged_member_id = $this->member_session->member_id;
        $this->view->logged_member_name = $this->member_session->first_name . ' ' . $this->member_session->last_name;
                
    } // init function end

	public function parsetagAction(){
		$html = "<html><body><p>some</p><img src='some address' alt='alt'/><img src='some address' alrt=''/><img src='some address' alrt=''/></body></html>";
		preg_match_all('/<img[^>]+>/i',$html, $result); 
	print_r($result);
	
	
	//Store your html into $html variable.
$html="
<html>
<head>
<title>Untitled Document</title>
</head>

<body>
    <a href='http://example.com'>Example</a><br>
    <a href='http://google.com'>Google</a><br>
    
    <a href='http://www.yahoo.com'>Yahoo</a><br>
</body>

</html>";

$dom = new DOMDocument();
$dom->loadHTML($html);

//Evaluate Anchor tag in HTML
$xpath = new DOMXPath($dom);
$hrefs = $xpath->evaluate("/html/body//a");

for ($i = 0; $i < $hrefs->length; $i++) {
        $href = $hrefs->item($i);
        $url = $href->getAttribute('href');

        //remove and set target attribute        
        $href->removeAttribute('target');
        $href->setAttribute("target", "_blank");

        $newURL=$url."/newurl";

        //remove and set href attribute        
        $href->removeAttribute('href');
       $href->setAttribute("href", $newURL);
}
// save html
$html=$dom->saveHTML();
print($html);


	//Store your html into $html variable.
$html="  <img src='abc'/>
   <img src='abc'/>
   <img src='abc'/>
    
";

$dom = new DOMDocument();
$dom->loadHTML($html);

//Evaluate Anchor tag in HTML
$xpath = new DOMXPath($dom);
$hrefs = $xpath->evaluate("/html/body//img");

for ($i = 0; $i < $hrefs->length; $i++) {
        $href = $hrefs->item($i);
        $href->setAttribute("class", "img-responsive");
}
// save html
$html=$dom->saveHTML();
print($html);

	}
	
    public function mainAction()
    {
        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            
            $result = $this->members->updateRole($this->member_session->member_id, $formData['role_id']);
            if($result){
                $auth = Zend_Auth::getInstance();
                $auth->clearIdentity(); #1
                unset($this->member_session->member_id); //on logout unset all sessions values 
                unset($this->member_session);
                $this->_redirect('/index/login');
            } else {
                $this->view->msg = "<div class='alert alert-danger'>Some error occur. Please try again.</div>";
            }
        }
    }

    public function indexAction()
    {
        $this->view->data = $this->pages_model->getPagesByMember($this->member_session->member_id);
        //print_r('<br/><br/>');print_r($this->view->data);
	}

    public function memberListAction()
    {
        //$member_model = new Application_Model_RegMembers();
        $member_list = $this->members->getMembersByParent($this->member_session->member_id);
        $this->view->data = $member_list;
        
        /*if (count($member_list) > 0)
        {
            $this->Paginator($member_list);
        } else
        {
            $this->view->empty_rec = true;
        }*/
    }

    public function newAction()
    {
        /*$transactions = new Application_Model_Transactions();
        $trans_result = $transactions->getTransactions($this->member_session->member_id);
        $debit_point_total =0;
        $credit_point_total =0;
        foreach($trans_result as $tr){
            $debit_point_total += $tr['points_debit'];
            $credit_point_total += $tr['points_credit']; 
        }
        
        $this->view->total_points = $debit_point_total;
        $this->view->member_blc = ($debit_point_total - $credit_point_total);*/
        
        $member_form = new Application_Form_MembersForm();
        $this->view->form = $member_form;

        if ($this->_request->isPost())
        {
            $formData = $this->_request->getPost();

            if (!$member_form->isValid($formData))
            {
                $this->view->form = $member_form;
                return;
            }

            /*if($this->view->member_blc==0){
                $this->view->msg = "<div class='alert alert-info'>You have no points. Please purchase more points to create new mamber.</div>";
                return;
            }*/

            $is_exist = $this->members->checkEmail($formData['email']);
            if ($is_exist)
            {
                $this->view->msg = "<div class='alert alert-warning'>" . $formData['email'] ." is already exists.</div>";
                return;
            }

            //For Images
            if ($_FILES['photo']['size'] > 0)
            {
                $file_name = null;
                try
                {
                    $photo_name = $_FILES['photo']['name'];
                    $random = rand(9, 999999);

                    $file_name = $random . $photo_name;
                    $formData["photo"] = $file_name;

                    move_uploaded_file($_FILES["photo"]['tmp_name'], SYSTEM_PATH . "images/user/members/" .
                        $file_name);
                    $thumb = new Application_Model_Thumbnail(SYSTEM_PATH . "images/user/members/" . $file_name);
                    $thumb->resize(200, 200);
                    $thumb->save(SYSTEM_PATH . "images/user/members/200X200/" . $file_name);
                }
                catch (Zend_File_Transfer_Exception $e)
                {
                    throw new Exception('Bad data: ' . $e->getMessage());
                }
            }
            /*$points = $formData["points"];
            $amount = $formData["amount"];

            unset($formData["amount"]);
            unset($formData["points"]);*/
            unset($formData["MAX_FILE_SIZE"]);
            unset($formData["Save"]);

            $member_pwd = rand(111111, 99999999);
            $formData['pwd'] = md5($member_pwd);
            $formData['parent_id'] = $this->member_session->member_id;
            
            $parent_root_id = $this->members->getRootId($this->member_session->member_id);
            $formData['root_id'] = $parent_root_id['root_id'];

            $dir_name = strtolower($formData['first_name']).'_'.rand(11111, 99999);
            $formData['dir_name'] = $dir_name;
            $formData['is_verified'] = 1;
            $formData['brochure_type'] = 'DEFAULT';
            
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
                
                //
                /*unset($formData['dir_name']);
                unset($formData['root_id']);
                unset($formData['role_id']);
                unset($formData['parent_id']);
                unset($formData['contact_number']);
                unset($formData['pwd']);
                unset($formData['ic_passport']);
                unset($formData['gender']);
                unset($formData['user_id']);
                if(isset($formData['photo'])){
                    unset($formData['photo']);
                }
                
                    $formData['member_id'] = $member_id;*/
                    
                    //$ho_profile = new Application_Model_HoProfile();
				    //$ho_profile->addProfile($formData);
                    
                    // create pages master record
                    $master_page = new Application_Model_MemberPagesMaster();
                    $master_data = array('member_id'=>$member_id, 'status'=>'FREE', 'pages'=>1,'price'=>0, 'page_status'=>'OFFLINE');
                    $mster_page_id = $master_page->add($master_data);
                    
                    $this->pages_model->addMemberPage(array('master_p_id'=>$mster_page_id,'member_id'=>$member_id));
                      
                    //$formData['master_p_id'] = $mster_page_id;
                    //$formData['package'] = 0;                    
                    //$this->bdlist->add($formData);
                      
                         
                // save transaction for parent
                /*$trans_data = null;
                $trans_data = array(
                    'member_id' => $this->member_session->member_id,
                    'points_debit' => 0,
                    'points_credit' => $points);
                $transactions->add($trans_data);*/

                $this->setEmailConfiguration();

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
                        <img style="width:200px;" src="http://pageiz.com/images/logo.png"/><td>
                   		 </tr>
                       <tr><td>Hello ' . $formData['first_name'] . ' ' . $formData['last_name'] .
                    ',<br/><br/>Thank You For Joinging Us.<br/>' .
                    'Following are your login credentials:<br/>Email: <strong>' . $formData['email'] .
                    '</strong><br/>Password: <strong>' . $member_pwd . '</strong>' .
                    '<br/>You can change your password after login. Please click on link to login: <a href="http://pageiz.com/authentication/login" target="_blank">pageiz.com</a>' .
                    '<br/><br/>Best Regards<br/><Strong>pageiz.com</Strong>
                      </td><tr>
                     </table>
                      </body>
                     </html>';

                //$subscribers = array($formData['first_name']=>$formData['email']); //'mussawir'=>'mussawir20@gmail.com'
                $mail = new Zend_Mail();
                $mail->setFrom('noreply@pageiz.com', 'pageiz.com');
                $mail->addTo(trim($formData['email']), $formData['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();

                $this->view->msg = "<div class='alert alert-success'>Member created successfully!</div>";

                $member_form->Reset();
            } else
            {
                $this->view->msg = "<div class='alert alert-danger'>Some error occur. Please try again.</div>";
            }
        }
    } // new action end
    
    public function commentsAction()
    {
        if ($this->_request->isPost())
        {
            $bulkdata = $this->_request->getPost('bulkdata');
            
            for($i=0; $i < count($bulkdata); $i++) {
    		  $id = $bulkdata[$i];    
    		  $this->page_comments->deleteComment($id);
            }
        }
        
        $page_id = $this->_request->getParam('id');
        $result = $this->page_comments->getCommentsByPageID($this->db,$page_id);
        $this->view->data = $result;
    }
    
    public function deleteCommentAction(){
		 $id = $this->_request->getParam('id');
         $page_id = $this->_request->getParam('page_id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
          
            if($this->page_comments->deleteComment($id)){
	           $page = $this->pages_model->getPageComments($page_id);
               $this->pages_model->setPageComment($page_id, ($page['comments']==0?0:$page['comments']-1));
               
		      $this->_redirect("/members/comments/id/".$page_id);					
            } 
		}
		
	public function approveCommentAction(){
		 $id = $this->_request->getParam('id');
         $page_id = $this->_request->getParam('page_id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
          
	     if($this->page_comments->approveComment($this->db, $id))
         {
            $page = $this->pages_model->getPageComments($page_id);
               $this->pages_model->setPageComment($page_id, ($page['comments']+1));
               
		      $this->_redirect("/members/comments/id/".$page_id);
        }
	}
		
	public function rejectCommentAction(){
		 $id = $this->_request->getParam('id');
         $page_id = $this->_request->getParam('page_id');
		  // Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();
	     if($this->page_comments->rejectComment($this->db, $id))
         {
            $page = $this->pages_model->getPageComments($page_id);
            $this->pages_model->setPageComment($page_id, ($page['comments']==0?0:$page['comments']-1));
		   
            $this->_redirect("/members/comments/id/".$page_id);
	       } 
	}
    
    public function commentsDetailsAction()
    {
        
    }
    
    public function pageLikesAction()
    {
        $page_id = $this->_request->getParam('id');
        
        $like_page_model = new Application_Model_LikedPages();
        $this->view->data = $like_page_model->getLikesByPage($this->db, $page_id); 
    }
    
    public function rbarAdsAction()
    {
        if(isset($this->member_session->msg)){
            $this->view->msg = $this->member_session->msg;
            unset($this->member_session->msg);
        }
        
        $page_id = $this->_request->getParam('id');
        $this->view->page_id = $page_id;
        
        $member_ads = new Application_Model_MemberPageAds();
        // if ads saved then show checked
        $this->view->page_ads = $member_ads->getMemberPageAds($page_id, $this->member_session->member_id);
        
        $this->view->ad_limit = $this->members->getMemberAdsLimit($this->member_session->member_id);
        
        $member_ad_list = $this->pages_model->getMembersAds($page_id, $this->member_session->member_id);
        $this->view->ad_list = $member_ad_list;
        
        $this->view->member_dir_name = '/images/uploads/'. $this->member_session->dir_name;
        
        if ($this->_request->isPost()) 
        {
            $formData = $this->_request->getPost();
            
            $is_saved = '';
            for($i=0; $i<count($formData['box_page_id']); $i++){
                $is_saved = $member_ads->add($formData['main_page_id'], $this->member_session->member_id, $formData['box_page_id'][$i], '');
            }
            
            if($is_saved){
                $this->member_session->msg =  "<div class='alert alert-success'><strong>Ads saved successfully!<strong></div>";
            } else {
                $this->member_session->msg =  "<div class='alert alert-danger'><strong>Some error occur. Please try again.<strong></div>";
            }
            $this->_redirect("/members/rbar-ads/id/$page_id");
        }        
    }
    
    public function transferPointsAction()
    {
        if(isset($this->member_session->msg)){
            $this->view->msg = $this->member_session->msg;
            unset($this->member_session->msg);
        }
        
        $member_id = $this->_request->getParam('id');
        $member_name = $this->_request->getParam('name');
        
        $transactions = new Application_Model_Transactions();
        $trans_result = $transactions->getTransactions($this->member_session->member_id); 
        
        $debit_point_total =0;
        $credit_point_total =0;
        foreach($trans_result as $tr){
            $debit_point_total += $tr['points_debit'];
            $credit_point_total += $tr['points_credit']; 
        }
        
        $this->view->total_points = ($debit_point_total - $credit_point_total);
        $this->view->member_name = $member_name;
                
        if ($this->_request->isPost()) 
        {
            if($this->view->total_points==0){
                $this->view->msg = "<div class='alert alert-info'>You do not have points to transfer.</div>";
                return;
            }
            
	       $formData = $this->_request->getPost();
	             
           // debit points to chlid
           $trans_data = array('member_id'=>$member_id, 'points_debit'=>$formData['points'], 'points_credit'=>0);
           $transactions->add($trans_data);
           // credit from parent
           $trans_data =null;
           $trans_data = array('member_id'=>$this->member_session->member_id, 'points_debit'=>0, 'points_credit'=>$formData['points']);
           $transactions->add($trans_data);
           
           $this->member_session->msg =  "<div class='alert alert-success'><strong>".$formData['points']." points successfully tranfered to $member_name<strong></div>";
           
           $this->_redirect("/members/transfer-points/id/$member_id/name/$member_name");
        }
    } // points transfer function end
    
    public function transferHistoryAction()
    {
        $member_id = $this->_request->getParam('id');
        $member_name = $this->_request->getParam('name');
        
        $transactions = new Application_Model_Transactions();
        $this->view->data = $transactions->getMemeberTransactions($member_id);
        $this->view->member_name = $member_name;
    }
    
    public function pageTransferAction()
    {
        if ($this->member_session->msg != null) {
            $this->view->msg = $this->member_session->msg;
            $this->member_session->msg = null;
        }
        
        $member_list = $this->members->getMembersByParent($this->member_session->member_id);
        $member_ddl = '<select id="trans_to" name="trans_to" class="form-control">';
        
        if(count($member_list)>0) {
            foreach($member_list as $ml){
                $member_ddl .= '<option value="'.$ml['member_id'].'">'.$ml['first_name'].' '. $ml['last_name'].'</option>';
            }
        } else {
            $member_ddl .= '<option value="0">-- Select --</option>';
        }
        
        $member_ddl .= '</select>';
        
        $this->view->member_ddl = $member_ddl;
        
        $page_list = $this->pages_model->getPagesByMember($this->member_session->member_id);
        $this->view->data =$page_list;
        
        if ($this->_request->isPost()) 
        {
			$formData = $this->_request->getPost();
            
            $pages_trans_model = new Application_Model_PageTransferHistory();            
            
            $to_member_dir_path = $this->members->getMemberDirectory($formData['trans_to']);
            
            $is_saved= false;
            for($x=0;$x<count($formData['bulk_share']);$x++){
                $page_id = $formData['bulk_share'][$x];
                $page_data = $this->pages_model->getPageBanner($page_id);
                
                $root_id=0;
                $page_root = $pages_trans_model->getTransPageRoot($page_id);
                if(isset($page_root['root_id'])){
                    $root_id = $page_root['root_id'];
                } else {
                    $root_id = $this->member_session->member_id;
                }
                $trans_data = array('page_id'=> $page_id, 'trans_to'=> $formData['trans_to'], 'trans_from'=>$this->member_session->member_id, 'root_id'=>$root_id);
                $is_saved = $pages_trans_model->add($trans_data);
                
                // update page member
                $this->pages_model->updatePageMember($page_id, $formData['trans_to']);
                
                // copy image
                $image_base = SYSTEM_PATH . "images/uploads/";
                copy($image_base.$this->member_session->dir_name.'/'.$page_data['banner_img'], $image_base.$to_member_dir_path['dir_name'].'/'.$page_data['banner_img']);
                unlink($image_base.$this->member_session->dir_name.'/'.$page_data['banner_img']);
                
                copy($image_base.$this->member_session->dir_name.'/200X200/'.$page_data['banner_img'], $image_base.$to_member_dir_path['dir_name'].'/200X200/'.$page_data['banner_img']);
                unlink($image_base.$this->member_session->dir_name.'/200X200/'.$page_data['banner_img']);
                
                copy($image_base.$this->member_session->dir_name.'/500X500/'.$page_data['banner_img'], $image_base.$to_member_dir_path['dir_name'].'/500X500/'.$page_data['banner_img']);
                unlink($image_base.$this->member_session->dir_name.'/500X500/'.$page_data['banner_img']);
            }
            
            if($is_saved){
                $this->member_session->msg = "<div class='alert alert-success'>Page(s) transfered successfully!</div>";
            } else {
                $this->member_session->msg = "<div class='alert alert-danger'>Some error occur. Please try again.</div>";
            }
            
            $this->_redirect("/members/page-transfer");
        }
        
    } // page transfer function end
    
    public function pageMasterAction()
    {
        $master_page = new Application_Model_MemberPagesMaster();
        $this->view->data = $master_page->getMasterPagesByMemeber($this->member_session->member_id);
    }
    
    public function detailPagesAction()
    {
        //$page_master_id = $this->_request->getParam('id');
        
        $this->view->data = $this->pages_model->getPagesByMember($this->member_session->member_id); //getPageByMaster($page_master_id); 
    }
    
    public function plainPageAction()
    {
        $page_id = $this->_request->getParam('id');
        $this->view->page_id = $page_id;
        
        $this->view->page_data = $this->bdlist->getDetails($page_id);
        
        //$timeline_model = new Application_Model_MemberPageTimeline();
        //$this->view->timeline = $timeline_model->getTimelineByMemeberPage($page_id, $this->member_session->member_id);
        
        $this->view->member_dir_name = '/images/uploads/'. $this->member_session->dir_name;
        $_SESSION['member_folder'] = array('is_member'=>true, 'member_dir_path'=> "/images/uploads/". $this->member_session->dir_name);
    }
    
    public function managePageAction()
    {
        if ($this->member_session->msg != null) {
            $this->view->msg = $this->member_session->msg;
            $this->member_session->msg = null;
        }
        
        $page_id = $this->_request->getParam('id');
        $this->view->page_id = $page_id;
        
        $site_info = new Application_Model_SiteInfo();
        $site_info_result = $site_info->getUrls();
    	$this->view->site_url= $site_info_result->site_url;
        
        $this->view->member_dir_name = '/images/uploads/'. $this->member_session->dir_name;
        $_SESSION['member_folder'] = array('is_member'=>true, 'member_dir_path'=> "/images/uploads/". $this->member_session->dir_name.'/images');
        
        $form = new Application_Form_MemberPageForm();
        
		$authorized = $this->pages_model->authorization($page_id,$this->member_session->member_id );  	
		if($authorized){
        		
		}else{
		 		$this->_redirect("/members/detail-pages");	
		}
		
        $result = $this->pages_model->getPageByID($page_id);  	
    	
		$form->banner_link->setValue($result->banner_link);
    	$form->title->setValue($result->title);
    	$form->url_slug->setValue($result->url_slug);
        $form->contents->setValue(stripslashes($result->contents));
    	$form->tags->setValue($result->tags);
        $form->keyword_content->setValue($result->keyword_content);
        $form->description_content->setValue($result->description_content);
        $form->is_comment->setValue($result->is_comment);
        $form->business_name->setValue($result->business_name);
        $form->contact_number->setValue($result->contact_number);
        $form->area_serviced->setValue($result->area_serviced);
        $form->page_description->setValue($result->page_description);
        $form->is_featured->setValue($result->is_featured);
        $form->address->setValue($result->address);
        $form->wap_number->setValue($result->wap_number);
        $form->wechat_number->setValue($result->wechat_number);
        //$form->categories->setValue($result->categories);
        
        //$this->view->page_type = $result->page_type;
        $this->view->errorSlug = $result->url_slug; //set parmalink and url_slug text box 
        $this->view->banner = $result->banner_img;
		$this->view->category = $result->categories;
        $this->view->country_name = $result->country_name;
        $this->view->state_name = $result->state_name;
        $this->view->city_name = $result->city_name;
        $this->view->postcode = $result->postcode;        
        $this->view->country_id = $result->country_id;
        $this->view->state_id = $result->state_id;
        $this->view->city_id = $result->city_id;
        $this->view->pc_id = $result->pc_id;
        
        $this->view->form = $form;
      //do not delete following code bring categories by parent child relation  
     //   $this->view->category_list = $this->getCategoryTree();
         $cat_model = new Application_Model_PageCategories();
		  $this->view->category_list = $cat_model->getAllCategoriesAsc();
        
        if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
	       	
            if (!$form->isValid($formData)) {
    		    $this->view->form = $form;
            } else {
                
                if($this->pages_model->checkPagesSlug($formData['url_slug'], $page_id)){
            		 $this->view->msg =  "<div class='alert alert-danger'><strong>This Page URL Is Already Exist</strong>. Please change to another.</div>";
            		 return;
                }
                
                $formData['date_published']= date("Y-m-d H:i:s");
                //responsive code making section 
				$html = stripslashes($formData['contents']);
				
$dom = new DOMDocument();
$dom->loadHTML($html);

//Evaluate Anchor tag in HTML
$xpath = new DOMXPath($dom);
$imgs = $xpath->evaluate("/html/body//img");
$tbls = $xpath->evaluate("/html/body//table");

for ($i = 0; $i < $imgs->length; $i++) {
        $img = $imgs->item($i);
        $img->setAttribute("class", "img-responsive");
		}
for ($i = 0; $i < $tbls->length; $i++) {
        $tbl = $tbls->item($i);
        $tbl->setAttribute("class", "table table-sm");
		}

// save html
$html= $dom->saveHTML();
$formData['contents'] = ''; 			
$formData['contents'] = $html; 			
// responsive code making ends 
				//print_r($formData['contents']);
				//return;
				
                $formData['dir_name'] = $this->member_session->dir_name;
                
                if($formData['submit'] == "0" ){
    			 
                    $formData['is_in_draft'] = 0;
                    $formData['draft_content'] = stripslashes($formData['contents']);    
        			$result = $this->pages_model->updateMemberPage($formData);
        			$this->member_session->msg = $result;
            	}
            	else 
                {
            		$formData['is_in_draft'] = 1;
					$formData['draft_content'] = stripslashes($formData['contents']);
        			$result = $this->pages_model->updateMemberDraftPage($formData);
        			$this->member_session->msg = $result;
            	}
        		$this->_redirect("/members/manage-page/id/".$page_id);
            }
        }
    } // manage page function end
    
	//Add URL to a new page 
	public function addUrlAction(){
		
		if ($this->member_session->msg != null) {
            $this->view->msg = $this->member_session->msg;
            $this->member_session->msg = null;
        }
		$page_id = $this->_request->getParam('id');
		$this->view->page_id = $page_id;
		//check if the member is sending correct page access parameters
		$authorized = $this->pages_model->authorization($page_id,$this->member_session->member_id );  	
		if($authorized){
        		$this->view->authorized = true; 
		}else{
		 		$this->view->authorized = false;
		}
		$result = $this->pages_model->getPageByID($page_id);  	
		if(strlen($result->url_slug) > 0){
        		$this->view->has_url = true; 
		}else{
		 		$this->view->has_url = false; 	
		}
		$this->view->page_id = $page_id;
        $form = new Application_Form_UrlNewPageForm();
		$this->view->form = $form; 
		 if (!$this->_request->isPost()) return;
		 $formData = $this->_request->getPost();
		
		  if (!$form->isValid($formData)) {
                       $this->view->form = $form;
                       return;
               }
		
		$result = $this->pages_model->newPageURL($formData);
        if($result){
		$this->_redirect("/members/manage-page/id/". $page_id);	
			
		}   
	}
	
    public function createPageAction()
    {
        if ($this->member_session->msg != null) {
            $this->view->msg = $this->member_session->msg;
            $this->member_session->msg = null;
        }
        
        $this->view->member_id = $this->member_session->member_id;
        
        $member_list = $this->members->getMembersByParent($this->member_session->member_id);
        $member_ddl = '<select id="member_id" name="member_id" class="form-control">';
        $member_ddl .= '<option value="'.$this->member_session->member_id.'">Personal</option>';
        
        if($this->member_session->role_id==3) {
            foreach($member_list as $ml){
                $member_ddl .= '<option value="'.$ml['member_id'].'">'.$ml['first_name'].' '. $ml['last_name'].'</option>';
            }
        }
        $member_ddl .= '</select>';
        
        $this->view->member_ddl = $member_ddl;
        
        $transactions = new Application_Model_Transactions();
        $trans_result = $transactions->getTransactions($this->member_session->member_id); 
        
        $debit_point_total =0;
        $credit_point_total =0;
        foreach($trans_result as $tr){
            $debit_point_total += $tr['points_debit'];
            $credit_point_total += $tr['points_credit']; 
        }
        
        $this->view->total_points = ($debit_point_total - $credit_point_total);
        $pages_count = ($this->view->total_points /200);
        $no_of_pages = '<select id="no_of_pages" name="no_of_pages" class="form-control">';
        if($pages_count>0){
            for($x=0; $x<$pages_count; $x++){
                $no_of_pages .= '<option value="'.($x+1).'">'.($x+1).'</option>';
            }
        } else {
            $no_of_pages .= '<option value="0">0</option>';
        }
        
        $no_of_pages .='</select>';
        
        $this->view->no_of_pages_ddl = $no_of_pages;
        
        if ($this->_request->isPost()) 
        {
			$formData = $this->_request->getPost();
            
            if($formData['no_of_pages']==0){
                $this->view->msg = "<div class='alert alert-warning'>You do not have points to create page(s)</div>";
                return;
            }
        
            // create pages master record
                    $master_page = new Application_Model_MemberPagesMaster();
                    $master_data = array('member_id'=>$formData['member_id'], 'status'=>'FREE', 'pages'=>$formData['no_of_pages'],'price'=>0, 'page_status'=>'OFFLINE');
                    $mster_page_id = $master_page->add($master_data);
                    
                    for($i=0;$i<((int)$formData['no_of_pages']); $i++){
                        $this->pages_model->addMemberPage(array('master_p_id'=>$mster_page_id,'member_id'=>$formData['member_id'], 'creator_id'=>$this->member_session->member_id));    
                    }                    
                      
                // save transaction for parent
                $trans_data = array(
                    'member_id' => $this->member_session->member_id,
                    'points_debit' => 0,
                    'points_credit' => $formData['points']);
                $transactions->add($trans_data);
                
            if($formData['member_id']!=$this->member_session->member_id)
            {
                $send_to_member = $this->members->getDetails($formData['member_id']);
                
                $this->setEmailConfiguration();

                $subject = "roodab.com - New Pages";
                $body = '<!DOCTYPE html><html><head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>roodab.com - New Pages</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body><table><tr><td align="left"><img style="width:200px;" src="http://roodab.com/images/netefct/logo.png"/><td></tr>
                       <tr><td>Hello ' . $send_to_member['first_name'] . ' ' . $send_to_member['last_name'] .
                        ',<br/><br/>'.$formData['no_of_pages'].' new page(s) assigned to you by ' . $this->member_session->first_name . ' ' .$this->member_session->last_name.
                        '<br/>Created on : '.date('m/d/Y h:i A', strtotime(date('Y-m-d H:i:s'))).
                        '<br/>You can setup and manage all your pages after login into your account' .
                        '<br/>Login into <a href="http://roodab.com/index/login" target="_blank">roodab.com</a>' .
                        '<br/><br/>Best Regards<br/><Strong>roodab.com</Strong>
                      </td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom('noreply@roodab.com', 'roodab.com');
                $mail->addTo(trim($send_to_member['email']), $send_to_member['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
                // send to page creater
                $subject = "roodab.com - New Pages";
                $body = '<!DOCTYPE html><html><head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>roodab.com - New Pages</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body><table><tr><td align="left"><img style="width:200px;" src="http://roodab.com/images/netefct/logo.png"/><td></tr>
                       <tr><td>Hello ' . $this->member_session->first_name . ' ' . $this->member_session->last_name .                        
                        '<br/>You have just created '.$formData['no_of_pages'].' page(s) and assigned to '.$send_to_member['first_name'] . ' ' . $send_to_member['last_name'].
                        '<br/>Created on : '.date('m/d/Y h:i A', strtotime(date('Y-m-d H:i:s'))).
                        '<br/><br/>Best Regards<br/><Strong>roodab.com</Strong>
                      </td><tr>
                     </table>
                      </body>
                     </html>';

                $mail = new Zend_Mail();
                $mail->setFrom('noreply@roodab.com', 'roodab.com');
                $mail->addTo(trim($this->member_session->email), $this->member_session->first_name);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
            }
            
            $this->member_session->msg = "<div class='alert alert-success'>Pages created successfully!</div>";
            
            $this->_redirect("/members/create-page/");
        }
        
    } // create page function end
    
    public function sharedPagesAction()
    {
        
    }
    
    public function sharePagesAction()
    {
        if ($this->member_session->msg != null) {
            $this->view->msg = $this->member_session->msg;
            $this->member_session->msg = null;
        }
        
        $member_list = $this->members->getMembersByParent($this->member_session->member_id);
        $member_ddl = '<select id="member_id" name="member_id" class="form-control">';
        
        if(count($member_list)>0) {
            foreach($member_list as $ml){
                $member_ddl .= '<option value="'.$ml['member_id'].'">'.$ml['first_name'].' '. $ml['last_name'].'</option>';
            }
        } else {
            $member_ddl .= '<option value="0">-- Select --</option>';
        }
        
        $member_ddl .= '</select>';
        
        $this->view->member_ddl = $member_ddl;
        
        $page_list = $this->pages_model->getPagesByMember($this->member_session->member_id);
        $this->view->data =$page_list;
        
        if ($this->_request->isPost()) 
        {
			$formData = $this->_request->getPost();
            
            $admin_pages_model = new Application_Model_PageAdmins();
            
            $is_saved= false;
            for($x=0;$x<count($formData['bulk_share']);$x++){
                $is_saved = $admin_pages_model->add($formData['bulk_share'][$x], $formData['member_id']);
            }
            
            if($is_saved){
                $this->member_session->msg = "<div class='alert alert-success'>Page(s) shared successfully!</div>";
            } else {
                $this->member_session->msg = "<div class='alert alert-danger'>Some error occur. Please try again.</div>";
            }
        }
    } // share pages function end
    
    public function updateUrlSlugAction()
    {
       $this->ajaxed();
       $url = $this->getRequest()->getParam('url');
       $id = $this->getRequest()->getParam('id');
	   
	   	//check from database if the slug is already in db
		$data["url_slug"]=$url;
		
        $is_exist = $this->pages_model->checkPagesSlug($url, $id); 
		 if($is_exist){
            echo 'exist';
		 } else {
		  
	       //$data = array('url_slug' => $url,'page_id' => $id); 

            $result = $this->pages_model->updateUrlSlug($url, $id);
    
    	    if($result)
            {
                echo 'success';
            }
            else{
                echo 'error';
            }
        }
    } // slug update function end
    
	    public function searchUrlSlugAction()
    {
       $this->ajaxed();
       $url = $this->getRequest()->getParam('url');
       
	   	//check from database if the slug is already in db
		$data["url_slug"]=$url;
		
        $is_exist = $this->pages_model->checkPageSlug($url); 
		 if($is_exist){
            echo 'exist';
		 }else{
		    echo 'success'; 
		 } 
    } // slug update function end

    public function uploadBannerAction()
    {
        $this->ajaxed();
        
        $formData = $this->_request->getPost();
                
        $member_dir = $this->member_session->dir_name;
        
        try {
            if (isset($_FILES['banner']))
            {
                $file = $_FILES['banner']['tmp_name'];
                
                $data = array();
                
                $banner = getimagesize($file);
/*               
			   if($banner[0]>1000 || $banner[0]<1000){
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-warning'><strong>Poster width must be 1000px</strong></div>");
                    echo json_encode($data);
                    return;
                }
                
                if($banner[1]>2000 || $banner[1]<1500){
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-warning'><strong>Poster height must be between 1500px to 2000px</strong></div>");
                    echo json_encode($data);
                    return;
                }
                */
                $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
                                
                if(!empty($formData["old_banner"])){
                    if (file_exists($dir_path . $formData["old_banner"]))
                    {
                        unlink($dir_path . $formData["old_banner"]);
                    }
                    
                    if (file_exists($dir_path .'1000X1000/'. $formData["old_banner"]))
                    {
                        unlink($dir_path .'1000X1000/'. $formData["old_banner"]);
                    }
                     if (file_exists($dir_path .'800X800/'. $formData["old_banner"]))
                    {
                        unlink($dir_path .'800X800/'. $formData["old_banner"]);
                    }
                    if (file_exists($dir_path .'500X500/'. $formData["old_banner"]))
                    {
                        unlink($dir_path .'500X500/'. $formData["old_banner"]);
                    }
                    
                    if (file_exists($dir_path .'300X300/'. $formData["old_banner"]))
                    {
                        unlink($dir_path .'300X300/'. $formData["old_banner"]);
                    }
                    
                    if (file_exists($dir_path .'150X150/'. $formData["old_banner"]))
                    {
                        unlink($dir_path .'150X150/'. $formData["old_banner"]);
                    }
                }

                $path = $_FILES['banner']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = pathinfo($path, PATHINFO_FILENAME);
                $random = rand(111, 999);
                $time = time() + (7 * 24 * 60 * 60);
                $file_name = $file_name . "_" . $time . $random . "." . $ext;
                
                move_uploaded_file($_FILES['banner']['tmp_name'], $dir_path . $file_name);

                $thumb = new Application_Model_Thumbnail($dir_path . $file_name);
                $thumb->resize(1000, 1000);
                $thumb->save($dir_path.'1000X1000/' . $file_name);

				$thumb->resize(800, 800);
                $thumb->save($dir_path.'800X800/' . $file_name);
				
                $thumb->resize(500, 500);
                $thumb->save($dir_path.'500X500/' . $file_name);
                
                $thumb->resize(300, 300);
                $thumb->save($dir_path.'300X300/' . $file_name);
                
                $thumb->resize(150, 150);
                $thumb->save($dir_path.'150X150/' . $file_name);
                
                // save into db
                $result = $this->pages_model->updateBanner($formData['page_id'],$file_name);
                if($result){
                    $data = array('status' => 'success', 'old_banner'=> $file_name, 'banner'=>'<img id="banner-img" src="'.$formData['base_path'].'/images/uploads/'.$member_dir.'/'.$file_name.'" alt="'.$file_name.'" class="img-responsive" />');    
                } else {
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-danger'><strong>Some error occur. Please try again.</strong></div>");
                }
                
                echo json_encode($data);
            }
        } catch (Zend_File_Transfer_Exception $e) {
            $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
        }        
    } // banner action end
    
	
		public function adImageAction(){
		 
        if ($this->member_session->msg != null) {
            $this->view->msg = $this->member_session->msg;
            $this->member_session->msg = null;
        }
        
        $page_id = $this->_request->getParam('id');
        $this->view->page_id = $page_id;
        
        $this->view->member_dir_name = '/images/uploads/'. $this->member_session->dir_name;
        $_SESSION['member_folder'] = array('is_member'=>true, 'member_dir_path'=> "/images/uploads/". $this->member_session->dir_name);
    
		$result = $this->pages_model->getPageByID($page_id);  	
        $this->view->ad_img = $result->ad_img;
	 
	}// ad image function end
	
	//Ad Image Uploading action 
	    public function uploadAdImageAction()
    {
        $this->ajaxed();
        
        $formData = $this->_request->getPost();
                
        $member_dir = $this->member_session->dir_name;
        
        try {
            if (isset($_FILES['adimage']))
            {
                $file = $_FILES['adimage']['tmp_name'];
                
                $data = array();
                
                $ad_image = getimagesize($file);
/*             
			 if($ad_image[0]<300){
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-warning'><strong>Ad Image must be 300px wide<strong></div>");
                    echo json_encode($data);
                    return;
                }
				*/
                
                $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
                                
                if(!empty($formData["old_ad_image"])){
                    if (file_exists($dir_path . $formData["old_ad_image"]))
                    {
                        unlink($dir_path . $formData["old_ad_image"]);
                    }
                    
                    if (file_exists($dir_path .'500X500/'. $formData["old_ad_image"]))
                    {
                        unlink($dir_path .'500X500/'. $formData["old_ad_image"]);
                    }
                 
				 if (file_exists($dir_path .'200X200/'. $formData["old_ad_image"]))
                    {
                        unlink($dir_path .'200X200/'. $formData["old_ad_image"]);
                    }
				}

                $path = $_FILES['adimage']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = pathinfo($path, PATHINFO_FILENAME);
                $random = rand(111, 999);
                $time = time() + (7 * 24 * 60 * 60);
                $file_name = $file_name . "_" . $time . $random . "." . $ext;
                
                move_uploaded_file($_FILES['adimage']['tmp_name'], $dir_path . $file_name);

                $thumb = new Application_Model_Thumbnail($dir_path . $file_name);
                $thumb->resize(500, 500);
                $thumb->save($dir_path.'500X500/' . $file_name);
                $thumb->resize(200, 200);
                $thumb->save($dir_path.'200X200/' . $file_name);
                // save into db
                $result = $this->pages_model->updateAdImage($formData['page_id'],$file_name);
                if($result){
                    $data = array('status' => 'success', 'old_ad_image'=> $file_name, 'adimage'=>'<img id="ad-image" src="'.$formData['base_path'].'/images/uploads/'.$member_dir.'/'.$file_name.'" alt="'.$file_name.'" class="img-responsive" />');    
                } else {
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-danger'><strong>Some error occur. Please try again.<strong></div>");
                }
                
                echo json_encode($data);
            }
        } catch (Zend_File_Transfer_Exception $e) {
            $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
        }        
    } // ad Image action end
    
    public function uploadPhotoAction()
    {
        $this->ajaxed();
        
        $formData = $this->_request->getPost();
                
        $member_dir = $this->member_session->dir_name;
        try {
            if(!empty($formData["old_logo"])){
                if (file_exists(SYSTEM_PATH . "/images/uploads/$member_dir/" . $formData["old_logo"]))
                {
                    unlink(SYSTEM_PATH . "/images/uploads/$member_dir/" . $formData["old_logo"]);
                }
            }
            
            if (isset($_FILES['logo']))
            {
                $file = $_FILES['logo']['tmp_name'];
                $error = false;
                $size = false;

                if (!is_uploaded_file($file) || ($_FILES['logo']['size'] > 1 * 1024 * 1024))
                {
                    $data = array('status' => 'error');
                    $this->view->msg = "<div class='alert alert-warning'>Please upload only files smaller than 1 Mb!</div>";
                    return;
                }
                
                $path = $_FILES['logo']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = pathinfo($path, PATHINFO_FILENAME);
                $random = rand(111, 999);
                $time = time() + (7 * 24 * 60 * 60);
                $file_name = $file_name . "_" . $time . $random . "." . $ext;
                
                move_uploaded_file($_FILES['logo']['tmp_name'], SYSTEM_PATH . "/images/uploads/$member_dir/" . $file_name);
                
                $data = array();
                $result = $this->bdlist->updateLogo($formData['page_id'],$file_name);
                if($result){
                    $data = array('status' => 'success', 'old_logo'=> $file_name, 'logo'=>'<img style="max-width: 100%;max-height: 100%;" id="logo-img" src="'.$formData['base_path'].'/images/uploads/'.$member_dir.'/'.$file_name.'" alt="'.$file_name.'" class="img-responsive" />');    
                } else {
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-danger'><strong>Some error occur. Please try again.<strong></div>");
                }
                
                echo json_encode($data);
            }
        } catch (Zend_File_Transfer_Exception $e) {
            $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
        }        
    } // banner action end
        
    public function renewPagesAction()
    {
        
    }

    public function listAction()
    {


        //unset($this->member_session->branch_msg);
        $results = $this->members->getMembers();
        if (count($results) > 0)
        {
            $this->Paginator($results);
        } else
        {
            $this->view->empty_rec = true;
        }
    }

    private function uploadLogo($logo, $old_logo)
    {
        //clean old logo
        try
        {
            if (file_exists(SYSTEM_PATH . "/images/logos/originals/" . $old_logo))
            {
                unlink(SYSTEM_PATH . "/images/logos/originals/" . $old_logo);
            }

            if (file_exists(SYSTEM_PATH . "/images/logos/250X250/" . $old_logo))
            {
                unlink(SYSTEM_PATH . "/images/logos/250X250/" . $old_logo);
            }

            if (file_exists(SYSTEM_PATH . "/images/logos/100X100/" . $old_logo))
            {
                unlink(SYSTEM_PATH . "/images/logos/100X100/" . $old_logo);
            }

        }
        catch (Zend_File_Transfer_Exception $e)
        {
            $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() .
                "</div>";
        }


        try
        {

            if (isset($_FILES['logo']))
            {
                $file = $_FILES['logo']['tmp_name'];
                $error = false;
                $size = false;

                if (!is_uploaded_file($file) || ($_FILES['logo']['size'] > 1 * 1024 * 1024))
                {
                    $this->view->msg = "<div class='alert alert-danger'>Please upload only files smaller than 1 Mb!</div>";
                    return;
                }
                if (!($size = @getimagesize($file)))
                {
                    $this->view->msg = "<div class='alert alert-danger'>Please upload only images, no other files are supported.</div>";
                    return;
                }
                if (!in_array($size[2], array(
                    1,
                    2,
                    3,
                    7,
                    8)))
                {
                    $this->view->msg = "<div class='alert alert-danger'>Please upload only images of type JPEG.</div>";
                    return;
                }
                if (($size[0] < 25) || ($size[1] < 25))
                {
                    $this->view->msg = "<div class='alert alert-danger'>Please upload an image bigger than 25px.</div>";
                    return;
                }
            }
            $path = $_FILES['logo']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $logo_name = pathinfo($path, PATHINFO_FILENAME);
            $random = rand(10, 10000);
            $time = time() + (7 * 24 * 60 * 60);
            $file_name = $logo_name . "-" . $time . $random . "." . $ext;
            move_uploaded_file($_FILES['logo']['tmp_name'], SYSTEM_PATH .
                "/images/logos/originals/" . $file_name);
            $thumb = new Application_Model_Thumbnail(SYSTEM_PATH .
                "/images/logos/originals/" . $file_name);
            $thumb->resize(250, 250);
            $thumb->save(SYSTEM_PATH . '/images/logos/250X250/' . $file_name);
            $thumb->resize(100, 100);
            $thumb->save(SYSTEM_PATH . '/images/logos/100X100/' . $file_name);
            return $file_name;
        }
        catch (Zend_File_Transfer_Exception $e)
        {
            $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() .
                "</div>";
            return false;
        }
    }

    private function getListCategories($ad_id, $member_id)
    {

    }


    /**
     * @author: Musavir 
     * @description: Manage list entry photos  
     **/

    public function manageLdImagesAction()
    {
        $this->_helper->layout->setLayout('dashboard-ld-images');
        //$this->_helper->layout->setLayout('dashboard');
        
        $ad_id = $this->_request->getParam('id');
        $this->view->member_page_id = $ad_id;
        
        $upload = new Zend_File_Transfer_Adapter_Http();
        $this->view->package_number = $this->member_session->package;
        
        $this->view->images = 'class="active"';
        
        if (isset($this->member_session->add_msg))
        {
            $this->view->msg = $this->member_session->add_msg;
            unset($this->member_session->add_msg);
        }
       
        /* If not package 3 can't add images */
        /*if ($this->member_session->package < 3)
        {
            $this->_redirect('/members/manage-ld/id/' . $ad_id);
            return;
        }*/


        $result = $this->bdlist->getMemberAd( $ad_id);
        $this->view->image1 = $result->image1;
        $this->view->image2 = $result->image2;
        $this->view->image3 = $result->image3;
        $this->view->image4 = $result->image4;
        $this->view->image5 = $result->image5;
        $this->view->image6 = $result->image6;


        return;
        /*$formData['bd_list_id'] = $ad_id;
        $this->member_session->add_msg = $this->bdlist->updateImages($formData);
        $this->_redirect('members/manage-ld-images/id/'.$ad_id);
        */

    }

    public function updateImage1Action()
    {
        $this->ajaxed();
        $image = $this->getRequest()->getParam('image');
        $ad_id = $this->getRequest()->getParam('ad_id');
        $result = $this->bdlist->updateImage1($image, $ad_id);
        echo $result;
    }

    public function updateImage2Action()
    {
        $this->ajaxed();
        $image = $this->getRequest()->getParam('image');
        $ad_id = $this->getRequest()->getParam('ad_id');
        $result = $this->bdlist->updateImage2($image, $ad_id);
        echo $result;
    }

    public function updateImage3Action()
    {
        $this->ajaxed();
        $image = $this->getRequest()->getParam('image');
        $ad_id = $this->getRequest()->getParam('ad_id');
        $result = $this->bdlist->updateImage3($image, $ad_id);
        echo $result;
    }

    public function updateImage4Action()
    {
        $this->ajaxed();
        $image = $this->getRequest()->getParam('image');
        $ad_id = $this->getRequest()->getParam('ad_id');
        $result = $this->bdlist->updateImage4($image, $ad_id);
        echo $result;
    }

    public function updateImage5Action()
    {
        $this->ajaxed();
        $image = $this->getRequest()->getParam('image');
        $ad_id = $this->getRequest()->getParam('ad_id');
        $result = $this->bdlist->updateImage5($image, $ad_id);
        echo $result;
    }

    public function updateImage6Action()
    {
        $this->ajaxed();
        $image = $this->getRequest()->getParam('image');
        $ad_id = $this->getRequest()->getParam('ad_id');
        $result = $this->bdlist->updateImage6($image, $ad_id);
        echo $result;
    }

    public function manageLdPvAction()
    {
        $ad_id = $this->_request->getParam('id');
        $this->view->member_page_id = $ad_id;
        
        $this->adapter = new Zend_File_Transfer_Adapter_Http();
        
        $form = new Application_Form_ManageAdPvForm();
        $this->view->package_number = $this->member_session->package;
        
        $this->view->promo = 'class="active"';

        if (isset($this->member_session->add_msg))
        {
            $this->view->msg = $this->member_session->add_msg;
            unset($this->member_session->add_msg);
        }
        
        /* If not package 3 can't add images */
        /*if ($this->member_session->package < 3)
        {
            $this->_redirect('/members/manage-ld/id/' . $ad_id);
            return;
        }*/

        $result = $this->bdlist->getMemberAd($ad_id);
        $form->video_link->setValue($result->video_link);
        $form->special_offer->setValue($result->special_offer);

        $this->view->promotion_image = $result->promotion_image;

        $this->view->form = $form;


        if (!$this->_request->isPost())
        {
            return;
        }

        $formData = $this->_request->getPost();

        if (!$form->isValid($formData))
        {
            return;
        }


        if ($this->adapter->isValid())
        {
            if (isset($_FILES['promotion_image']))
            {
                $promotion_image = $this->uploadPv($_FILES['promotion_image'], $result->
                    promotion_image);
                $formData['promotion_image'] = $promotion_image;
            }
        } else
        {
            $formData['promotion_image'] = $result->promotion_image;
        }

        $formData['bd_list_id'] = $ad_id;

        if (strlen($formData['video_link']) > 10)
            $v_link = explode('/', $formData['video_link']);
        if ($v_link[3] != "")
        {
            $formData['video_link'] = $v_link[3];
        } else
        {
            $formData['video_link'] = null;
        }


        $this->member_session->add_msg = $this->bdlist->updatePv($formData);
        $this->_redirect('members/manage-ld-pv/id/' . $ad_id);
    }


    private function uploadPv($logo, $old_logo)
    {

        $this->view->promo = 'class="active"';
        //clean old logo
        try
        {
            if (file_exists(SYSTEM_PATH . "/images/logos/originals/" . $old_logo))
            {
                unlink(SYSTEM_PATH . "/images/logos/originals/" . $old_logo);
            }

            if (file_exists(SYSTEM_PATH . "/images/logos/250X250/" . $old_logo))
            {
                unlink(SYSTEM_PATH . "/images/logos/250X250/" . $old_logo);
            }

            if (file_exists(SYSTEM_PATH . "/images/logos/100X100/" . $old_logo))
            {
                unlink(SYSTEM_PATH . "/images/logos/100X100/" . $old_logo);
            }

        }
        catch (Zend_File_Transfer_Exception $e)
        {
            $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() .
                "</div>";
        }


        try
        {

            if (isset($_FILES['promotion_image']))
            {
                $file = $_FILES['promotion_image']['tmp_name'];
                $error = false;
                $size = false;

                if (!is_uploaded_file($file) || ($_FILES['logo']['size'] > 1 * 1024 * 1024))
                {
                    $this->view->msg = "<div class='alert alert-danger'>Please upload only files smaller than 1 Mb!</div>";
                    return;
                }
                if (!($size = @getimagesize($file)))
                {
                    $this->view->msg = "<div class='alert alert-danger'>Please upload only images, no other files are supported.</div>";
                    return;
                }
                if (!in_array($size[2], array(
                    1,
                    2,
                    3,
                    7,
                    8)))
                {
                    $this->view->msg = "<div class='alert alert-danger'>Please upload only images of type JPEG.</div>";
                    return;
                }
                if (($size[0] < 25) || ($size[1] < 25))
                {
                    $this->view->msg = "<div class='alert alert-danger'>Please upload an image bigger than 25px.</div>";
                    return;
                }
            }
            $path = $_FILES['promotion_image']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $logo_name = pathinfo($path, PATHINFO_FILENAME);
            $random = rand(10, 10000);
            $time = time() + (7 * 24 * 60 * 60);
            $file_name = $logo_name . "-" . $time . $random . "." . $ext;
            move_uploaded_file($_FILES['promotion_image']['tmp_name'], SYSTEM_PATH .
                "/images/logos/originals/" . $file_name);
            $thumb = new Application_Model_Thumbnail(SYSTEM_PATH .
                "/images/logos/originals/" . $file_name);
            $thumb->resize(250, 250);
            $thumb->save(SYSTEM_PATH . '/images/logos/250X250/' . $file_name);
            $thumb->resize(100, 100);
            $thumb->save(SYSTEM_PATH . '/images/logos/100X100/' . $file_name);
            return $file_name;
        }
        catch (Zend_File_Transfer_Exception $e)
        {
            $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() .
                "</div>";
            return false;
        }
    }


    public function manageLdDvAction()
    {
        $ad_id = $this->_request->getParam('id');
        $this->view->member_page_id = $ad_id;
        $result = $this->bdlist->getMemberAd( $ad_id);
        $this->view->result = $result;
        $this->view->package_number = $this->member_session->package;
        $this->view->details = 'class="active"';
        //Get Country, State and City Names
        if (isset($result->country_id) && $result->country_id > 0)
        {
            $this->view->country_name = Application_Model_Info::getCountryName($this->db, $result->
                country_id);
        }
        if (isset($result->state_id) && $result->state_id > 0)
        {
            $this->view->state_name = Application_Model_Info::getStateName($this->db, $result->
                state_id);
        }
        if (isset($result->city_id) && $result->city_id > 0)
        {
            $this->view->city_name = Application_Model_Info::getCityName($this->db, $result->
                city_id);
        }
        if (isset($result->pc_id) && $result->pc_id > 0)
        {
            $this->view->postcode = Application_Model_Info::getPostcode($this->db, $result->
                pc_id);
        }

        //get categories


    }


    public function newListEntryAction()
    {


    }


    public function Paginator($results)
    {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }


    public function settingAction()
    {

    }

    //get member's info by member_id
    public function profileAction()
    {
        $this->view->id = $this->member_session->member_id;
        $this->_redirect('members/basic-update');
        //$this->view->memberDetail  = $this->members->getDetails($this->member_session->member_id);
    }


    public function registerAction()
    {
        $this->view->title = "New User Registration";
        $form = new Application_Form_CustomerForm();
        $this->view->form = $form;

        if (!$this->_request->isPost())
        {
            return;
        }

        $formData = $this->_request->getPost();
        if (!$form->isValid($formData))
        {
            return;
        }

        $customer = new Application_Model_Customer();
        // check owner name if aleady there re show the form with change shop name
        $select = $customer->select()->where('email = ?', $formData['email']);
        $row = $customer->fetchRow($select);

        if ($row)
        {
            $this->view->msg = "Email is already register. Click SING IN to login";
            $this->view->form = $form;
            return;
        }

        //exit;

        $customer_id = $customer->insertCustomer($formData); //

        if (!isset($customer_id))
        {
            $this->view->msg = "Some unknown ERROR occured during saving this record. Please try again";
        }


        $this->customer_session->signup_done = true;
        $this->customer_session->customer_id = $customer_id;
        $this->_redirect('index/subscribe');

    }


    public function uploadifyAction()
    {

    }


    public function passUpdateAction()
    {
        $member_id = $this->member_session->member_id;
        $this->view->member_id = $member_id;

        $form = new Application_Form_MemberChangePassForm();
        $this->view->form = $form;
        $this->view->msg = "";

        if (!$this->_request->isPost())
        {
            return;
        }
        $formData = $this->_request->getPost();
        if (!$form->isValid($formData))
        {
            return;
        }
        //All business logics will come here
        if (strcmp($formData['pwd_current'], $formData['pwd']) == 0)
        {
            $this->view->msg = "<div class='alert alert-danger'>Old and New password are same</div>";
            $this->view->form = $form;
            return;
        }

        if (strcmp($formData['pwd'], $formData['pwd_confirm']) != 0)
        {
            $this->view->msg = "<div class='alert alert-danger'>Passwords are not matching</div>";
            $this->view->form = $form;
            return;
        }

        if ($this->members->passUpdate($member_id, $formData['pwd']))
        {
            $this->view->msg = "<div class='alert alert-success'>Password successfully Updated</div>";
        } else
        {
            $this->view->msg = "<div class='alert alert-danger'>Password Update Failed. Try again</div>";

        }

    }

    //for hotoffer coming soon
    public function hocsAction()
    {

    }


    public function deliveryAddressAction()
    {

        if (isset($this->member_session->ud_msg))
        {
            $this->view->msg = $this->member_session->ud_msg;
            unset($this->member_session->ud_msg);
        }

        $member_id = $this->member_session->member_id;
        $this->view->member_id = $member_id;
        $results = $this->members->getDetails($member_id);
        $form = new Application_Form_ProfileDeliveryAddressForm();


        $form->street_address->setValue($results->street_address);

        if ($results->country_id == null || $results->country_id < 1)
        {
            $this->view->add_address = true;
            $form->country->setValue($results->country_id);
            $this->view->form = $form;
            /*  $form->state->setValue($results->state_id);
            $this->view->form = $form;
            $form->city->setValue($results->city_id);
            $this->view->form = $form;
            $form->pc->setValue($results->pc_id); */
            $this->view->form = $form;
        } else
        {
            $this->view->street_address = $results->street_address;
            $this->view->country_name = Application_Model_Countries::getCountryNameStatic($this->
                db, $results->country_id);
            $this->view->state_name = Application_Model_States::getStateStatic($this->db, $results->
                state_id);
            $this->view->city_name = Application_Model_Cities::getCityStatic($this->db, $results->
                city_id);
            $this->view->postcode = Application_Model_Postcodes::getPostcodeStatic($this->
                db, $results->pc_id);
            $this->view->form = $form;
            $this->view->package_number = $this->member_session->package;

        }

        if (!$this->_request->isPost())
        {
            return;
        }

        $formData = $this->_request->getPost();

        if (!$form->isValid($formData))
        {
            $this->view->msg = "not valid";
            return;
        }


        if ($formData['country'] == "--------" || $formData['country'] < 1)
        {
            $this->view->msg = "<div class='alert alert-danger'>Please select all fields of Address Info to update record.</div>";
            return;
        }

        if (!is_object($formData['state']) && ($formData['state'] == "Select State" || $formData['state'] <
            1))
        {
            $this->view->msg = "<div class='alert alert-danger'>Please select all fields of Address Info to update record.</div>";
            return;
        }

        if (!is_object($formData['city_id']) && ($formData['city_id'] == "Select City" ||
            $formData['city_id'] < 1))
        {
            $this->view->msg = "<div class='alert alert-danger'>Please select all fields of Address Info to update record.</div>";
            return;
        }

        if (!is_object($formData['state']) && ($formData['state'] == "Select Postcode" ||
            $formData['pc_id'] < 1))
        {
            $this->view->msg = "<div class='alert alert-danger'>Please select all fields of Address Info to update record.</div>";
            return;
        }

        $formData['member_id'] = $this->member_session->member_id;
        $this->member_session->ud_msg = $this->members->updateAddress($formData);
        $this->_redirect("members/delivery-address/id/" . $formData['member_id']);


    }

    public function billingAddressAction()
    {

    }


    public function basicUpdateAction()
    {
        $this->_helper->layout->setLayout('dashboard');
        $request = $this->getRequest();
        $hop_id = $this->_request->getParam('id');
        $this->view->member_id = $hop_id;
        if ($hop_id == null)
        {
            $hop_id = $this->member_session->member_id;
        }
        $this->view->member_id = $hop_id;
        $this->view->msg = $this->member_session->checkout_info['msg'];
        $this->member_session->checkout_info['msg'] = null;
        $form = new Application_Form_ProfileUpdateBasicForm();
        $this->view->form = $form;
        $formData = $this->_request->getPost();

        if (!$this->_request->isPost())
        {
            if ($hop_id != null)
            {
                $result = $this->members->getDetails($hop_id);

                $form->first_name->setValue($result->first_name);
                $form->last_name->setValue($result->last_name);
                $form->office_contact_number->setValue($result->office_contact_number);

                $form->contact_number->setValue($result->contact_number);
                $this->view->form = $form;
            }
            return;
        }


        if ($form->isValid($formData))
        {

            $formData['member_id'] = $this->member_session->member_id;
            $this->member_session->ud_msg = $this->members->updateBasic($formData);
            $this->_redirect('members/basic-update/' . $hop_id);


        }

        if ($this->members->updateBasic($formData))
        {

            $this->view->msg = "<div class='alert alert-success'>Password successfully Updated</div>";
        } else
        {
            $this->view->msg = "<div class='alert alert-danger'>Password Update Failed. Try again</div>";

        }

    }


    public function changePasswordAction()
    {
        $this->view->title = "Change Password";
        $form = new Application_Form_Default_CustomerChangePassForm(array('disableLoadDefaultDecorators' => true));
        $customer = new Application_Model_Default_Customer();
        $form->submit->setAttrib("class", "cr-acnt-btn");
        $this->view->form = $form;
        $changing_pass = $customer->getOldPassword($this->customer_session->customer_id);
        if (!$this->_request->isPost())
        {
            return;
        }
        $formData = $this->_request->getPost();
        if (!$form->isValid($formData))
        {
            return;
        }
        $old_password = $formData['old_password'];
        $password = $formData['password'];
        $re_password = $formData['re_password'];
        //echo $old_password."<br>....";$changing_pass; die;
        //first checking old-password with the database password
        if ((md5($old_password) != $changing_pass))
        {
            $this->view->usermsg = "Old Password does not matches";
            $this->view->form = $form;
            return;
        }
        //check password and repassword
        if (strcmp($formData['password'], $formData['re_password']) != 0)
        {
            $this->view->usermsg = "Password and Re-Password not matching";
            $this->view->form = $form;
            return;
        }
        //remove unwanted data from the form as submit button
        unset($formData['submit']);
        //					$this->logger->log($country_id, Zend_Log::INFO);

        $customer->update(array('password' => md5($password)), 'customer_id = ' . $this->
            customer_session->customer_id); //update customer addrees id that is it primary
        $this->view->usermsg =
            "<div><span style='color:green'>Your Password is changed</span></div>";
    } //change password end here


    //logout
    public function logoutAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity(); #1
        unset($this->member_session->customer_id);
        $this->_redirect('/index');
    }

    public function uptAction()
    {
        $this->ajaxed();


    }


    public function addListingAction()
    {
        //show message if it is set true
        if (isset($this->member_session->msg))
        {
            $this->view->msg = $this->member_session->msg;
            unset($this->member_session->msg);
        }

        $package = $this->_request->getParam("id");
        $this->member_session->package = $package;

        $form = new Application_Form_AddListingForm();
        $this->view->form = $form;

        $form->submit->setLabel("Next");


        if (!$this->_request->isPost())
        {
            $this->view->form = $form;
            return;
        }

        $formData = $this->_request->getPost();
        if (!$form->isValid($formData))
        {
            $this->view->form = $form;
            return;
        }

        $package = $formData['package'];
        //var_dump($package);
        //return;
        if ($package == 0)
        {
            $is_free = $this->bdlist->getFreeList($this->member_session->member_id);
            if ($is_free)
            {
                $this->view->is_free = true;
            } else
            {
                $this->view->is_free = false;
                $this->view->msg = "<div style='color: red; font-weight: bold'>Sorry you can't create another free listing advertisement</div>";
                return;
            }
        }
        
        if ($package == 1)
        {
            $formData['subtotal'] = '1';
        } 
        elseif ($package == 2)
        {
            $formData['subtotal'] = '2';
        } 
        elseif ($package == 3)
        {
            $formData['subtotal'] = '5';
        } else
        {
            $formData['subtotal'] = '0';
            $this->view->is_free = true;
        }

        if (!$this->view->is_free)
        { // is_free nested if condition

            //check promotion code validation
            $promocode = new Application_Model_Promocodes();
            if (strlen(trim($formData['promocode'])) > 7)
            {
                $result = $promocode->checkPromocode(trim($this->member_session->email), trim($formData['promocode']),
                    1);
                if ($result["found"] == 1)
                {

                    if ($result["is_used"] == 1)
                    {
                        $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry this promocode is already used! 
<br/>To proceed please clear promocode field.
<STRONG></div>";
                        return;
                    }

                    if ($result["is_expired"] == 1)
                    {
                        $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry this promocode is expired! <STRONG>
<br/>To proceed please clear promocode field.</div>";
                        return;
                    }

                    if ($result["value"] > 25 && $package == 2)
                    {
                        $this->view->msg = "<div class='alert alert-danger'><Strong>Please chose A$150 package to proceed<STRONG></div>";
                        return;
                    }

                    if ($result["value"] > 150 && $package == 3)
                    {
                        $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry wrong value promocode. <STRONG>
<br/>To proceed please clear promocode field.</div>";
                        return;
                    }
                } else
                {
                    $this->view->msg = "<div class='alert alert-danger'><Strong>Please check! Have you entered right promocode? Enter correct promocode<br/> We have not found this promocode. To proceed please clear promocode field. <STRONG></div>";
                    return;
                }
            }
        } // is_free ends here

        /*if ($this->view->is_free)
        {
            $formData['role_id'] = 2;
        } else
        {
            $formData['role_id'] = 1;
        }*/

        $formData['email'] = $this->member_session->member_email;
        $formData['package'] = $package;
        $this->member_session->checkout_info = $formData;
        $this->member_session->checkout_info['email'] = $this->member_session->email;

        if ($this->view->is_free)
        {
            $this->_redirect("/members/free-ad-summary");
        } else
        {
            $this->_redirect("/members/payment-summary");
        }

    }


    public function paymentSummaryAction()
    {
        if (!isset($this->member_session->checkout_info))
        {
            $this->helper->_redirect("index");
        }
        //get promocode discount
        $code = trim($this->member_session->checkout_info['promocode']);
        $discount = 0;
        if (isset($code) && strlen($code) > 7)
        {
            $promocode = new Application_Model_Promocodes();
            $email = $this->member_session->email;
            $result = $promocode->checkPromocode($email, $code, 1);
            if ($result["found"] == 1)
            {
                $discount = $result["value"];
            } else
            {
                $this->view->msg = "<div class='alert alert-danger'><Strong>Your promocode is not correct<STRONG></div>";
            }

        }
        //$this->view->msg = $discount;
        $this->member_session->checkout_info['discount'] = $discount;
        if ($this->member_session->checkout_info['package'] == 1)
        {
            $subtotal = 1;
        } else if ($this->member_session->checkout_info['package'] == 2)
        {
            $subtotal = 2;
        } else if ($this->member_session->checkout_info['package'] == 3)
        {
            $subtotal = 5;
        }
        $this->member_session->checkout_info['total_payment'] = ($subtotal - $discount);
        $this->view->pp_info = $this->member_session->checkout_info;

    }

    public function packagePaymentAction()
    {
        if (!isset($this->member_session->checkout_info))
        {
            $this->_redirect("index/login");
        }

        $this->view->pp_info = $this->member_session->checkout_info;

        /*if($this->member_session->checkout_info['package'] == 2){
        $this->view->payment_amount = 25;
        // $this->view->payment_amount = ;
        }
        if($this->member_session->checkout_info['package'] == 3){
        $this->view->payment_amount = 150;
        //$this->view->payment_amount = 0.1;
        }*/
    }

    public function paymentCancelledAction()
    {

    }

    public function paymentFailedAction()
    {

    }


    public function paymentCompletedAction()
    {
        if (!isset($this->member_session->checkout_info))
        {
            $this->_redirect("/members/index");
            return;
        }
        $email = $this->_request->getParam("1");
        $first_name = $this->_request->getParam("2");
        $last_name = $this->_request->getParam("3");
        //$this->view->msg = $email . " " . $first_name . " " . $last_name;
        $email = $this->member_session->checkout_info['email'];
        $first_name = $this->member_session->checkout_info['first_name'];
        $last_name = $this->member_session->checkout_info['last_name'];
        $business_name = $this->member_session->checkout_info['business_name'];
        $promocode = $this->member_session->checkout_info['promocode'];
        $role_id = 2;
        $package = $this->member_session->checkout_info['package'];
        $promotion_tbl = new Application_Model_Promocodes();

        $formData = array(
            "member_id" => $this->member_session->member_id,
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "role_id" => $role_id,
            "business_name" => $business_name,
            "promocode" => $promocode);
        //$member_id =	$this->members->addMember($formData);
        if (isset($this->member_session->member_id))
        {
            $formData['member_id'] = $this->member_session->member_id;
            $formData['package'] = $package;
            $this->bdlist->add($formData);
            $promotion_tbl->markUsed($promocode);

            //				$this->sendEmailPayment($first_name,$last_name, $email, $this->member_session->checkout_info['total_payment'] );

            $subject = "Thank you for adding a new listing in Small Business Support Link.";
            $body = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Small Business Support Link</title>
        <style type="text/css">
        body {margin: 0; padding: 0; min-width: 100%!important;}
        </style>
    </head>
    <body><table><tr><td align="right"><img src="http://sbsupportlink.com.au/images/website/sbsl-email-logo.png"/><td>
     </tr>
     <tr><td>Hi,</td></tr>
 <tr><td><strong>TAX INVOICE<strong><td></tr>
    <tr><td>SB Support Link Pty Ltd<td></tr>
    <tr><td>ACN 162 046 200<td></tr>
    <tr><td>' . date("F j, Y") . '<td></tr>
<tr><td>Invoice To: </td><tr>

<tr><td>' . trim($this->member_session->checkout_info['email']) . '</td></tr>
<tr><td>Invoice for adding another business listing in Small Business Support Link</td></tr>
<tr><td>Total Payment: $' . $this->member_session->checkout_info['total_payment'] .
                ' AUD</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Small Business Support Link
 </td></tr>
</table>
    </body>
</html>';

            $mail = new Zend_Mail();
            $mail->setFrom('sales@sbsupportlink.com.au', 'sbsupportlink.com.au');
            $mail->addTo(trim($this->member_session->checkout_info['email']), '');
            $mail->setSubject($subject);
            $mail->setBodyHtml($body);
            $mail->send();


            $subject = "A new paid listing of $" . $this->member_session->checkout_info['total_payment'] .
                " AUD";
            $body = "New paid listing of $" . $this->member_session->checkout_info['total_payment'] .
                "AUD is added by " . trim($this->member_session->checkout_info['email']);

            $mail1 = new Zend_Mail();
            $mail1->setFrom("sales@sbsupportlink.com.au", "sbsupportlink.com.au");
            $mail1->addTo("sales@sbsupportlink.com.au", 'sbsupportlink.com.au');
            $mail1->setSubject($subject);
            $mail1->setBodyHtml($body);
            $mail1->send();


            $this->view->pp_info = $this->member_session->checkout_info;
            $this->view->msg = "<div class='alert alert-success'><Strong>Thank you for adding another business listing.<STRONG></div>";
            unset($this->member_session->checkout_info);
        }
    }


    public function freeAdSummaryAction()
    {
        if (!isset($this->member_session->checkout_info))
        {
            $this->helper->_redirect("index");
        }
        $this->member_session->checkout_info['total_payment'] = '0.00';
        $this->view->pp_info = $this->member_session->checkout_info;

    }


    public function freeAdListedAction()
    {

        if (!isset($this->member_session->checkout_info))
        {
            $this->_redirect("/members/index");
            return;
        }

        $email = $this->member_session->checkout_info['email'];
        $first_name = $this->member_session->checkout_info['first_name'];
        $last_name = $this->member_session->checkout_info['last_name'];
        $business_name = $this->member_session->checkout_info['business_name'];
        $promocode = $this->member_session->checkout_info['promocode'];
        $role_id = 1;
        $package = $this->member_session->checkout_info['package'];

        $formData = array(
            "member_id" => $this->member_session->member_id,
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "role_id" => $role_id,
            "business_name" => $business_name,
            "promocode" => $promocode);
        //$member_id =	$this->members->addMember($formData);
        if (isset($this->member_session->member_id))
        {
            $formData['member_id'] = $this->member_session->member_id;
            $formData['package'] = $package;
            $this->bdlist->add($formData);
            $this->view->pp_info = $this->member_session->checkout_info;
            $this->view->msg = "<div class='alert alert-success'><Strong>Thank you for adding your free  advertisement listing.<STRONG></div>";
            unset($this->member_session->checkout_info);
        }
    }


    private function sendEmailPayment($first_name, $last_name, $email, $total_payment)
    {
        /* email sending section */
        $mail = new Zend_Mail();
        $mail->setFrom('sales@sbsupportlink.com', 'sbsupportlink.com.au');
        $mail->addTo($email, $first_name);
        $mail->setSubject("Payment Confirmation");
        $mail->setBodyHtml("Hi " . $first_name . "<p> We have received your payment A$" .
            $total_payment . " by Paypal</p> ");
        $mail->send(); //on server mail will be sent
    }

    public function upgradeListingAction()
    {
        //show message if it is set true
        if (isset($this->member_session->msg))
        {
            $this->view->msg = $this->member_session->msg;
            unset($this->member_session->msg);
        }
        unset($this->member_session->checkout_info);

        //$this->view->msg = $this->member_session->email . "email";
        $bd_list_id = $this->_request->getParam("id1");
        $c_package = $this->_request->getParam("id2");
        $this->member_session->c_package = $c_package;
        $this->view->c_package = $c_package;
        
        $form = new Application_Form_AddListingForm();
        $form->removeElement("first_name");
        $form->removeElement("last_name");
        $form->removeElement("business_name");

        $this->view->form = $form;

        $form->submit->setLabel("Next");

        if (!$this->_request->isPost())
        {
            $this->view->form = $form;
            return;
        }

        $formData = $this->_request->getPost();
        if (!$form->isValid($formData))
        {
            $this->view->form = $form;
            return;
        }

        //check promotion code validation
        $promocode = new Application_Model_Promocodes();
        $package = $formData['package'];

        if (strlen(trim($formData['promocode'])) > 7)
        {
            $this->member_session->checkout_info['promocode'] = trim($formData['promocode']);

            $result = $promocode->checkPromocode(trim($this->member_session->email), trim($formData['promocode']),
                1);
            if ($result["found"] == 1)
            {

                if ($result["is_used"] == 1)
                {
                    $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry this promocode is already used! 
<br/>To proceed please clear promocode field.
<STRONG></div>";
                    return;
                }

                if ($result["is_expired"] == 1)
                {
                    $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry this promocode is expired! <STRONG>
<br/>To proceed please clear promocode field.</div>";
                    return;
                }

                if ($result["value"] > 25 && $package == 2)
                {
                    $this->view->msg = "<div class='alert alert-danger'><Strong>Please chose A$150 package to proceed<STRONG></div>";
                    return;
                }

                if ($result["value"] > 150 && $package == 3)
                {
                    $this->view->msg = "<div class='alert alert-danger'><Strong>Sorry wrong value promocode. <STRONG>
<br/>To proceed please clear promocode field.</div>";
                    return;
                }

            } else
            {
                $this->view->msg = "<div class='alert alert-danger'><Strong>Please check! Have you entered right promocode? Enter correct promocode<br/> We have not found this promocode. To proceed please clear promocode field. <STRONG></div>";
                return;
            }
        }

        $formData['role_id'] = 2;
        $formData['email'] = $this->member_session->email;
        $formData['package'] = $package;
        $formData['bd_list_id'] = $bd_list_id;
        $this->member_session->checkout_info = $formData;
        
        $this->_redirect("/members/payment-summary-pu");
    }


    public function paymentSummaryPuAction()
    {
        if (!isset($this->member_session->checkout_info))
        {
            $this->helper->_redirect("index");
        }
        //get promocode discount
        $code = $this->member_session->checkout_info['promocode'];
        $discount = 0;
        if (isset($code) && strlen(trim($code)) > 7)
        {
            $promocode = new Application_Model_Promocodes();
            $email = $this->member_session->email;
            $result = $promocode->checkPromocode($email, $code, 1);
            if ($result["found"] == 1)
            {
                $discount = $result["value"];
            } else
            {
                $this->view->msg = "<div class='alert alert-danger'><Strong>Your promocode is not correct<STRONG></div>";
            }
        }

        //$this->view->msg = $discount;

        $this->member_session->checkout_info['discount'] = $discount;
        if ($this->member_session->checkout_info['package'] == 1)
        {
            $subtotal = 1;
        } else if ($this->member_session->checkout_info['package'] == 2)
        {
            $subtotal = 2;
        } else if ($this->member_session->checkout_info['package'] == 3)
        {
            $subtotal = 5;
        }
        
        $this->member_session->checkout_info['total_payment'] = ($subtotal - $discount);
        $this->view->pp_info = $this->member_session->checkout_info;

    }

    public function packagePaymentPuAction()
    {
        if (!isset($this->member_session->checkout_info))
        {
            $this->_redirect("index/login");
        }
        if ($this->member_session->checkout_info['package'] == 1)
        {
            $this->view->payment_amount = 1;
        }
        if ($this->member_session->checkout_info['package'] == 2)
        {
            $this->view->payment_amount = 2;
        }
        if ($this->member_session->checkout_info['package'] == 3)
        {
            $this->view->payment_amount = 5;
        }
        
        $this->view->pp_info = $this->member_session->checkout_info;
    }

    public function paymentCancelledPuAction()
    {

    }


    public function paymentCompletedPuAction()
    {

        if (!isset($this->member_session->checkout_info))
        {
            $this->_redirect("/members/index");
            return;
        }

        $email = $this->_request->getParam("1");
        $first_name = $this->_request->getParam("2");
        $last_name = $this->_request->getParam("3");
        //$this->view->msg = $email . " " . $first_name . " " . $last_name;
        $bd_list_id = $this->member_session->checkout_info['bd_list_id'];
        $promocode = $this->member_session->checkout_info['promocode'];
        $package = $this->member_session->checkout_info['package'];
        
        if ($this->member_session->checkout_info['package'] == 1)
        {
            $no_of_pages = 1;
        }
        if ($this->member_session->checkout_info['package'] == 2)
        {
            $no_of_pages = 5;
        }
        if ($this->member_session->checkout_info['package'] == 3)
        {
            $no_of_pages = 10;
        }
                
        $promotion_tbl = new Application_Model_Promocodes();
        $formData = array(
            "bd_list_id" => $bd_list_id,
            "member_id" => $this->member_session->member_id,
            "package" => $package,
            "no_of_pages" => $no_of_pages,
            "promocode" => $promocode);
        //$member_id =	$this->members->addMember($formData);
        if (isset($this->member_session->member_id))
        {
            //$formData['member_id'] = $this->member_session->member_id;
            $this->bdlist->updatePackage($formData);
            
            $promotion_tbl->markUsed($promocode);
            $this->sendEmailPu($this->member_session->first_name, $this->member_session->
                last_name, $this->member_session->email, $package, $this->member_session->
                checkout_info['total_payment']);
            $this->member_session->checkout_info['first_name'] = $this->member_session->
                first_name;
            $this->member_session->checkout_info['last_name'] = $this->member_session->
                last_name;

            $this->view->pp_info = $this->member_session->checkout_info;
            $this->view->msg = "<div class='alert alert-success'><Strong>Thank you for upgrading your advertisement.<STRONG></div>";
            unset($this->member_session->checkout_info);
        }

    }

    private function sendEmailPu($first_name, $last_name, $email, $package, $total_payment)
    {
        /* email sending section */

        $package_name = null;
        if ($package == 1)
        {
            $package_name = 'RM 1';
        }
        if ($package == 2)
        {
            $package_name = 'RM 2';
        }
        if ($package == 3)
        {
            $package_name = 'RM 5';
        }
        
        

        $mail = new Zend_Mail();
        $mail->setFrom('sales@likelinkr.com', 'likelinkr.com');
        $mail->addTo($email, $first_name);
        $mail->setSubject("Payment Confirmation");
        $mail->setBodyHtml("Hi " . $first_name . "<p> We have received your payment RM " .
            $total_payment . " by Paypal and we have update your package to " . $package_name ."</p> ");
        $mail->send(); //on server mail will be sent
    }


    public function removeAdCatAction()
    {
        $this->view->cat_id = $this->_request->getParam("id");
        $this->view->ad_id = $this->member_session->ad_id;
        $this->view->finished = false;
        if (!$this->_request->isPost())
        {
            return;
        }

        $mlc = new Application_Model_MemberListCats();
        $result = $mlc->deleteRecord($this->view->cat_id, $this->view->ad_id);

        $this->view->msg = "<div style='font-weight: bold; color: dark-green'>Category Removed</div>";
        $this->view->finished = true;

    }

    public function removeDirectoryAction()
    {
        $bdlist_id = $this->_request->getParam("id");
        $this->view->bdlist_id = $bdlist_id;


        if ($bdlist_id != 0)
        {
            $result = $this->bdlist->getDetails($bdlist_id);
            $this->view->finished = false;
            $this->view->first_name = $result->first_name;
            $this->view->last_name = $result->last_name;
            $this->view->business_name = $result->business_name;
            $this->view->package = $result->package;
            $this->view->expiry_date = $result->expiry_date;
            $this->view->email = $result->email;
        }

        if (!$this->_request->isPost())
        {
            return;
        }

        $result = $this->bdlist->deleteRecord($bdlist_id);

        $this->view->msg = "<div class='alert alert-success'>Directory Removed</div>";
        $this->view->finished = true;
        $this->_redirect("/members/remove-directory/id/0");
    }

    public function publishPageAction()
    {
        $member_page_id = $this->_request->getParam("id");
        $master_page_id = $this->_request->getParam("m_id");
        
        $master_page = new Application_Model_MemberPagesMaster();
        
        // update page master
        $master_page->updateMasterPage($master_page_id);
        
        // update page detail
        $this->bdlist->addExpireDate($member_page_id);
        
        $master_data = $master_page->getMemeberMasterPages($this->member_session->member_id);
        $page_count=0;
        foreach($master_data as $md){
            $page_count += $md['pages'];
        }
        
        if($page_count==1){
            $master_data = array('member_id'=>$this->member_session->member_id, 'status'=>'FREE', 'pages'=>4,'price'=>0, 'page_status'=>'OFFLINE');
            $mster_page_id = $master_page->add($master_data);
            
            for($x=0; $x<4;$x++){                
                $page_data = array('member_id' => $this->member_session->member_id, 'email' => '', 'package'=> 1,'master_p_id'=>$mster_page_id);                
                $this->bdlist->add($page_data);
            }
        }
        
        $this->_redirect('/members/page-master');
    }

    public function getStateAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $this->country_session->country_id = $country_id;
        $this->results = Application_Model_Countries::getState($this->db, $country_id);
        echo $this->results;
    } // getState function end

    public function getCitiesAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $state_id = $this->getRequest()->getParam('state_id');
        $this->country_session->country_id = $country_id;
        $this->results = Application_Model_Countries::getCities($this->db, $country_id,$state_id);
        echo $this->results;
    } // getCities function end
    
    public function getPostcodesAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $state_id = $this->getRequest()->getParam('state_id');
		$city_id = $this->getRequest()->getParam('city_id');

        //$cities = new Application_Model_Cities();
        //$city_info = $cities->getCityById($city_id);

	    //$this->results = Application_Model_Countries::getPostcodesByAreaName($this->db, $country_id,$state_id,$city_info['city_name']);
        $this->results = Application_Model_Countries::getPostcodesByAreaName($this->db, $country_id,$state_id,$city_id);
        echo $this->results;
    } // getPostcodes function end

    public function uploadBrochureAction()
    {
        if ($this->member_session->msg != null) {
            $this->view->msg = $this->member_session->msg;
            $this->member_session->msg = null;
        }
        
        $form = new Application_Form_BrochureUploadForm();
        $this->view->form = $form;
        
        $member_pages = $this->pages_model->getMemberPages($this->member_session->member_id);
        $pages_ddl = '';
        foreach($member_pages as $mp){
            $pages_ddl .= "<option value=".$mp['page_id'].">".$mp['title']."</option>";
        }
        $this->view->pages_ddl = $pages_ddl;
        
        if($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if (!$form->isValid($formData))
            {
                return;
            }            
            
            $member_dir = $this->member_session->dir_name;
            $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
            
            $brochure_model = new Application_Model_MemberBrochures();
            
            $page_brochure = $brochure_model->getBrochureByPage($this->member_session->member_id, $formData['page_id']);
            if(isset($page_brochure))
            {
                if (file_exists($dir_path . $page_brochure["brochure"]))
                {
                    unlink($dir_path . $page_brochure["brochure"]);
                }
                
                try {
                    if (isset($_FILES['brochure']))
                    {
                        //Set the time out
                        set_time_limit(0);
                        
                        $path = $_FILES['brochure']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $file_name = pathinfo($path, PATHINFO_FILENAME);
                        $time = time() + (7 * 24 * 60 * 60);
                        $file_name = $time.'_'.$file_name.'.'.$ext;
                        
                        move_uploaded_file($_FILES['brochure']['tmp_name'], $dir_path . $file_name);
                        
                        // update in db
                        $is_saved = $brochure_model->updateBrochure($page_brochure['m_brochuer_id'], $file_name);
                        if($is_saved)
                        {
                            $this->member_session->msg = "<div class='alert alert-success'>Brochure uploaded successfully!</div>";
                        } else {
                            $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                        }
                        
                        $this->_redirect("/members/upload-brochure");
                    }
                } catch (Zend_File_Transfer_Exception $e) {
                    $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                    return;
                }
            }
            
            // check if more then one upload allowed
            $brochure_limit = $this->members->getBrochureType($this->member_session->member_id);
            $brochure_count = $brochure_model->getMemberPageBrochures($this->member_session->member_id, $formData['page_id']);
            if($brochure_limit['brochure_type']=='DEFAULT' && count($brochure_count)==1){
                $this->view->msg = "<div class='alert alert-info'>Please buy package to upload more brochures.</div>";
                return;
            }
            
            try {
                if (isset($_FILES['brochure']))
                {
                    //Set the time out
                    set_time_limit(0);
                    
                    $path = $_FILES['brochure']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $file_name = pathinfo($path, PATHINFO_FILENAME);
                    $time = time() + (7 * 24 * 60 * 60);
                    $file_name = $time.'_'.$file_name.'.'.$ext;
                    
                    move_uploaded_file($_FILES['brochure']['tmp_name'], $dir_path . $file_name);
                    
                    // save in db
                    $is_saved = $brochure_model->add(array('member_id'=>$this->member_session->member_id, 'brochure'=>$file_name, 'image'=>null, 'page_id'=>$formData['page_id']));
                    if($is_saved)
                    {
                        $this->member_session->msg = "<div class='alert alert-success'>Brochure uploaded successfully!</div>";
                    } else {
                        $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                    }
                    
                    $this->_redirect("/members/upload-brochure");
                }
            } catch (Zend_File_Transfer_Exception $e) {
                $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            }
        }
    } // brochure funciton end
    
    public function brochureListAction()
    {
        
    }
    
    public function deleteBrochureAction()
    {
        $this->ajaxed();
        
        $page_id = $this->getRequest()->getParam('page_id');
        
        $brochure_model = new Application_Model_MemberBrochures();
        
        $page_brochure = $brochure_model->getBrochureByPage($this->member_session->member_id, $page_id);
        if(isset($page_brochure))
        {
            $member_dir = $this->member_session->dir_name;
            $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
            
            if (file_exists($dir_path . $page_brochure["brochure"]))
            {
                unlink($dir_path . $page_brochure["brochure"]);
            }
        }
        
        $result = $brochure_model->deleteBrochure($this->member_session->member_id, $page_id);
        echo $result;
    }
    
    private function getCategoryTree($parent_id = 0, $spacing = '', $tree_array = '')
    {
        $cat_model = new Application_Model_PageCategories();
        $result = $cat_model->getCategoriesByParent($parent_id);
        
        if (!is_array($tree_array)){
            $tree_array = array();
        }
        
        if (isset($result)) {
            if (is_array($result)){
                sort($result);
            }
            foreach($result as $r) {
                $tree_array[] = array("category_id" => $r['category_id'], "category_name" => ($spacing . $r['category_name']));
                $tree_array = $this->getCategoryTree($r['category_id'], ('&nbsp;&nbsp;'.$spacing . '-&nbsp;'), $tree_array);
            }
        }        
        return $tree_array;
    }
    
    public function getListSubCat1Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        
		$this->results = Application_Model_PageCategories::getSubCat1($this->db, $parent_id, $size);
        echo $this->results;
    }
    
    public function getListSubCat2Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        
        $this->results = Application_Model_PageCategories::getSubCat2($this->db, $parent_id, $size);
        echo $this->results;
    }

    public function getListSubCat3Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        
        $this->results = Application_Model_PageCategories::getSubCat3($this->db, $parent_id, $size);
        echo $this->results;
    }
   
    public function getListSubCat4Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        
        $results = Application_Model_PageCategories::getSubCat4($this->db, $parent_id, $size);
        echo $$this->results;
    }
    
    public function productServiceAction()
    {
        if ($this->member_session->msg != null) {
            $this->view->msg = $this->member_session->msg;
            $this->member_session->msg = null;
        }
        
        $form = new Application_Form_ProductServiceForm(); 
        $this->view->form = $form;
        
        $page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
        
        $pp_list = new Application_Model_PageProducts();
        $this->view->data = $pp_list->getPageProducts($page_id);
        
        $this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
        
        if($this->_request->isPost())
        {
            if(count($this->view->data)==10){
                $this->view->msg = "<div class='alert alert-warning'>You can not add more than 10 products.</div>";
                return;
            }
            
            $formData = $this->_request->getPost();
            if (!$form->isValid($formData))
            {
                $this->view->form = $form;
                return;
            }
            
            try {
                if (isset($_FILES['photo']))
                {
                    $member_dir = $this->member_session->dir_name;
                    $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/500X500/";
                    
                    $path = $_FILES['photo']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $file_name = pathinfo($path, PATHINFO_FILENAME);
                    $time = time() + (7 * 24 * 60 * 60);
                    $file_name = $time.'_'.$file_name.'.'.$ext;
                    
                    move_uploaded_file($_FILES['photo']['tmp_name'], $dir_path . $file_name);
                    
                    // save in db
                    unset($formData['MAX_FILE_SIZE']);
                    unset($formData['submit']);
                    $formData['photo'] = $file_name;
                    $formData['page_id'] = $page_id;
                    $is_saved = $pp_list->add($formData);
                    if($is_saved)
                    {
                        $this->member_session->msg = "<div class='alert alert-success'>Product/service saved successfully!</div>";
                    } else {
                        $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                    }
                    
                    $this->_redirect('/members/product-service/id/'.$page_id);
                }
            } catch (Zend_File_Transfer_Exception $e) {
                $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            }            
            
        } // post if end
        
    } // action end
    
    public function editProductServiceAction()
    {
        $page_id = $this->getRequest()->getParam('page_id');
        $pp_id = $this->getRequest()->getParam('id');
        
        $pp_list = new Application_Model_PageProducts();
        $result = $pp_list->getPageProduct($pp_id);
        
        $form = new Application_Form_ProductServiceForm();
        $form->name->setValue($result->name);
        $form->price->setValue($result->price);
        $form->hide_price->setValue($result->hide_price);
        $form->discount->setValue($result->discount);
        $form->hide_discount->setValue($result->hide_discount);
        $form->description->setValue($result->description);
        $form->buy_link->setValue($result->buy_link);
        $form->submit->setLabel("Update");
        $this->view->form = $form;
        
        $this->view->photo = $result->photo;
        $this->view->page_id = $page_id;
        $this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
        
        if($this->_request->isPost())
        {   
            $formData = $this->_request->getPost();
            if (!$form->isValid($formData))
            {
                $this->view->form = $form;
                return;
            }
            
            $member_dir = $this->member_session->dir_name;
            $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/500X500/";
            $file_name = '';
            try {
                if (isset($_FILES['photo']['size']))
                {
                    if (file_exists($dir_path . $result->photo))
                    {
                        unlink($dir_path . $result->photo);
                    }
                    
                    $path = $_FILES['photo']['name'];
                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                    $file_name = pathinfo($path, PATHINFO_FILENAME);
                    $time = time() + (7 * 24 * 60 * 60);
                    $file_name = $time.'_'.$file_name.'.'.$ext;
                    
                    move_uploaded_file($_FILES['photo']['tmp_name'], $dir_path . $file_name);
                }
            } catch (Zend_File_Transfer_Exception $e) {
                $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            }
                    
                    // save in db
                    unset($formData['MAX_FILE_SIZE']);
                    unset($formData['submit']);
                    $formData['photo'] = !empty($file_name)?$file_name:$result->photo;
                    $is_saved = $pp_list->updateRecord($formData, $pp_id);
                    if($is_saved)
                    {
                        $this->member_session->msg = "<div class='alert alert-success'>Product/service updated successfully!</div>";
                    } else {
                        $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                    }
                    
                    $this->_redirect('/members/product-service/id/'.$page_id);
                
        } // post if end
        
    } // action end
    
    public function deleteProductAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        $pp_id = $this->_request->getParam('id');
        $page_id = $this->_request->getParam('page_id');
        
        $pp_list = new Application_Model_PageProducts();
        $result = $pp_list->getPageProduct($pp_id);
                
        $member_dir = $this->member_session->dir_name;
        $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/500X500/";
        
        if (file_exists($dir_path . $result->photo))
        {
            unlink($dir_path . $result->photo);
        }
        
        $is_deleted = $pp_list->deleteRecord($pp_id);
        if($is_deleted==false){
            $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
        }
        $this->_redirect('/members/product-service/id/'.$page_id);
    }

    // temp actions
    public function startCampaignAction()
    {
        if($this->_request->isPost()){
            $this->view->msg = "<div class='alert alert-success'>Your campaign started</div>";
        }
    }
    
    public function newAdvertAction()
    {
        if($this->_request->isPost()){
            $this->view->msg = "<div class='alert alert-success'>Your advertisement started</div>";
        }
    }
    
    public function newCampaignAction(){}
    public function campaignListAction(){}
    public function campaignDetailAction(){}
    public function interestedInCampaignAction(){}
    
    // temp actions end

    public function ajaxed()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest())
        {
            $this->_redirect('index');
            return; // if not a ajax request leave function
        }
    }
    
    private function getRandomString($length=20) 
    {
        $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
        $randomStr='';
        for($i=0; $i < $length; $i++) {
            $randomStr .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $randomStr;
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

    public function __call($method, $args)
    {
        if ('Action' == substr($method, -6))
        {
            // If the action method was not found, forward to the
            // index action
            return $this->_redirect('admin/index/');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "' . $method . '" called', 500);
    }

} //class ends
