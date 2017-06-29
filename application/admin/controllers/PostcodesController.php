<?php
/**
 Author: Musavir 
 Date: October 2014
 Kuala Lumpur
 */
class Admin_PostcodesController extends Zend_Controller_Action
{
		var $user_session = null;
		var $pc_session = null;
	    private $db = null;
        private $postcodes = null;
		private $nearbypostcodes = null;
		private $authAdapter = null;
        protected $baseurl = '';
		private $results = null;
		

	public function init(){
	Zend_Layout::startMvc(
	array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
	
	
	$this->postcodes = new Application_Model_Postcodes();
	$this->nearbypostcodes = new Application_Model_NearByPostcodes();
	
	$this->db = Zend_Db_Table::getDefaultAdapter();
    $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
    $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
	$this->user_session = new Zend_Session_Namespace("user_session");
    $this->pc_session = new Zend_Session_Namespace("pc_session");
				
//authorization for this controller
			ini_set("max_execution_time",0);
               $this->postcodes = new Application_Model_Postcodes();
               $auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
			$this->_redirect($this->baseurl.'/admin/index/login');;
                }
				
	// A member can't log in to admin section 				
	if($this->user_session->role_id == 5){
	$this->_redirect('/index/no-permissions');
	}
				
		if(isset($this->user_session->role_id)){
		
			$role = array('1' => 'Admin','2' => 'Payment Manager','3' => 'Content Manager','4' => 'Listing Manager', '3' => 'Deals Manager' );
				$this->view->user = array(
					'user_id' => $this->user_session->user_id,
					'email' => $this->user_session->email,
					'role_id' => $this->user_session->role_id,
					'role_name'	=> $role[$this->user_session->role_id],
					'user_name'	=>$this->user_session->firstname,
					);
}
}

public function editAction(){
	$pc_id = $this->_request->getParam('pc_id');
	$form = new Application_Form_PostcodesForm();
	
	
	
	 $result = $this->postcodes->getPostCodeById($pc_id);
	 $form->postcode->setValue($result->postcode);
	 $form->area_name->setValue($result->area_name);
	 $form->areas->setValue($result->areas);
	 
	$this->view->form = $form;
    if(!$this->_request->isPost()){
		$this->view->form = $form;
		return;
	}
	
	$formData = $this->_request->getPost();
	/* if (!$form->isValid($formData)) {
	$this->view->form = $form;
	return;
	}	 */
	$formData['pc_id'] = $pc_id;
	
	$this->view->msg = $this->postcodes->edit($formData);
}

public function editNearpostcodeAction(){
	$pc_nb_id = $this->_request->getParam('pc_nb_id');
	$form = new Application_Form_NearPostcodesForm();
	
	
	
	 // $result = $this->nearbypostcodes->getPcNb($pc_nb_id);
	 // $form->areas->setValue($result->areas);
	 // $form->area_name->setValue($result->area_name);
	 // $form->areas->setValue($result->areas);
	 // $form->country->setValue($result->country);
	 
	$this->view->form = $form;
    if(!$this->_request->isPost()){
		$this->view->form = $form;
		return;
	}
	
	$formData = $this->_request->getPost();
	/* if (!$form->isValid($formData)) {
	$this->view->form = $form;
	return;
	}	 */
	$formData['pc_nb_id'] = $pc_nb_id;
	
	$this->view->msg = $this->nearbypostcodes->edit($formData);
}


/* Country related functions end here */
			
public function newAction(){
		$form = new Application_Form_PostcodesForm();
		$this->view->form =  $form;

		if (!$this->_request->isPost()) {
			$this->view->form = $form;
		return;
		}

    }			
     public function nearPostcodesAction(){
		
		$form = new Application_Form_NearPostcodesForm();
		$this->view->form =  $form;
		if (!$this->_request->isPost()) {
			$this->view->form = $form;
		return;
		}
}			


public function messagePageAction(){
			$this->view->msg = $this->cities_session->msg;
			unset($this->cities_session->country);
			unset($this->cities_session->state);
			unset($this->cities_session->add_more);
			unset($this->cities_session->msg);
}
		
public function saveNearPostcodeAction(){			
$this->ajaxed();
$pc_id = $this->_request->getParam('postcode_id');
$areas = $this->_request->getParam('areas');

/*echo $country_id. " ". $state_id. " ". $city_id;
return;*/
if($pc_id == '') {
			echo "<div class='alert alert-danger'>Please Enter Postcode</div>";
		return;
	    }

if($areas == '' || strlen($areas) < 1) {
			echo "<div class='alert alert-danger'>Please Enter Near By Postcode by comma separator</div>";
		return;
	    }
		
	$data = array("pc_id" => $pc_id,"areas" => $areas);

$msg = $this->nearbypostcodes->add($data);
echo $msg;
}		

	
    public function savePostcodeAction()
    {			
        $this->ajaxed();
        $country_id = $this->_request->getParam('country_id');
        $state_id = $this->_request->getParam('state_id');
        $city_id = $this->_request->getParam('city_id');
        $postcode = $this->_request->getParam('postcode');
        $area_name= $this->_request->getParam('area_name');
        $areas = $this->_request->getParam('areas');
        
        
        /*echo $country_id. " ". $state_id. " ". $city_id;
        return;*/
        
        if($country_id == 0) {
			echo "<div class='alert alert-danger'>Please Select Country</div>";
		return;
	    }

if($state_id == '') {
			echo "<div class='alert alert-danger'>Please Select State</div>";
		return;
	    }

if($city_id == '') {
			echo "<div class='alert alert-danger'>Please Select City</div>";
		return;
	    }

if($postcode == '') {
			echo "<div class='alert alert-danger'>Please Enter Postcode</div>";
		return;
	    }
		
		
if($area_name == '') {
			echo "<div class='alert alert-danger'>Please Enter Area Name</div>";
		return;
	    }
		
		
	$data = array("country_id" => $country_id,"state_id" => $state_id, "city_id" => $city_id,"postcode" => $postcode,
	"area_name" => $area_name,"areas" => $areas);
		/* 
		if($this->postcodes->checkPostcodeOnly(trim($postcode))){
		echo "<div class='alert alert-danger'>Postcode Already Exists</div>";
		return;
		}
		 */
		if($this->postcodes->checkPostcode($data)){
		echo "<div class='alert alert-danger'>Postcode Already Exists</div>";
		return;
		}
		
    
    $msg = $this->postcodes->add($data);
    echo $msg;
    
        //$this->_redirect("/admin/postcodes/new");
    }

public function bulkAction(){
	
	if (isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	

	$form = new Application_Form_PostcodesForm();
	$form->removeElement('postcode');
	$form->removeElement('areas');
	$form->removeElement('area_name');
	$this->view->form = $form;

	if(!$this->_request->isPost()){
		$this->view->form = $form;
		return;
	}
	
	$formData = $this->_request->getPost();

	//var_dump($formData);
//	return;

if(trim($formData['country']) == 0) {
			$this->view->msg = "<div class='alert alret-danger'>Please Select Country</div>";
		return;
	    }
		
		if(trim($formData['state_id']) == 0) {
			$this->view->msg = "<div class='alert alret-danger'>Please Select State</div>";
		return;
	    }
				if (!$form->isValid($formData)) {
					$this->view->form = $form;
					return;
				}
	if(!(isset($formData['country']))) {
			$this->view->msg = "<div class='alert alret-danger'>Please Select Country</div>";
				return;
	    }
	    if(!(isset($formData['state_id']))){
			$this->view->msg = "<div class='alert alret-danger'>Please Select State</div>";
	return;
		}


	$data = array("country_id" => $formData['country'],"state_id" => $formData['state_id'],"postcodes" => $formData['postcodes']);
		$this->user_session->msg = $this->postcodes->bulkAdd($data);
		$this->_redirect("/admin/postcodes/bulk");
	
	}
	






public function confirmDeleteAction(){
    $category_id = $this->_request->getParam('category_id');
    $category_table = new Application_Model_Admin_Category();
    $cat_name = $category_table->getCategoryName($category_id);
    $cat_image = $category_table->getCategoryImage($category_id);
    
    $this->view->category_name = $cat_name;
  $this->view->category_image = $cat_image;
  $this->view->category_id = $category_id;

}


//on deleting this category this category_id will be removed 
//from all product_categories and if this category has parent 
//category it will assign to its all child categories if does not have 
//parent category children will assign zero as parent category id 
// all images of this category will be removed also

public function deleteAction(){
    $category_id = $this->_request->getParam('category_id');
    $category_table = new Application_Model_Admin_Category();
   $parent_id = $category_table->getParentID($category_id);
}
 
 public function listNearpostAction()
 {
 $query_string = $this->_request->getParam("query_string");
      $this->view->countries = Application_Model_Countries::getCountries($this->db,1);
     $user_id  = $this->user_session->user_id;
	$form = new Application_Form_NearPostcodesForm();
	$this->view->form = $form;
	 if(isset($query_string) && $query_string !=''){
	$this->view->qs = true;
		$results = $this->nearbypostcodes->getPostcode($this->db,$query_string);
		if (count($results) > 0) {
				 $this->Paginator($results);
				} else {
				$this->view->empty_rec = true;
				}
		 
		 }
		 else
		 {
		 $results = $this->nearbypostcodes->getAllPostcodes($this->db);
		 $this->Paginator($results);
		 }
 }
 
 public function listAction(){ 
      
	 $query_string = $this->_request->getParam("query_string");
      $this->view->countries = Application_Model_Countries::getCountries($this->db,1);
     $user_id  = $this->user_session->user_id;
	 //$this->view->db = $this->db;
	 if(isset($query_string) && $query_string !=''){
	$this->view->qs = true;
		$results = $this->postcodes->getPostcode($this->db,$query_string);
		if (count($results) > 0) {
				 $this->Paginator($results);
				} else {
				$this->view->empty_rec = true;
				}
		 
		 }
		 else
		 {
		 $results = $this->postcodes->getAllPostcodes($this->db);
		 $this->Paginator($results);
		 }

}	

	public function getAllPostcodesAction(){
	$this->ajaxed();
	// $results = $this->postcodes->getAllPostcodes($this->db);
	// if (count($results) > 0) {
				 // $this->Paginator($results);
				// } else {
				// $this->view->empty_rec = true;
				// }
	}
        
    public function getCountriesPostcodesAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
       
        $results = $this->postcodes->getCountriesPostcodes($country_id, $this->db);
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

    
//this function is used for every function that recieves a ajax call
    public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()
            )return; // if not a ajax request leave function

    }

    public function __call($method, $args) {
        if ('Action' == substr($method, -6)) {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('amdin/index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                . $method
                . '" called',
                500);
    }
}//class ends 