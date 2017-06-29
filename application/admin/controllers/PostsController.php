<?php

class Admin_PostsController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $post = null;
		private $url = null;
		private $post_comment = null;
		private $results = null;
        private $Heatmap = null;
        private $post_category_model = null;
        private $pmc_model = null;

	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");

		ini_set("max_execution_time",(60*300));
		$this->post = new Application_Model_Posts();
		$this->url = new Application_Model_SiteInfo();
		$this->post_comment = new Application_Model_PostComments();
        $this->Heatmap = new Application_Model_HeatmapClicks();
        $this->post_category_model = new Application_Model_PostCategories();
        $this->pmc_model = new Application_Model_PostMapCategory();
        
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

   private function get_url()
   {
      return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['REQUEST_URI']
      );
    }
    
	public function newPostAction() 
	{
	   if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}
       
		$results = $this->url->getUrls();
		$this->view->site_url = $results->site_url;  
		  
		$form = new Application_Form_PostForm();
		$this->view->form = $form;
		
        // post categories
        $cat_form = new Application_Form_PostCategoryForm();
        $this->view->cat_form = $cat_form;
                
        $chk_list = $this->categoryCheckboxTree();
        $this->view->chk_list = $chk_list;
        // categories code end
        
		if (!$this->_request->isPost())return;
		$formData = $this->_request->getPost();
		
		if (!$form->isValid($formData))
	{
            $this->view->form = $form;
			$this->view->errorSlug =  $formData['url'];
            return;
        }
		 //check from database if the slug is already in db
		$data = array ("url"=>$formData['url']);
		$data["url"]=$formData['url'];
		 if($this->post->checkPostSlug($data)){
		 $this->view->msg =  "<div class='alert alert-danger'>Post Unique Parmalink Is Already Exist. Please change heading for unique parmalink.</div>";
		 $this->view->errorSlug =  $formData['url'];
		 return;
		 }
         
		//For Images
		$file_name = NULL;
		 try {
			$image = $_FILES['image']['name'];
			if(isset($image) && strlen($image) > 0 ) 
	       {
			$random = rand(9,999999);
			$file_name = $random . $image;
			$formData["image"] = $file_name;

			move_uploaded_file($_FILES["image"]['tmp_name'], SYSTEM_PATH."images/user/posts/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/user/posts/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'images/user/posts/500X500/'.$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'images/user/posts/200X200/'.$file_name);
	       }
	       else
            {
            $formData["image"] = 'placeholder.png';	
            } 
		}
		catch (Zend_File_Transfer_Exception $e)
		{
			throw new Exception('Bad data: '.$e->getMessage());  
		}
		
        
		$formData['user_id']= $this->user_session->user_id;
		$formData['date_created']= date("Y-m-d H:i:s");
		$formData['date_published']= date("Y-m-d H:i:s");
		//$slug= $formData['url'];
		//$formData['url']= str_replace("-","", $slug);

        if($formData['submit'] == "0" )
         {
            $formData['is_in_draft'] = 0;
            $formData['draft_content'] = $formData['contents'];
			$result = $this->post->addPost($formData);	
            
            if(!isset($formData['category']))
            {
                $this->pmc_model->addPostCategory($result,1);
            }else{            
                foreach($formData['category'] as $cat_id){
                    $this->pmc_model->addPostCategory($result,$cat_id);
               	}
            }
			//$this->view->msg = $result;
         }
         else
         {
            $formData['is_in_draft'] = 1;
            $formData['draft_content'] = $formData['contents'];
            
			$result = $this->post->addDraftPost($formData);	
                        
            if(!isset($formData['category']))
            {
                $this->pmc_model->addPostCategory($result,1);
            }else{            
                foreach($formData['category'] as $cat_id){
                    $this->pmc_model->addPostCategory($result,$cat_id);
               	}
            }	
		    //$this->view->msg = $result;
         }
$this->_redirect('admin/posts/edit-post/id/'.$result);
		//clear all form fields
	   //$form->reset();
	}

	//for post list
	public function indexAction(){ 

        if($this->user_session->role_id==3||$this->user_session->role_id==4)
        {
            $drafted_posts = $this->_request->getParam('status');
            if(isset($drafted_posts)){            
                $this->view->data =$this->post->getAllDraftPostsByUser($this->db,$this->user_session->user_id);
            }else{
                $results = $this->post->getAllPostsByUser($this->db,$this->user_session->user_id);
                $this->view->data = $results;       
            }
        }
        else
        {
            $drafted_posts = $this->_request->getParam('status');
            if(isset($drafted_posts)){            
                $this->view->data =$this->post->getAllDraftPosts($this->db);
            }else{
                $results = $this->post->getAllPosts($this->db);
                $this->view->data = $results;
                
            }
        }
        
        $post_categories = $this->pmc_model->getAllPostCategory($this->db);
        $this->view->post_categories = $post_categories;
             
     /*code for delete bulk data start*/
	
	 if(isset($_POST['submit'])) {
	$id_array = $_POST['bulkdata']; // return array
	$id_count = count($_POST['bulkdata']); // count array
	//var_dump($id_array);
	//return;
	for($i=0; $i < $id_count; $i++) {
		$id = $id_array[$i];

		$result = $this->post->getPostByID($id);
		
		if(isset($result->image) && strlen($result->image) > 0 && $result->image == 'placeholder.png'){
		
    		$this->post->removePost($this->db, $id);
    		$this->post_comment->deleteCommentByPost($id);
            $this->Heatmap->deleteClicks($id,'post');
            $this->pmc_model->deletePostCategory($id);
    	}
    	else{
        	unlink(SYSTEM_PATH.'images/user/posts/500X500/'.$result->image);
        	unlink(SYSTEM_PATH.'images/user/posts/'.$result->image);
        	unlink(SYSTEM_PATH.'images/user/posts/200X200/'.$result->image);
    		$this->post->removePost($this->db, $id);
    		$this->post_comment->deleteCommentByPost($id);
            $this->Heatmap->deleteClicks($id,'post');
            $this->pmc_model->deletePostCategory($id);
    	}//else end
	 }//for loop end
	 $this->_redirect('/admin/posts/index');
	}// if cond
	
	/*code for delete bulk data end*/
 } // function end 

    // for edit post
   public function editPostAction()
   {
        if($this->user_session->msg!=null)
		{
			$this->view->msg = $this->user_session->msg;
			$this->user_session->msg = null;
		}

	$id = $this->_request->getParam('id');
	$results = $this->url->getUrls();
		
    $this->view->site_url = $results->site_url;
	$form = new Application_Form_PostForm();
    
    // post categories
        $cat_form = new Application_Form_PostCategoryForm();
        $this->view->cat_form = $cat_form;
                
        $cat_id_list = $this->pmc_model->getCategoryIdByPostId($id);
        $chk_list = $this->categoryCheckboxTree();
        $this->view->chk_list = $chk_list;  
        
        $id_array = '';
        foreach($cat_id_list as $i){
            $id_array .= $i['category_id'].',';
        }
        $this->view->cat_id_list = $id_array;
        
        // categories code end

if(isset($id)){
	$this->user_session->id = $id;
}

if(isset($id) || isset($this->user_session->id)){
  	$result = $this->post->getPostByID($this->user_session->id);

	$this->view->id = $result->post_id;
	$this->view->heading = $result->heading;
    $this->view->url_slug = $result->url;
	$form->heading->setValue($result->heading);
	$form->contents->setValue($result->contents);
    $form->image->setValue($result->image);
	$this->view->save_description = $result->draft_content;
	$form->description_content->setValue($result->description_content);
	$form->keyword_content->setValue($result->keyword_content); 
	$form->tags->setValue($result->tags);
    $form->is_comment->setValue($result->is_comment);
	$form->submit->setLabel("Update");
    
    $this->view->image = $result->image;
    $this->user_session->image = $result->image;
    $this->view->is_comment = $result->is_comment;
    
    $this->view->form = $form;
}
     if (!$this->_request->isPost()) {
			$this->view->form = $form;
			return;
        }

        $formData = $this->_request->getPost();

	   if (!$form->isValid($formData)) {
			$this->view->form = $form;
			$this->view->url_slug =  $formData['url'];
			return;
        }
	
		//For Image upload
	$file_name = NULL;
	$image = $_FILES['image']['name'];
	try {
	if(!empty($image) && $result->image == 'placeholder.png') { 
		//var_dump("condition1");
		//return;
			$random = rand(9,999999);
			$file_name = $random . $image;
			$formData["image"] = $file_name;
			
			move_uploaded_file($_FILES["image"]['tmp_name'], SYSTEM_PATH."images/user/posts/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/user/posts/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'images/user/posts/500X500/'.$file_name); 
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'images/user/posts/200X200/'.$file_name);	
	}
	elseif(isset($image) && strlen($image) > 0 && $result->image != 'placeholder.png'){
		//var_dump("condition 2");
		//return;
			unlink(SYSTEM_PATH."images/user/posts/".$result->image);
		    unlink(SYSTEM_PATH."images/user/posts/500X500/".$result->image);
			unlink(SYSTEM_PATH.'images/user/posts/200X200/'.$result->image);
			
			$random = rand(9,999999);
			$file_name = $random . $image;
			$formData["image"] = $file_name;
			
			move_uploaded_file($_FILES["image"]['tmp_name'], SYSTEM_PATH."images/user/posts/".$file_name);
			$thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/user/posts/".$file_name);
			$thumb->resize(500,500);
			$thumb->save(SYSTEM_PATH.'images/user/posts/500X500/'.$file_name);
			$thumb->resize(200,200);
			$thumb->save(SYSTEM_PATH.'images/user/posts/200X200/'.$file_name);
	}
	else{
		//var_dump("condition3");
		//return;
            $formData['image']=  $result->image;
        }
	}
    	catch (Zend_File_Transfer_Exception $e)
    		{
    			throw new Exception('Bad data: '.$e->getMessage());
    		}
    
	$formData['post_id']= $this->user_session->id;

	$slug= $formData['url'];
    
    //check from database if the slug is already in db
	$data = array ("url"=>$formData['url']);
		$data["url"]=$formData['url'];
		
		 if($this->post->checkPostsSlug($data, $this->user_session->id)){
		 $this->view->msg =  "<div class='alert alert-danger'>Post Unique Parmalink Is Already Exist. Please change heading for unique parmalink.</div>";
		 return;
		 }
		 
        /*if($formData['is_comment'] == "on")
         {
            $formData['is_comment'] = 1;
         }*/
         
		if($formData['submit'] == "0" )
        {
			$formData['is_in_draft'] = 0;
            $formData['draft_content'] = $formData['description'];
		$result = $this->post->updatePost($formData);
        
            if(!isset($formData['category']))
            {
                $this->pmc_model->addPostCategory($id,1);
            }else{
                $this->pmc_model->deletePostCategory($id);
                
                foreach($formData['category'] as $cat_id){
                    $this->pmc_model->addPostCategory($id,$cat_id);
               	}
            } 
            
    	$this->view->msg = $result;
    	}
    	else
        {
    		$formData['is_in_draft'] = 1;
            $formData['draft_content'] = $formData['description'];
		$result = $this->post->updateDraftPost($formData);
        
            if(!isset($formData['category']))
            {
                $this->pmc_model->addPostCategory($id,1);
            }else{
                $this->pmc_model->deletePostCategory($id);
                
                foreach($formData['category'] as $cat_id){
                    $this->pmc_model->addPostCategory($id,$cat_id);
               	}
            }
    	$this->view->msg = $result;
    	}
        
		$this->_redirect("/admin/posts/edit-post/id/".$formData['post_id']);
	}
		/*update only url slug through ajax*/
	public function updatePostUrlSlugAction()
    {
       $this->ajaxed();
       $url = $this->getRequest()->getParam('url');
       $id = $this->getRequest()->getParam('id');
	    
		//check from database if the slug is already in db
	//$data = array ("url"=>$url);
		$data["url"]=$url;
		$slug = $this->post->checkPostsSlug($url, $id);
	
		 if($this->post->checkPostsSlug($data, $id)){
			echo 'exist'; 
		 return;
		 } 
		
        $url = array('url' => $url, 'post_id' => $id); 

		$result = $this->post->updateUrl($url);
        
        if($result)
        {
            echo 'success';   
        }
        else{
            echo 'error';
        }       
    }

	// delete single post
	public function deletePostAction()
	{

	 $this->_helper->viewRenderer->setNoRender();
     $this->_helper->layout()->disableLayout();

		$id = $this->_request->getParam('id');
		$result = $this->post->getPostByID($id);
        
        if(isset($result->image) && strlen($result->image) > 0 && $result->image == 'placeholder.png'){
		
    		$this->post->removePost($this->db, $id);    		
    	}
    	else{
        	unlink(SYSTEM_PATH.'images/user/posts/500X500/'.$result->image);
        	unlink(SYSTEM_PATH.'images/user/posts/'.$result->image);
        	unlink(SYSTEM_PATH.'images/user/posts/200X200/'.$result->image);
    		$this->post->removePost($this->db, $id);
    	}
                
        $this->post_comment->deleteCommentByPost($id);
        $this->Heatmap->deleteClicks($id,'post');
        $this->pmc_model->deletePostCategory($id);
        
        $this->_redirect('/admin/posts/index');
    } 

    public function newCategoryAction(){
        
        $cat_form = new Application_Form_PostCategoryForm();
        
        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();
            if (!$cat_form->isValid($formData))
            {
                $this->view->cat_form = $cat_form;
                $this->_redirect('/admin/posts/new-post');
            }
            
            $r = $this->post_category_model->addCategory($formData);
            if(isset($r)){
                $this->user_session->msg = "<div class='alert alert-success'>Category saved successfully!</div>";
                $cat_form->reset();
            }
            else{
                $this->user_session->msg = "<div class='alert alert-danger'>Some error occure.</div>";
            }
        }
        
        $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
        //$this->_redirect('/admin/posts/new-post');
    }

    private function categoryCheckboxTree($parent_id = 0,$tree_array = '')
    {
        $result = $this->post_category_model->getCategoriesByParent($parent_id);
        
        if (!is_array($tree_array)){
            $tree_array = array();
        }
        
        $tree_array[] = array("name" => '<ul>');
        if (isset($result)) {
            foreach($result as $r) {
                $tree_array[] = array("name" => '<li><label><input type="checkbox" value="'.$r['category_id'].'" name="category[]" /> '.$r['name'].'</label></li>');
                $tree_array = $this->categoryCheckboxTree($r['category_id'], $tree_array);
            }
        }
        $tree_array[] = array("name" => '</ul>');
        return $tree_array;
    }

	// Paginator action
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
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