<?php

class Admin_PostCategoryController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
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

	public function indexAction()
    {
        if (isset($this->user_session->msg)) {
            $this->view->msg = $this->user_session->msg;
            unset($this->user_session->msg);
        }
        
        $form = new Application_Form_PostCategoryForm();
        $this->view->form = $form;
        
        $result = $this->categoryTreeList();
        if (count($result) > 0) {
		  $this->Paginator($result, 10);
        } else {
            $this->view->empty_rec = true;
        }
        
        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();
            if (!$form->isValid($formData))
            {
                $this->view->form = $form;
                return;
            }
            
            $r = $this->post_category_model->addCategory($formData);
            if(isset($r)){
                $this->view->msg = "<div class='alert alert-success'>Category saved successfully!</div>";
                $form->reset();
            }
            else{
                $this->view->msg = "<div class='alert alert-danger'>Some error occure.</div>";
            }
        }
    } // index function end
    
    public function editAction(){
        $id = $this->_request->getParam('id');
        
        $result = $this->post_category_model->getCategoryById($id);
        $form = new Application_Form_PostCategoryForm();        
        $form->name->setValue($result->name);
        $form->parent_id->setValue($result->parent_id);
        $this->view->form = $form;
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();

            if (!$form->isValid($formData)) {
    			$this->view->form = $form;
    			return;
            }
            
            $formData["category_id"] = $id;
            $r = $this->post_category_model->updateCategory($formData);
            if($r){
                $this->view->msg = "<div class='alert alert-success'>Category updated successfully!</div>";                
            }
            else{
                $this->view->msg = "<div class='alert alert-danger'>Some error occure.</div>";
            }
        }
    } // edit function end
    
    public function deleteAction(){
        
        if ($this->_request->isPost()) {
            $bulkdata = $this->_request->getPost('bulkdata');

            for ($i = 0; $i < count($bulkdata); $i++) {
                $id = $bulkdata[$i];
                $this->pmc_model->updatePostCategory($id);
                $r = $this->post_category_model->deleteCategory($id);
            }
            
            if($r){
                $this->user_session->msg = "<div class='alert alert-success'>Categories deleted successfully!</div>";                
            }
            else{
                $this->user_session->msg = "<div class='alert alert-danger'>Some error occure.</div>";
            }
            
            $this->_redirect('/admin/post-category/index');
        }
        
        $id = $this->_request->getParam('id');
        $this->pmc_model->updatePostCategory($id);
        $r = $this->post_category_model->deleteCategory($id);
        
        if($r){
            $this->view->msg = "<div class='alert alert-success'>Category deleted successfully!</div>";                
        }
        else{
            $this->view->msg = "<div class='alert alert-danger'>Some error occure.</div>";
        }
        
        $this->_redirect('/admin/post-category/index');
    }
    
    private function categoryTreeList($parent_id = 0, $spacing = '', $tree_array = '')
    {
        $result = $this->post_category_model->getCategoriesByParent($parent_id);
        
        if (!is_array($tree_array)){
            $tree_array = array();
        }
        
        if (isset($result)) {
            foreach($result as $r) {
                $tree_array[] = array("category_id" => $r['category_id'], "name" => ($spacing . $r['name']));
                $tree_array = $this->categoryTreeList($r['category_id'], ('&nbsp;&nbsp;'.$spacing . '-&nbsp;'), $tree_array);
            }
        }        
        return $tree_array;
    }
    
    public function Paginator($results, $items) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($items);
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
    
} // class end