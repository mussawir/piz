<?php

class Admin_PageController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $page = null;
		private $url = null;
        private $page_comments = null;
        private $Heatmap = null;
		
	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
		$this->members = new Application_Model_Members();	
		$this->master_pages = new Application_Model_MemberPagesMaster;	
		ini_set("max_execution_time",(60*300));
		
		$this->page = new Application_Model_Pages();
		$this->url = new Application_Model_SiteInfo();
		$this->page_comments = new Application_Model_PageComments();
        $this->Heatmap = new Application_Model_HeatmapClicks();
        
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
    
    //for new page
	public function transferAction(){
		function recurse($src,$dst) { 
	        $dir = opendir($src); 
	        @mkdir($dst); 
	        while(false !== ( $file = readdir($dir)) ) { 
	            if (( $file != '.' ) && ( $file != '..' )) { 
	                if ( is_dir($src . '/' . $file) ) { 
	                    recurse($src . '/' . $file,$dst . '/' . $file); 
	                } 
	                else { 
	                    copy($src . '/' . $file,$dst . '/' . $file); 
	                }  
	            } 
	        } 
	        closedir($dir); 
			return 'success';
	    } 
		
		$id = $this->_request->getParam('id');
		$page = $this->page->getPageByID($id);
		$old_data = $this->page->getPageByID($id);
		$old_member_id = $old_data->member_id;
		$old_member = $this->members->getDetails($old_member_id);
		$old_member_directory = $old_member->dir_name;
		$this->view->member = $page;
		
		$this->view->master_page = $this->master_pages->getMasterPage($page->master_p_id);
		$this->view->marketers = $this->members->getMarketerList();
		$this->view->members = $this->members->getMembersList();
		if ($this->_request->isPost()) 
        {
			$formData = $this->_request->getPost();
			$new_member = $this->members->getDetails($formData['member']);
			$new_member_directory = $new_member->dir_name;
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
			$master_data = array('status'=>$formData['status'], 'price'=>$formData['page_price'], 'end_date'=>$formData['end_date'],);
			$mster_page_id = $master_page->updateAdminMasterPage($master_data,$page->master_p_id);
			
			// create page detail
			$page_detail = new Application_Model_Pages();
			
				$page_detail->updateAdminMemberPage(array(
					'member_id' => $formData['member'],
					'marketer_id' => $formData['marketer_id'],
					'expiry_date' => $formData['end_date'],
					'video_hidden' => $video,
					'slider_hidden' => $slider,
					'pop' => $pop,
					'inpage' => $inpage,
					'total_posts' => $formData['pages_post'],
					'notes' => $formData['notes']
				),$id);
			$src = SYSTEM_PATH . '/images/uploads/'. $old_member_directory;
			$dst = SYSTEM_PATH . '/images/uploads/'. $new_member_directory;
			recurse($src,$dst);
			$this->view->msg = "<div class='alert alert-success'>Page has been updated successfully!.</div>";
			return;
		}
	}
	
	public function newPageAction() 
	{
		$form = new Application_Form_PageForm();
		$this->view->form = $form;
		
        $results = $this->url->getUrls();
		$this->view->site_url= $results->site_url;
        
		if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
		
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		
	if (!$form->isValid($formData))
	{
            $this->view->form = $form;
			$this->view->errorSlug =  $formData['url_slug'];
            return;
        }		

		 //check from database if the slug is already in db
		$data = array ("url"=>$formData['url_slug']);
		$data["url"]=$formData['url_slug'];
		 if($this->page->checkPageSlug($data)){
		 $this->view->msg =  "<div class='alert alert-danger'>Page Unique Parmalink Is Already Exist. Please change title for unique parmalink.</div>";
		 $this->view->errorSlug =  $formData['url'];
		 return;
		 }
		   	
		$formData['user_id']= $this->user_session->user_id;
    		$formData['date_created']= date("Y-m-d H:i:s");
    		$formData['date_published']= date("Y-m-d H:i:s");
    		
    		//check from database if the slug is already in db  
    		$data = array ("url"=>$formData['url_slug']);
    		$data["url"]=$formData['url_slug'];
    			
        
         //var_dump($formData);return;
         if($formData['submit'] == "0" )
         {
            $formData['is_in_draft'] = 0;
            $formData['draft_content'] = stripslashes($formData['contents']);
			
            $result = $this->page->addPage($formData);
    		$this->view->msg = $result;
         }
         else
         {
            $formData['is_in_draft'] = 1;
            $formData['draft_content'] = stripslashes($formData['contents']);
			
            $result = $this->page->addDraftPage($formData);
    		$this->view->msg = $result;
         }
		$this->_redirect('admin/page/edit/id/'.$result);
	}
	
	//for post list
	public function indexAction()
    { 
	   $drafted_pages = $this->_request->getParam('status');
       if(isset($drafted_pages)){
            $this->view->data =$this->page->getAllDraftPages($this->db);
        }else{
        $results = $this->page->getAllPages($this->db);
    	$this->view->data = $results;
        
	   }
	/*code for delete bulk data start*/

	 if(isset($_POST['submit'])) {
	$id_array = $_POST['bulkdata']; // return array
	$id_count = count($_POST['bulkdata']); // count array

	for($i=0; $i < $id_count; $i++) { 
		$id = $id_array[$i];

		$result = $this->page->getPageByID($id);
		$this->page->deletePage($id);
        $this->page_comments->deleteCommentByPage($id);
        $this->Heatmap->deleteClicks($id,'page');
	 }//for loop end 
          
	 $this->_redirect('/admin/page/index');
	}// if condition
	
	/*code for delete bulk data end*/
   } // index function end
	
	// for edit post
   public function editAction()
   {
	   if ($this->user_session->msg != null) {
            $this->view->msg = $this->user_session->msg;
            $this->user_session->msg = null;
        }
       
    	$id = $this->_request->getParam('id');
    	$form = new Application_Form_PageForm();
    	$results = $this->url->getUrls();
    	$this->view->site_url= $results->site_url;
    	$this->view->page_id = $id;
    	
        if(isset($id)){
    	$this->user_session->id = $id;
    }
  
    if(isset($id)){
      	$result = $this->page->getPageByID($this->user_session->id);  	
    	
    	$this->view->page_id = $result->page_id;
    	
		$form->title->setValue($result->title);
    	$form->url_slug->setValue($result->url_slug);
        $form->contents->setValue(stripslashes($result->contents));
    	$form->tags->setValue($result->tags);
        $form->keyword_content->setValue($result->keyword_content);
        $form->description_content->setValue($result->description_content);
        $form->is_comment->setValue($result->is_comment);
        $form->page_for->setValue($result->page_for);
        $this->view->errorSlug = $result->url_slug; //set parmalink and url_slug text box 
		
       $this->view->form = $form;
    
	}
    
        if (!$this->_request->isPost()) {
			return;
        }
        
        $formData = $this->_request->getPost();
	       	
        if (!$form->isValid($formData)) {
		    $this->view->form = $form;
            return;
        }

	$formData['image'] = "";
    $formData['page_id']= $this->user_session->id;
	
	//$slug= $formData['url_slug'];
	//$formData['url_slug']= str_replace("-","", $slug);
	
    $formData['date_published']= date("Y-m-d H:i:s");
	
	//check from database if the slug is already in db
        $data = array ("url"=>$formData['url_slug']);
		$data["url"]=$formData['url_slug'];
		
		 if($this->page->checkPagesSlug($data, $this->user_session->id)){
    		 $this->view->msg =  "<div class='alert alert-danger'>Url Slug Is Already Exist. Please change to another.</div>";
    		 return;
		 }
    
    /*if($formData['is_comment'] == "on")
         {
            $formData['is_comment'] = 1;
         }*/
    
    	if($formData['submit'] == "0" ){
    			 
            $formData['is_in_draft'] = 0;
            $formData['draft_content'] = stripslashes($formData['contents']);    
			$result = $this->page->updatePage($formData);
			$this->user_session->msg = $result;
    	}
    	else 
        {
    		$formData['is_in_draft'] = 1;
            $formData['draft_content'] = stripslashes($formData['contents']);
			$result = $this->page->updateDraftPage($formData);
			$this->user_session->msg = $result;
    	}
		$this->_redirect("/admin/page/edit/id/".$formData['page_id']);
	}
		
	// delete page
	public function deletePageAction()
	{
		
	 $this->_helper->viewRenderer->setNoRender();
     $this->_helper->layout()->disableLayout();
  
		$id = $this->_request->getParam('id');
		$result = $this->page->getPageByID($id);
		
		$this->page->deletePage($id);
        $this->page_comments->deleteCommentByPage($id);
        $this->Heatmap->deleteClicks($id,'page');
        
		$this->_redirect('/admin/page/index');
	}
	
	
	//update URL slug only
	public function updateUrlSlugAction()
    {
       $this->ajaxed();
       $url = $this->getRequest()->getParam('url');
       $id = $this->getRequest()->getParam('id');
	   
	   	//check from database if the slug is already in db
		$data["url_slug"]=$url;
		
        $is_exist = $this->page->checkPagesSlug($url, $id); 
		 if($is_exist){
            echo 'exist';
		 } else {
		  
	       //$data = array('url_slug' => $url,'page_id' => $id); 

            $result = $this->page->updateUrlSlug($url, $id);
    
    	    if($result)
            {
                echo 'success';
            }
            else{
                echo 'error';
            }
        }
    }
    
    public function memberPagesAction()
    {
        $result = $this->page->getAllMemberPages($this->db);
    	$this->view->data = $result;
    }

	
	public function blockPageAction(){
		 $page_id = $this->_request->getParam('page_id');
		  // Because of following code we don't need a phtml file 
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout()->disableLayout();
			$this->page->block($page_id);
			$this->_redirect("/admin/page/member-pages");
	}
		
	public function unblockPageAction(){
		  $page_id = $this->_request->getParam('page_id');
		  // Because of following code we don't need a phtml file 
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout()->disableLayout();
			$this->page->unblock($page_id);
			$this->_redirect("/admin/page/member-pages");
	}
	
	
	//this function is used for every function that recieves a ajax call
    public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()){
		$this->_redirect('admin/index');
			return; // if not a ajax request leave function
		}
    }
	
	// Paginator action
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
            return $this->_forward('admin/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
	
}