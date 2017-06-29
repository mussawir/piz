<?php
class MemPagesController extends Zend_Controller_Action
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
		$this->feedbacks_model = new Application_Model_Feedbacks();
		$this->feedbackscomment_model = new Application_Model_PageFeedbacks();
		$this->feedbackslikes_model = new Application_Model_LikedFeedbacks();
		$this->customize = new Application_Model_CustomizePopup();
		$this->pages_master_model = new Application_Model_MemberPagesMaster();
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
	function siteURL()
{
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'].'/';
		return $protocol.$domainName;
	}
	public function getLoginAction(){
		$email = $this->member_session->email;
        $password = $this->member_session->getLpw;
        // $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL,$this->siteURL().'dashboards/manuAuth');
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS,
            // "pwd=".$password."&email=".$email);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// $server_output = curl_exec ($ch);
		// curl_close ($ch);
		// var_dump($server_output);
		// header('Location: '.$this->siteURL().'dashboards/home');
	}
	public function uploadCommentAction(){
		if ($this->_request->isPost())
        {
		$formData = $this->_request->getPost();
		$formData['email'] = 'test@test.com';
		$formData['name'] = 'John Doe';
		$result = $this->feedbackscomment_model->addComment($formData);
		$formData1 = json_encode($result);
		die($formData1);
		}
	}
	public function newVideoPostAction(){
		$page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
		if($this->_request->isPost())
        { 
		$formData = $this->_request->getPost();
		$pp_list = new Application_Model_PageProducts();
		// $page_id = $formData['page_id'];
		unset($formData['submit']);
		
		$formData['post_type'] = '4'; // Video Post
		// var_dump($formData);exit;
		$is_saved = $pp_list->add($formData);
		if($is_saved)
		{
			$this->view->msg = "<div class='alert alert-success'>Video Post saved successfully!</div>";
		} else {
			$this->view->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
		}
		$this->_redirect('/mem-pages/new-video-post/id/'.$page_id);
		}
	}
	public function updateFeedbackAction(){
		
		if ($this->_request->isPost())
        {
		$formData = $this->_request->getPost();
		$page_id = $formData['page_id'];
		$member_dir = $this->member_session->dir_name;
		if(isset($_FILES['edit_image']) && $_FILES['edit_image']['size'] > 0){
			 // echo"file uploaded";exit; 
			 $file = $_FILES['edit_image']['tmp_name'];
                
                $data = array();
                
                $banner = getimagesize($file);
				
                $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
                      
                $path = $_FILES['edit_image']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = pathinfo($path, PATHINFO_FILENAME);
                $random = rand(111, 999);
                $time = time() + (7 * 24 * 60 * 60);
                $file_name = $file_name . "_" . $time . $random . "." . $ext;
                
                move_uploaded_file($_FILES['edit_image']['tmp_name'], $dir_path . $file_name);
				$formData['page_id'] = $page_id;
                $formData['banner'] = $file_name;
				// var_dump($formData);exit;
                // save into db
                $result = $this->feedbacks_model->updateFeedback($formData);
                if($result){
                        // $this->view->msg = 'Get Feedback has been created!';
						$this->_redirect("/mem-pages/feedback/id/".$page_id);
                } else {
						// $this->view->err = 'Some error occured! Please try again.';
						$this->_redirect("/mem-pages/feedback/id/".$page_id);
                }
		}else{
			// echo "Not Uploaded" ;exit;
				// $formData['page_id'] = $page_id;
                $formData['banner'] = '';
				// var_dump($formData);exit;
                // save into db
                $result = $this->feedbacks_model->updateFeedback($formData);
                if($result){
                        // $this->view->msg = 'Get Feedback has been created!';
						$this->_redirect("/mem-pages/feedback/id/".$page_id);
                } else {
						// $this->view->err = 'Some error occured! Please try again.';
						$this->_redirect("/mem-pages/feedback/id/".$page_id);
                }
		}
		// $result = $this->feedbackscomment_model->updateFeedback($formData);
		// $formData1 = json_encode($result);
		// die($formData1);
		}
	}
	public function saveFeedbacklikeAction(){
		$this->ajaxed();
        
        $page_id = $this->getRequest()->getParam('page_id');
        $feed_id = $this->getRequest()->getParam('feed_id');
        $like_page_model = new Application_Model_LikedFeedbacks();
        $result = $like_page_model->add($page_id, $this->member_session->member_id,$feed_id);
        
        if($result)
        {
            // $page = $this->page->getPageLikes($page_id);
            // $this->page->setPageLikes($page_id, ($page['likes']+1));
            echo 'success';
        } else {
            echo 'error';
        }
	}
	public function feedbackAction(){
		$page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
		$member_dir = $this->member_session->dir_name;
		$this->view->member_dir = $member_dir;
		$result = $this->pages_model->getPageByID($page_id); 
		$this->view->result = $result;
		$feedback = $this->feedbacks_model->getFeedbackByID($page_id);
		if($feedback){
			
			$this->view->feedback = $feedback;
			$feedbackscomment = $this->feedbackscomment_model->getCommentsByPage($page_id,$feedback->feed_id);
			// $array = json_decode(json_encode($feedbackscomment), true);
			// print_r($feedbackscomment->toArray());exit;
			
			$this->view->comments = $feedbackscomment;
		}
		
		if ($this->_request->isPost())
        {
		$formData = $this->_request->getPost();
		if(isset($_FILES['banner_image']) && $_FILES['banner_image']['size'] > 0){
			 // echo"file uploaded";exit; 
			 $file = $_FILES['banner_image']['tmp_name'];
                
                $data = array();
                
                $banner = getimagesize($file);
				
                $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
                      
                $path = $_FILES['banner_image']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = pathinfo($path, PATHINFO_FILENAME);
                $random = rand(111, 999);
                $time = time() + (7 * 24 * 60 * 60);
                $file_name = $file_name . "_" . $time . $random . "." . $ext;
                
                move_uploaded_file($_FILES['banner_image']['tmp_name'], $dir_path . $file_name);
				$formData['page_id'] = $page_id;
                $formData['banner'] = $file_name;
				// var_dump($formData);exit;
                // save into db
                $result = $this->feedbacks_model->addFeedback($formData);
                if($result){
                        $this->view->msg = 'Get Feedback has been created!';
						$this->_redirect("/mem-pages/feedback/id/".$page_id);
                } else {
						$this->view->err = 'Some error occured! Please try again.';
						$this->_redirect("/mem-pages/feedback/id/".$page_id);
                }
		} else { 
			// echo "Not Uploaded" ;exit;
				$formData['page_id'] = $page_id;
                $formData['banner'] = '';
				// var_dump($formData);exit;
                // save into db
                $result = $this->feedbacks_model->addFeedback($formData);
                if($result){
                        $this->view->msg = 'Get Feedback has been created!';
						$this->_redirect("/mem-pages/feedback/id/".$page_id);
                } else {
						$this->view->err = 'Some error occured! Please try again.';
						$this->_redirect("/mem-pages/feedback/id/".$page_id);
                }			
		}
            
		}
	}
    public function searchProductsAction(){
		// $search_pro = new Application_Model_PageProducts();
			// $search = $search_pro->getProducts('159', 'Testing');
			// echo json_encode($search->toArray());
		if ($this->_request->isPost())
        {
			$name = $_POST['name'];
			$page_id = $_POST['page_id'];
			
			$search_pro = new Application_Model_PageProducts();
			$search = $search_pro->getProducts($page_id, $name);
			$arr = Array();
			
			
		}
	}
	public function updateCustomizationAction(){
		
		if ($this->_request->isPost()){ 
		$member_dir = $this->member_session->dir_name;
		$FormData = $this->_request->getPost();
			$FormData['cta_heading_color'] = "#".$FormData['heading_color'];
			$FormData['cta_paragraph_color'] = "#".$FormData['paragraph_color'];
			$FormData['cta_button_color'] = "#".$FormData['button_color'];
			$FormData['member_id'] = $this->member_session->member_id;
			unset($FormData['heading_color']);
			unset($FormData['paragraph_color']);
			unset($FormData['button_color']);
			// var_dump($FormData);exit;
			// var_dump($FormData);exit;
			// echo 'success'; exit;
			// var_dump($_FILES);exit;
			if(isset($_FILES['background_image']) && $_FILES['background_image']['size'] > 0){
				// echo 'success'; exit;
			 
			 $file = $_FILES['background_image']['tmp_name'];
                
                $data = array();
                
                $banner = getimagesize($file);
				
                $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
                      
                $path = $_FILES['background_image']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = pathinfo($path, PATHINFO_FILENAME);
                $random = rand(111, 999);
                $time = time() + (7 * 24 * 60 * 60);
                $file_name = $file_name . "_" . $time . $random . "." . $ext;
                
                move_uploaded_file($_FILES['background_image']['tmp_name'], $dir_path . $file_name);
			// $FormData = $this->_request->getPost();
			
			// var_dump($FormData);exit;
			$FormData['cta_background'] = $file_name;
			unset($FormData['background_image']);
			// var_dump($FormData);exit;
			$result = $this->customize->updateCustomization($FormData);
			if($result){
					// echo 'success';exit; 
						
						$this->_redirect("/mem-pages/popup-design/id/".$FormData['page_id']);
				} else {
					// echo 'error';exit;
						
						$this->_redirect("/mem-pages/popup-design/id/".$FormData['page_id']);
				}
		}else{
			// echo 'not';exit;
			$result = $this->customize->updateCustomizationWithOutImage($FormData);
			if($result){
					// echo 'success';exit; 
						$this->view->msg = 'Customization has been created for CTA';
						$this->_redirect("/mem-pages/popup-design/id/".$FormData['page_id']);
				} else {
					// echo 'error';exit;
						$this->view->err = 'Some error occured! Please try again.';
						$this->_redirect("/mem-pages/popup-design/id/".$FormData['page_id']);
				}
		}
			
		}
	}
	public function popupDesignAction(){
		$id = $this->_request->getParam('id');
		$this->view->id = $id;
		$customization = $this->customize->getCustomizationByID($this->member_session->member_id,$id);
		$this->view->custom = $customization;
		$member_dir = $this->member_session->dir_name;
		$this->view->member_dir = $member_dir;
		if ($this->_request->isPost()){ 
		$FormData = $this->_request->getPost();
			$FormData['cta_heading_color'] = "#".$FormData['heading_color'];
			$FormData['cta_paragraph_color'] = "#".$FormData['paragraph_color'];
			$FormData['cta_button_color'] = "#".$FormData['button_color'];
			$FormData['member_id'] = $this->member_session->member_id;
			unset($FormData['heading_color']);
			unset($FormData['paragraph_color']);
			unset($FormData['button_color']);
			// var_dump($FormData);exit;
			// echo 'success'; exit;
			// var_dump($_FILES);exit;
			if(isset($_FILES['background_image']) && $_FILES['background_image']['size'] > 0){
				// echo 'success'; exit;
			 
			 $file = $_FILES['background_image']['tmp_name'];
                
                $data = array();
                
                $banner = getimagesize($file);
				
                $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
                      
                $path = $_FILES['background_image']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = pathinfo($path, PATHINFO_FILENAME);
                $random = rand(111, 999);
                $time = time() + (7 * 24 * 60 * 60);
                $file_name = $file_name . "_" . $time . $random . "." . $ext;
                
                move_uploaded_file($_FILES['background_image']['tmp_name'], $dir_path . $file_name);
			// $FormData = $this->_request->getPost();
			
			// var_dump($FormData);exit;
			$FormData['cta_background'] = $file_name;
			unset($FormData['background_image']);
			// var_dump($FormData);exit;
			$result = $this->customize->addCustomization($FormData);
			if($result){
					// echo 'success';exit; 
						$this->view->msg = 'Customization has been created for CTA';
						$this->_redirect("/mem-pages/popup-design/id/".$id);
				} else {
					// echo 'error';exit;
						$this->view->err = 'Some error occured! Please try again.';
						$this->_redirect("/mem-pages/popup-design/id/".$id);
				}
		}else{
			echo 'not found';exit;
		}
			
		}
	}
    public function indexAction()
    {
        $this->view->data = $this->pages_model->getPagesByMember($this->member_session->member_id);
	}
	public function purchaseAction(){
		$this->view->data = $this->pages_master_model->getMasterPagesByMemeber($this->member_session->member_id);
	}
    public function postAction(){
		
		
	}	
	public function uploadSliderFilesAction(){
		
		$id = $this->_request->getParam('id');
		if ($this->_request->isPost())
        {
			$FormData = $this->_request->getPost();
			
			$member_dir = $this->member_session->dir_name;
for($i=0;$i<=5;$i++){
			if (isset($_FILES['slider'.$i]))
            {
				
                $file = $_FILES['slider'.$i]['tmp_name'];
                
                $data = array();
                //$banner = getimagesize($file);
                
                $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
                                
                    //if (file_exists($dir_path . $formData["old_banner"]))
                    //{
                      //  unlink($dir_path . $formData["old_banner"]);
                    //}

                $path = $_FILES['slider'.$i]['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $file_name = pathinfo($path, PATHINFO_FILENAME);
                $random = rand(111, 999);
                $time = time() + (7 * 24 * 60 * 60);
                $file_name = $file_name . "_" . $time . $random . "." . $ext;
                
				
                if(move_uploaded_file($_FILES['slider'.$i]['tmp_name'], $dir_path . $file_name)){
					echo 'success';
				}else{
					echo 'failed';
				}
				
                // save into db
                $result = $this->pages_model->updatePic($id,$file_name,'slide'.$i);
                if($result){
                    //$data = array('status' => 'success', 'old_banner'=> $file_name, 'banner'=>'<img id="banner-img" src="'.$formData['base_path'].'/images/uploads/'.$member_dir.'/'.$file_name.'" alt="'.$file_name.'" class="img-responsive" />');    
					$whatHappened= 'success';
                } else {
                    //$data = array('status' => 'error', 'msg'=>"<div class='alert alert-danger'><strong>Some error occur. Please try again.</strong></div>");
					$whatHappened= 'error';
                }
            }
}
$this->view->msg =  "<div class='alert alert-success'><strong>Succcess</strong>Slider Uploaded</div>";
$this->_redirect("/mem-pages/manage-page/id/".$id);					
		}
	}
	public function uploadSliderAction(){
		$id = $this->_request->getParam('id');
		$this->view->data = $id;
	}
    public function commentsAction()
    {
		$id = $this->_request->getParam('id');
		$this->view->page_id = $id;
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
               
		      $this->_redirect("/mem-pages/comments/id/".$page_id);					
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
               
		      $this->_redirect("/mem-pages/comments/id/".$page_id);
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
		   
            $this->_redirect("/mem-pages/comments/id/".$page_id);
	       } 
	}
    
    public function detailPagesAction()
    {
        $this->view->data = $this->pages_model->getPagesByMember($this->member_session->member_id); //getPageByMaster($page_master_id); 
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
		 		$this->_redirect("/mem-pages/detail-pages");	
		}
		
        $result = $this->pages_model->getPageByID($page_id);  	
    	$this->view->result = $result;
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
		$this->view->result = $result;
        
        $this->view->form = $form;
        
        /*if ($this->_request->isPost()) 
        {
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
        		$this->_redirect("/mem-pages/manage-page/id/".$page_id);
            }
        }*/
    } // manage page function end
    
    public function savePageAction()
    {
        // $this->ajaxed();
        
        $formData = $this->_request->getPost();
        // var_dump($formData);exit;
                $formData['date_published']= date("Y-m-d H:i:s");
                //responsive code making section 
				$html = stripslashes($formData['contents']);
				$page_id = $formData['page_id'];
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
                
                $formData['is_in_draft'] = 0;
                $formData['draft_content'] = stripslashes($formData['contents']);    
 			    $is_saved = $this->pages_model->updateMemberPage($formData);
     			
        if($is_saved){
            // echo var_dump($is_saved);exit;
			$this->_redirect("/mem-pages/manage-page/id/". $page_id."?status=success");	
        } else {
            $this->_redirect("/mem-pages/manage-page/id/". $page_id."?status=error");
        }
    }
    
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
		$this->_redirect("/mem-pages/manage-page/id/". $page_id);	
			
		}   
	}
    
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
             /*   if($banner[0]<300){
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-warning'><strong>Poster width must be greater than 300px</strong></div>");
                    echo json_encode($data);
                    return;
                }
                 
			    if($banner[1]<100){
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-warning'><strong>Poster height must be greater than 100px</strong></div>");
                    echo json_encode($data);
                    return;
                }
				
                if($banner[0]>1000){
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-warning'><strong>Poster width must be less than 1000px</strong></div>");
                    echo json_encode($data);
                    return;
                }
                
                if($banner[1]>2000){
                    $data = array('status' => 'error', 'msg'=>"<div class='alert alert-warning'><strong>Poster height must be less than 2000px</strong></div>");
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
    public function saveLinkAction(){
		$video_link = $_POST['videoId'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->updateLink($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
	}
	public function saveFormplaceAction(){
		$video_link = $_POST['formPlace'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->updateplace($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
	}
	public function turnOnAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOn($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOnadsAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOnAds($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOnvidsAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOnVids($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOffvidsAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOffVids($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOnformAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOnForm($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOffformAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOffForm($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOnslideAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOnSlide($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOffslideAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOffSlide($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOnfeedbackAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOnfeedback($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOfffeedbackAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOfffeedback($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOnbannerAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOnBanner($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOffbannerAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOffBanner($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	
	public function pinPostAction(){
		if($this->_request->isPost())
        {
		$post_id = $_POST['post_id'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->pinPost($pageId,$post_id);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function turnOffadsAction(){
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOffAds($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
	public function switchOffAction(){ 
		if($this->_request->isPost())
        {
		$video_link = $_POST['value'];
		$pageId = $_POST['page_id'];
		$result = $this->pages_model->turnOff($pageId,$video_link);
			if($result){
				echo 'success';
            } else {
				echo 'fail';
			}
		}
	}
    public function pageLikesAction()
    {
        $page_id = $this->_request->getParam('id');
        
        $like_page_model = new Application_Model_LikedPages();
        $this->view->data = $like_page_model->getLikesByPage($this->db, $page_id); 
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
        
        $page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
        
        $form = new Application_Form_BrochureUploadForm();
        $this->view->form = $form;
        
        $brochure_model = new Application_Model_MemberBrochures();
        $page_brochure = $brochure_model->getMemberPageBrochures($this->member_session->member_id, $page_id);
        
        $pages_ddl = '<option value="0">-- Select --</option>';
        foreach($page_brochure as $mp){
            $pages_ddl .= "<option value=".$mp['m_brochuer_id'].">".(!empty($mp['title'])?$mp['title']:'No Title')."</option>";
        }
        $this->view->brochure_ddl = $pages_ddl;
        
        // get last inserted brochure
        $last_brochure = $brochure_model->getLastBrochure($this->member_session->member_id, $page_id);
        $this->view->title = $last_brochure['title'];
        $this->view->src = !empty($last_brochure['image']) ? '/images/uploads/'.$this->member_session->dir_name.'/500X500/'.$last_brochure['image']: null;
        
        if($this->_request->isPost())
        {
            $formData = $this->_request->getPost();
            if (!$form->isValid($formData)) {
                $this->view->form = $form;
                return;
            }
            
            $member_dir = $this->member_session->dir_name;
            $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
            $image_file='';
            $brochure_file='';
                
            if($formData['m_brochuer_id']>0)
            {
                $saved_brochure = $brochure_model->getBrochureById($formData['m_brochuer_id']);
                
                try {
                    if (isset($_FILES['image']['size']))
                    {
                        if (file_exists($dir_path.'/500X500/' . $saved_brochure["image"]))
                        {
                            unlink($dir_path.'/500X500/' . $saved_brochure["image"]);
                        }
                        
                        $path = $_FILES['image']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $file_name = pathinfo($path, PATHINFO_FILENAME);
                        $time = time() + (7 * 24 * 60 * 60);
                        $image_file = $time.'_'.$file_name.'.'.$ext;
                        
                        move_uploaded_file($_FILES['image']['tmp_name'], $dir_path.'/500X500/' . $image_file);                        
                    }
                    
                    if (isset($_FILES['brochure']))
                    {
                        if (file_exists($dir_path . $saved_brochure["brochure"]))
                        {
                            unlink($dir_path . $saved_brochure["brochure"]);
                        }
                        
                        $path = $_FILES['brochure']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $file_name = pathinfo($path, PATHINFO_FILENAME);
                        $time = time() + (7 * 24 * 60 * 60);
                        $brochure_file = $time.'_'.$file_name.'.'.$ext;
                        
                        move_uploaded_file($_FILES['brochure']['tmp_name'], $dir_path . $brochure_file);                        
                    }
                } catch (Zend_File_Transfer_Exception $e) {
                    $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                    return;
                }
                
                // update in db
                        $is_saved = $brochure_model->updateBrochure($formData['m_brochuer_id'], array('title'=>$formData['title'], 'image'=>(empty($image_file)?$saved_brochure["image"]:$image_file), 'brochure'=>$brochure_file));
                        if($is_saved)
                        {
                            $this->member_session->msg = "<div class='alert alert-success'>Brochure uploaded successfully!</div>";
                        } else {
                            $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                        }
                        
                        $this->_redirect("/mem-pages/upload-brochure/id/".$page_id);
            } else {            
                // check if more then one upload allowed
                $brochure_limit = $this->members->getBrochureLimit($this->member_session->member_id);
                $saved_brochures = $brochure_model->getMemberPageBrochures($this->member_session->member_id, $page_id);
                if($brochure_limit['brochure_limit']==1 && count($saved_brochures)==1){
                    $this->view->msg = "<div class='alert alert-info'>Please buy package to upload more brochures.</div>";
                    return;
                }
                
                try {
                    if (isset($_FILES['image']['size']))
                    {
                        $path = $_FILES['image']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $file_name = pathinfo($path, PATHINFO_FILENAME);
                        $time = time() + (7 * 24 * 60 * 60);
                        $image_file = $time.'_'.$file_name.'.'.$ext;
                        
                        move_uploaded_file($_FILES['image']['tmp_name'], $dir_path.'/500X500/' . $image_file);                        
                    }
                    
                    if (isset($_FILES['brochure']))
                    {
                        $path = $_FILES['brochure']['name'];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        $file_name = pathinfo($path, PATHINFO_FILENAME);
                        $time = time() + (7 * 24 * 60 * 60);
                        $brochure_file = $time.'_'.$file_name.'.'.$ext;
                        
                        move_uploaded_file($_FILES['brochure']['tmp_name'], $dir_path . $brochure_file);
                        
                        // save in db
                        $is_saved = $brochure_model->add(array('member_id'=>$this->member_session->member_id, 'brochure'=>$brochure_file, 'title'=>$formData['title'], 'image'=>$image_file, 'page_id'=>$page_id));
                        if($is_saved)
                        {
                            $this->member_session->msg = "<div class='alert alert-success'>Brochure uploaded successfully!</div>";
                        } else {
                            $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                        }
                        
                        $this->_redirect("/mem-pages/upload-brochure/id/".$page_id);
                    }
                } catch (Zend_File_Transfer_Exception $e) {
                    $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                }
            }
        } // post end
        
    } // brochure funciton end
    
    public function brochureListAction()
    {
        
    }
    
    public function deleteBrochureAction()
    {
        $page_id = $this->getRequest()->getParam('page_id');
        $m_brochuer_id = $this->getRequest()->getParam('m_brochuer_id');
        
        $brochure_model = new Application_Model_MemberBrochures();
        
        $page_brochure = $brochure_model->getBrochureById($m_brochuer_id);
        if(isset($page_brochure))
        {
            $member_dir = $this->member_session->dir_name;
            $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/";
            
            if (file_exists($dir_path . $page_brochure["brochure"]))
            {
                unlink($dir_path . $page_brochure["brochure"]);
            }
            
            if (file_exists($dir_path.'/500X500/' . $page_brochure["image"]))
            {
                unlink($dir_path.'/500X500/' . $page_brochure["image"]);
            }
        }
        
        $result = $brochure_model->deleteBrochure($m_brochuer_id);
        
        $this->_redirect("/mem-pages/upload-brochure/id/".$page_id);
    } // brochure delete end
    
    public function showBrochureAction()
    {
        $this->ajaxed();
        
        $m_brochuer_id = $this->getRequest()->getParam('m_brochuer_id');
        
        $brochure_model = new Application_Model_MemberBrochures();        
        $page_brochure = $brochure_model->getBrochureById($m_brochuer_id);
        
        $data = array('title'=>$page_brochure['title'], 
                    'src'=>!empty($page_brochure['image'])?'/images/uploads/'.$this->member_session->dir_name.'/500X500/'.$page_brochure['image']:null);
                    
        echo json_encode($data);
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
    public function newProductPostAction(){
		
		$form = new Application_Form_ProductServiceForm(); 
        $this->view->form = $form;
        
        $page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
        
        $pp_list = new Application_Model_PageProducts();
        
        $this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
        
        if($this->_request->isPost())
        {
            /*if(count($this->view->data)==10){
                $this->view->msg = "<div class='alert alert-warning'>You can not add more than 10 products.</div>";
                return;
            }*/
            
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
					$formData['post_type'] = '1'; // Product Post
                    $is_saved = $pp_list->add($formData);
                    if($is_saved)
                    {
                        $this->member_session->msg = "<div class='alert alert-success'>Product/service saved successfully!</div>";
                    } else {
                        $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                    }
                    
                    $this->_redirect('/mem-pages/new-product-post/id/'.$page_id);
                }
            } catch (Zend_File_Transfer_Exception $e) {
                $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            }
        }
	}
	public function newServicePostAction(){
		
		$form = new Application_Form_ProductServiceForm(); 
        $this->view->form = $form;
        
        $page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
        
        $pp_list = new Application_Model_PageProducts();
        
        $this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
        
        if($this->_request->isPost())
        {
            /*if(count($this->view->data)==10){
                $this->view->msg = "<div class='alert alert-warning'>You can not add more than 10 products.</div>";
                return;
            }*/
            
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
					$formData['post_type'] = '3'; // Product Post
                    $is_saved = $pp_list->add($formData);
                    if($is_saved)
                    {
                         $this->view->msg = "<div class='alert alert-success'>Service saved successfully!</div>";
						  $this->_redirect('/mem-pages/new-service-post/id/'.$page_id);
                    } else {
                        $this->view->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
						 $this->_redirect('/mem-pages/new-service-post/id/'.$page_id);
                    }
                   
                   
                }
            } catch (Zend_File_Transfer_Exception $e) {
                $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            }            
            
        }
	}
	public function productPostAction(){
		
		if($this->_request->isPost())
        { 
			$this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
			$page_id = $this->getRequest()->getParam('id');
			$this->view->page_id = $page_id;
			$name = $_POST['name'];
			$page_id = $_POST['page_id'];
			$this->view->name  = $name;
			$search_pro = new Application_Model_PageProducts();
			$this->view->data = $search_pro->getProducts($page_id, $name);
		}else{
			$this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
			$page_id = $this->getRequest()->getParam('id');
			$this->view->page_id = $page_id;
			$pp_list = new Application_Model_PageProducts();
			$this->view->data = $pp_list->getPagexProductx($page_id);
		}
	}
	public function videoPostsAction(){
		$page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
		$pp_list = new Application_Model_PageProducts();
        $this->view->videos = $pp_list->getVideoPosts($page_id);
		if($this->_request->isPost())
        {
		$formData = $this->_request->getPost();
		unset($formData['page_id']);
		$pp_id = $formData['pp_id'];
		unset($formData['pp_id']);
		$updated = $pp_list->updateRecord($formData,$pp_id);
		if($updated){
			$this->view->status = "<div class='alert alert-success'>Video updated successfully!</div>";
			  $this->_redirect('/mem-pages/video-posts/id/'.$page_id);
		}else{
			$this->view->status = "<div class='alert alert-danger'>Some error Occurred!</div>";
			  $this->_redirect('/mem-pages/video-posts/id/'.$page_id);
		}
		}
	}
	public function servicePostsAction(){
		
		if($this->_request->isPost())
        { 
			$this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
			$page_id = $this->getRequest()->getParam('id');
			$this->view->page_id = $page_id;
			$name = $_POST['name'];
			$page_id = $_POST['page_id'];
			$this->view->name  = $name;
			$search_pro = new Application_Model_PageProducts();
			$this->view->data = $search_pro->getServices($page_id, $name);
		}else{
			$this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
			$page_id = $this->getRequest()->getParam('id');
			$this->view->page_id = $page_id;
			$pp_list = new Application_Model_PageProducts();
			$this->view->data = $pp_list->getServicePosts($page_id);
		}
	}
	public function socialPostsAction(){ 
		$page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
		$pp_list = new Application_Model_PageProducts();
        $this->view->posts = $pp_list->getPagePosts($page_id);
	}
	public function newSocialPostAction(){
		$form = new Application_Form_ProductServiceForm(); 
        $this->view->form = $form;
        
        $page_id = $this->getRequest()->getParam('id');
        $this->view->page_id = $page_id;
        
        $pp_list = new Application_Model_PageProducts();
	
	if($this->_request->isPost())
        { 
		$formData = $this->_request->getPost();
		
		$page_id = $formData['page_id'];
		unset($formData['submit']);
		
		$formData['post_type'] = '2'; // Info Post
		
		$is_saved = $pp_list->add($formData);
		if($is_saved)
		{
			$this->view->msg = "<div class='alert alert-success'>Post saved successfully!</div>";
		} else {
			$this->view->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
		}
		$this->_redirect('/mem-pages/new-social-post/id/'.$page_id);
		}
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
        
		$this->view->posts = $pp_list->getPagePosts($page_id);
        $this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
        
        if($this->_request->isPost())
        {
            /*if(count($this->view->data)==10){
                $this->view->msg = "<div class='alert alert-warning'>You can not add more than 10 products.</div>";
                return;
            }*/
            
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
					$formData['post_type'] = '1'; // Product Post
                    $is_saved = $pp_list->add($formData);
                    if($is_saved)
                    {
                        $this->member_session->msg = "<div class='alert alert-success'>Product/service saved successfully!</div>";
                    } else {
                        $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                    }
                    
                    $this->_redirect('/mem-pages/product-service/id/'.$page_id);
                }
            } catch (Zend_File_Transfer_Exception $e) {
                $this->view->msg = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            }            
            
        } // post if end
        
    } // action end
    public function createPostAction(){ 
	$pp_list = new Application_Model_PageProducts();
	
	if($this->_request->isPost())
        {
		$formData = $this->_request->getPost();
		
		$page_id = $formData['page_id'];
		unset($formData['submit']);
		
		$formData['post_type'] = '2'; // Info Post
		
		$is_saved = $pp_list->add($formData);
		if($is_saved)
		{
			$this->member_session->msg = "<div class='alert alert-success'>Post saved successfully!</div>";
		} else {
			$this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
		}
		$this->_redirect('/mem-pages/product-service/id/'.$page_id);
		}
		
	}
	public function editSocialPostAction()
    {
        $page_id = $this->getRequest()->getParam('page_id');
        $pp_id = $this->getRequest()->getParam('id');
        
        $pp_list = new Application_Model_PageProducts();
        $result = $pp_list->getPageProduct($pp_id);
        
        
        $this->view->desc = stripslashes($result->description);
        
        $this->view->page_id = $page_id;
        $this->view->dir_path = '/images/uploads/'. $this->member_session->dir_name.'/500X500/';
        
        if($this->_request->isPost())
        {   
            $formData = $this->_request->getPost();
            
            
            $member_dir = $this->member_session->dir_name;
            $dir_path = SYSTEM_PATH . "/images/uploads/$member_dir/500X500/";
            
                    
                    unset($formData['submit']);
                    
                    $is_saved = $pp_list->updateRecord($formData, $pp_id);
                    if($is_saved)
                    {
                        $this->member_session->msg = "<div class='alert alert-success'>Social Post updated successfully!</div>";
                    } else {
                        $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
                    }
                    
                    $this->_redirect('/mem-pages/social-posts/id/'.$page_id);
                
        } // post if end
        
    }
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
        $form->price_usd->setValue($result->price_usd);
        $form->discount_usd->setValue($result->discount_usd);
		
		
		// $form->description->setValue(stripslashes($result->description));
        $form->buy_link->setValue($result->buy_link);
        $form->submit->setLabel("Update");
        $this->view->form = $form;

        $this->view->desc = stripslashes($result->description);
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
                    
                    $this->_redirect('/mem-pages/product-post/id/'.$page_id);
                
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
        $this->_redirect('/mem-pages/product-post/id/'.$page_id);
    }
	public function deleteServiceAction()
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
        $this->_redirect('/mem-pages/service-posts/id/'.$page_id);
    }
	public function deletePostAction()
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
        $this->_redirect('/mem-pages/social-posts/id/'.$page_id);
    }
    public function deleteVideoAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        $pp_id = $this->_request->getParam('id');
        $page_id = $this->_request->getParam('page_id');
        
        $pp_list = new Application_Model_PageProducts();
        $result = $pp_list->getPageProduct($pp_id);
        
        $is_deleted = $pp_list->deleteRecord($pp_id);
        if($is_deleted==false){
            $this->member_session->msg = "<div class='alert alert-danger'>Some error occure. Please try again.</div>";
        }
        $this->_redirect('/mem-pages/video-posts/id/'.$page_id);
    }
    public function hideAdsAction()
    {
        // $page_id = $this->_request->getParam('id');
        // $this->view->page_id = $page_id;
        // $this->view->dir_name = $this->member_session->dir_name;
        
        // $result = $this->pages_model->getMemberPageList($this->member_session->member_id, $page_id);
        // $this->view->data = $result;
    } // hide ads function end
    
    public function hideShowAdsAction()
    {
        $ad_id = $this->_request->getParam('id');
        $page_id = $this->_request->getParam('pid');
        $is_hidden = $this->_request->getParam('type');

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        $result = false;
        if($is_hidden=='hide'){
            $result = $this->pages_model->hidePage($ad_id);
        } else {
            $result = $this->pages_model->unhidePage($ad_id);
        }
        
        if($result)
        {
            $this->_redirect("/mem-pages/hide-ads/id/".$page_id);
        }
    } // hideShowAds function end
    public function packagesAction(){
		
	}
	
	public function successAction(){
		$package = $this->_request->getParam('p');
		$member_id = $this->member_session->member_id;
		$price = 0;
                if($package==1){
                    $price = 400;
                } else if($package==4) {
                    $price = 1500;
                } else if($package==8) {
                    $price = 2400;
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
				
				$this->view->msg = "<div class='alert alert-success'>Page has been created</div>";
				return;
	}
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
    
    public function Paginator($results)
    {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
    
    public function __call($method, $args)
    {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_redirect('/mem-pages/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "' . $method . '" called', 500);
    }
    
} // class end