<?php
/**
 Author: Musavir 
 Date: October 2014
 Kuala Lumpur
 */
class Admin_CitiesController extends Zend_Controller_Action
{
		var $user_session = null;
		var $cities_session = null;
	    private $db = null;
        private $cities = null;
		private $authAdapter = null;
        protected $baseurl = '';
		private $results = null;
		

	public function init(){
	Zend_Layout::startMvc(
	array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
	$this->db = Zend_Db_Table::getDefaultAdapter();
    $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
    $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
	$this->user_session = new Zend_Session_Namespace("user_session");
    $this->cities_session = new Zend_Session_Namespace("cities_session");
				
//authorization for this controller
			ini_set("max_execution_time",0);
               $this->cities = new Application_Model_Cities();
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

			
    public function newAction(){
		$form = new Application_Form_CitiesForm();
		$this->view->form =  $form;
	//show message if it is set true 
	if (isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	
	if(isset($this->cities_session->country_id)){
	$form->country->setValue($this->cities_session->country_id);
	unset($this->cities_session->country_id);
	}
		
		if (!$this->_request->isPost()) {
			$this->view->form = $form;
		return;
		}

        $formData = $this->_request->getPost();
		if(trim($formData['country']) == 0) {
			$this->view->msg = "<div class='alert alret-danger'>Please Select Country</div>";
		return;
	    }
		
		if(trim($formData['state']) == 0) {
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
	    if(!(isset($formData['state']))){
			$this->view->msg = "<div class='alert alret-danger'>Please Select State</div>";
	return;
		}
			$formData['user_id'] = $this->user_session->user_id;
	
	$data = array("country_id" => $country_id,"state_id" => $state_id,"city_name" => $city_name);
		if($this->cities->checkCity($data)){
		$this->view->msg = "<div class='alert alert-danger'>City Already Exists</div>";
		return;
		}
		
		$this->user_session->msg = $this->cities->add($formData);
		$this->states_session->country_id = $formData['country'];
		
        $form->reset();
        
        $this->_redirect('/admin/states/new');
        
    } // new function end

    public function editAction()
    {

        $city_id = $this->_request->getParam('city_id');
        $form = new Application_Form_CitiesForm();
        $this->view->city_id = $city_id;


        $this->view->form = $form;
        if(!$this->_request->isPost())
        { 
            $result = $this->cities->getCityById($city_id);
            $data=$this->cities->getcountrycityId($city_id);
            $this->view->country_name=Application_Model_Countries::getCountryNameStatic($this->db, $data['country_id']);
            $this->view->state_name=Application_Model_States::getStateStatic($this->db, $data['state_id']);
             
            $form->country->setValue(Application_Model_Countries::getCountries($this->db,0));
            
            $this->view->cities=$result->city_name;
            $form->city_name->setValue($result->city_name);
            return;    
        }     

    $this->view->form = $form; 
      
    $formData = $this->_request->getPost();
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

$formData['city_id'] = $city_id;
$data = array("country_id" => $formData['country'],"state_id" => $formData['state_id'],"city_name" => $formData['city_name']);
	
		if($this->cities->checkCity($data)){
		$this->view->msg =  "<div class='alert alert-danger'>City Name Already Exists</div>";
		return;
		}


$this->view->msg = $this->cities->updateCityById($formData);
}

public function messagePageAction(){
			$this->view->msg = $this->cities_session->msg;
			unset($this->cities_session->country);
			unset($this->cities_session->state);
			unset($this->cities_session->add_more);
			unset($this->cities_session->msg);
}
			
public function saveCityAction(){			
$this->ajaxed();
$country_id = $this->_request->getParam('country_id');
$state_id = $this->_request->getParam('state_id');
$city_name = $this->_request->getParam('city_name');

if($country_id == 0) {
			echo "<div class='alert alert-danger'>Please Select Country</div>";
		return;
	    }

if($state_id == '') {
			echo "<div class='alert alert-danger'>Please Select State</div>";
		return;
	    }
		
if($city_name == '') {
			echo "<div class='alert alert-danger'>Please Enter City Name</div>";
		return;
	    }
		
	$data = array("country_id" => $country_id,"state_id" => $state_id,"city_name" => $city_name);
		if($this->cities->checkCity($data)){
		echo "<div class='alert alert-danger'>City Already Exists</div>";
		return;
		}

$msg = $this->cities->add($data);
echo $msg;
}

/* 
* DO NOT DELETE THIS FUNCITON IS FOR EXCEL SHEET UPLOAD EXAMPLE 
*/

public function bulkUploadAction(){
	$file_adapter = new Zend_File_Transfer_Adapter_Http();
	
	$form = new Application_Form_BulkCitiesForm();
	$this->view->form = $form;
	if(!$this->_request->isPost()){
		$this->view->form = $form;
		return;
	}
	
	$formData = $this->_request->getPost();
//	print_r($formData);die;
				if (!$form->isValid($formData)) {
					$this->view->form = $form;
					return;
				}
	
	if(!$file_adapter->isValid()){
		$excel_file = null;
	}
	
	if(isset($_FILES['bulk_cities'])){
		$excel_file = $_FILES['bulk_cities']['name'];
	}
//	echo $file_name;die;
	if(!$excel_file == null){
	try{
		move_uploaded_file($_FILES['bulk_cities']['tmp_name'],SYSTEM_PATH.'/uploads/'.$excel_file);
	}
	catch(Zend_File_Transfer_Exception $e){
		echo "Error Message :  ".$e->getMessage();
		return;
	}
	
	
	$arr = array();
	if(($handle = fopen(SYSTEM_PATH.'/uploads/'.$excel_file, "r" )) !== false){
		while($input_data = fgetcsv($handle))
		{
			$data = array(
						'lang_id' => Zend_Registry::get("lang_id"),
						'user_id' => $this->user_session->user_id,
						'city_code' => $input_data[0],
						'city_name' => $input_data[1],
						'country' => $formData['country_id'],
						'state' => $formData['state_id']);
			$this->cities->add($data);
		}
		
	}
		//================================ This commented code would be used ifg we load excel file ==============================
		//================== Please do not delete it ===================================
		/*$excel = new Core_PHPExcelReader();
		$excel->setOutputEncoding('UTF-8');     // sets encoding UTF-8 for output data
		$excel->read($excel_file);  
		$nr_sheets = count($excel->sheets);       // gets the number of worksheets

		for($sheet=0;$sheet<$nr_sheets;$sheet++) {
			for($row=1;$row<=$excel->sheets[$sheet]['numRows']&&($row<=$max_rows||$max_rows==0);$row++) {
				$data = array(
						'lang_id' => Zend_Registry::get("lang_id"),
						'state_name' => $excel->sheets[1]['cellsInfo'][$row][1]['rowspan'],
						'country_id' => $formData['country']);
						print_r($data);die;
			$this->states->addState($data);
			}
		}*/
	}//end of $file_name == null
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
 
 public function listAction(){ 
 $this->view->countries = Application_Model_Countries::getCountries($this->db,1);
 $user_id  = $this->user_session->user_id;
 $data['user_id'] = $user_id;
 $query_string = $this->_request->getParam("query_string");
		$results = null; 
		$user_id  = $this->user_session->user_id;
		$query_string = trim($query_string);
		if($query_string !=''){
        if(is_string($query_string)){
        $results = $this->cities->getSearchCities($query_string);
          }
		}
		else
		{
		  $results = $this->cities->getAllCities($this->db);
		}
   if (count($results) > 0) {
				 $this->Paginator($results);
				} else {
				$this->view->empty_rec = true;
				}
 
   
		/*$results = Application_Model_Countries::getAllCities($this->db,$user_id);
	
		
		if (count($results) > 0) {
				 $this->Paginator($results);
				} else {
				$this->view->empty_rec = true;
				}
*/
}	

public function bulkAction(){
	$form = new Application_Form_CitiesForm();
	$form->removeElement("city_name");
	$this->view->form =  $form;
	//show message if it is set true 
	if (isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	
	if(isset($this->cities_session->country_id)){
	$form->country->setValue($this->cities_session->country_id);
	unset($this->cities_session->country_id);
	}
		
		if (!$this->_request->isPost()) {
			$this->view->form = $form;
		return;
		}

        $formData = $this->_request->getPost();
		
		var_dump($formData);
		if(trim($formData['country']) == 0) {
			$this->view->msg = "<div class='alert alret-danger'>Please Select Country</div>";
		return;
	    }
		
		if(trim($formData['state']) == 0) {
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
	    if(!(isset($formData['state']))){
			$this->view->msg = "<div class='alert alret-danger'>Please Select State</div>";
	return;
		}
			$formData['user_id'] = $this->user_session->user_id;
	


//var_dump($formData['state']);

//return;

	$data = array("country_id" => $formData['country'],"state_id" => $formData['state'],"cities" => $formData['cities']);
				
		$this->user_session->msg = $this->cities->bulkAdd($data);
		$this->_redirect("/admin/cities/bulk");
	
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