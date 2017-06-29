<?php

class CategoriesController extends Zend_Controller_Action {
    private $baseurl = '';
	private $member_session = null;
	private $db = null;
    private $cookie = null;
    private $results = null;
    private $cat_model = null;
	
    public function init() {
		$this->_helper->layout->setLayout('categories');
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->member_session = new Zend_Session_Namespace("member_session");
        $this->checkout_session = new Zend_Session_Namespace("checkout_session");
        
        $this->cat_model = new Application_Model_PageCategories();
        
	    $this->view->user_role = $this->member_session->role_id;
        $this->view->logged_user_id = $this->member_session->member_id;
    }

    public function indexAction() 
    {
  		$this->view->category_list = $this->getCategoryTree();
        
        $result = $this->cat_model->getCategoriesByAlphabate();
        $this->view->list = $result;
    }
    
    public function loadCategoriesAction()
    {
        $this->ajaxed();
        
        $category = $this->_request->getPost('category');
        
        $result = $this->cat_model->getCategoriesByAlphabate($category);
        
        $html = '';
        foreach($result as $r){
            $html .= "<li><a href='/categories/search/category/".$r['category_id']."'>".$r['category_name']."</a></li>";
        }
        echo $html;
    }
    
    public function searchAction()
    {
        $category = $this->_request->getParam('category');
        $state = $this->_request->getParam('state');
        $city = $this->_request->getParam('city');
        
        $this->view->category = $category; 
        $this->view->state = $state;
        $this->view->city = $city;
        $this->view->category_list = $this->getCategoryTree();
        
        $pages = new Application_Model_Pages();
        $search_result = '';
        if($state==0 && $city==0){
            $search_result = $pages->searchCategoryByCountry($this->db, $category);    
        }
         
        if($state>0 && $city==0){
            $search_result = $pages->searchCategoryByState($this->db, $category, $state);    
        }

        if($state>0 && $city>0){
            $search_result = $pages->searchCategoryByStateCity($this->db, $category, $state, $city);    
        }
                           
        //$this->view->search_result = $search_result;
        $this->Paginator($search_result);        
    }
    
    private function getCategoryTree($parent_id = 0, $spacing = '', $tree_array = '')
    {
        $result = $this->cat_model->getCategoriesByParent($parent_id);
        
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

   public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
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
