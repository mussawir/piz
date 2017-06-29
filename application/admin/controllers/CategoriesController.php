<?php
/**
 Author: Musavir 
 Date: October 2014
 Kuala Lumpur
 */
class Admin_CategoriesController extends Zend_Controller_Action
{
		var $user_session = null;
        private $category_session = null;
        private $db = null;
        private $authAdapter = null;
        private $categories = null;
		private $list_categories = null;
	    protected $baseurl = '';
        private $adapter = null;

	public function init()
    {
        	Zend_Layout::startMvc(
        		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
        	$this->db = Zend_Db_Table::getDefaultAdapter();
              $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
              $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function

                $this->user_session = new Zend_Session_Namespace("user_session");
                $this->category_session = new Zend_Session_Namespace("category_session");

            //authorization for this controller
			ini_set("max_execution_time",0);
               $this->list_categories = new Application_Model_PageCategories();
			    
               $auth = Zend_Auth::getInstance();
               
    		//if not loggedin redirect to login page
    		if (!$auth->hasIdentity()){
			$this->_redirect($this->baseurl.'/admin/index/login');;
                }
                
				if(isset($this->user_session->role_id)){
		
			$role = array('1' => 'Admin','2' => 'Payment Manager','3' => 'Content Manager','4' => 'Listing Manager', '5' => 'Deals Manager' );
				$this->view->user = array(
					'user_id' => $this->user_session->user_id,
					'email' => $this->user_session->email,
					'role_id' => $this->user_session->role_id,
					'role_name'	=> $role[$this->user_session->role_id],
					'user_name'	=>$this->user_session->firstname,
					);

        }
    }
				
    public function indexAction(){
	
	}


    public function listingMainAction()
    {

        $this->adapter = new Zend_File_Transfer_Adapter_Http();
		$form = new Application_Form_CategoryForm();
		$form->removeElement("parent_id");
		$form->is_main->setValue('0');
		$form->is_featured->setValue('0');
		$this->view->form =  $form;
    	//show message if it is set true 
    	if (isset($this->user_session->msg)){
        	$this->view->msg = $this->user_session->msg;
        	   unset($this->user_session->msg);
        	}
	
    		if (!$this->_request->isPost()) {
    			$this->view->form = $form;
    		return;
		}
	
        $formData = $this->_request->getPost();
		if (!$form->isValid($formData)) {
			$this->view->form = $form;
			return;
		}
		
	$formData['user_id'] = $this->user_session->user_id;
	
	$data = array("category_name" => $formData['category_name']);
		
		if($this->list_categories->checkMainCatName($data)){
		$this->view->msg = "<div class='alert alert-danger'>The Main Category ".$formData['category_name']. " Already Exists</div>";
		return;
		}
		
		if($this->list_categories->checkCode($formData['code'])){
		$this->view->msg = "<div class='alert alert-danger'>Category code ".$formData['code']. " Already Exists</div>";
		return;
		}
		
		
/*if (!$this->adapter->isValid())
{
//    throw new Exception('Not a valid data, Bad image data: '.implode(',', $this->adapter->getMessages()));
$this->view->msg = "<div class='alert alert-danger'>Please upload category image</div>";
$this->view->form = $form;
return;
}*/

$file_name = NULL;
if ($this->adapter->isValid())
{
try {
		$image_name = $_FILES['myfile']['name'];
        $random = rand(10,10000);
        $time = time() + (7 * 24 * 60 * 60);
        $file_name = $time . $random . $image_name;
 
        move_uploaded_file($_FILES['myfile']['tmp_name'], SYSTEM_PATH ."/images/categories/originals/".$file_name);
        $thumb = new Application_Model_Thumbnail(SYSTEM_PATH ."/images/categories/originals/".$file_name);
	$thumb->resize(250,250);
	$thumb->save(SYSTEM_PATH .'/images/categories/250X250/'.$file_name);
        $thumb->resize(100,100);
	$thumb->save(SYSTEM_PATH .'/images/categories/100X100/'.$file_name);
  $formData['parent_id'] = 0;

}
catch (Zend_File_Transfer_Exception $e)
{
    throw new Exception('Bad image data: '.$e->getMessage());
}
}
	$formData['category_image'] = $file_name;
	$formData['parent_id'] = 0;
    $this->user_session->msg = $this->list_categories->add($formData, $file_name);
	$this->_redirect('/admin/categories/listing-main');		
}			

public function listingSubAction(){

$this->adapter = new Zend_File_Transfer_Adapter_Http();
$form = new Application_Form_CategoryForm();
$form->is_main->setValue('0');
$form->is_featured->setValue('0');
$this->view->form =  $form;
	//show message if it is set true 
	if (isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	
	if (!$this->_request->isPost()) {
			$this->view->form = $form;
		return;
		}
		
        $formData = $this->_request->getPost();
		if (!$form->isValid($formData)) {
			$this->view->form = $form;
			return;
		}
		

if($formData['parent_id'] == 0){
$this->view->msg = "<div class='alert alert-danger'>Please select Main Category to proceed</div>";
		return;
		
	}

$formData['user_id'] = $this->user_session->user_id;


if(isset($formData['parent_id'])){
$main_cat = $formData['parent_id'];
	}

if(isset($formData['sub-cat1'])){
$sub_cat1 = $formData['sub-cat1'];
	}
if(isset($formData['sub-cat2'])){
$sub_cat2 = $formData['sub-cat2'];
	}
if(isset($formData['sub-cat3'])){
$sub_cat3 = $formData['sub-cat3'];
	}

if(isset($main_cat) && $main_cat !==''){
	$parent_id = $main_cat;
	}
	if(isset($sub_cat1) && $sub_cat1 !==''){
	$parent_id = $sub_cat1;
	}
	if(isset($sub_cat2) && $sub_cat2 !==''){
	$parent_id = $sub_cat2;
	}
	if(isset($sub_cat3) && $sub_cat3 !==''){
	$parent_id = $sub_cat3;
	}
	$formData['parent_id'] = $parent_id;

$data = array("category_name" => $formData['category_name'], "parent_id" => $parent_id);
		if($this->list_categories->checkSubCatName($data)){
		$this->view->msg = "<div class='alert alert-danger'>The Sub Category ".$formData['category_name']. " Already Exists</div>";
		return;
		}
		
if($this->list_categories->checkCode($formData['code'])){
		$this->view->msg = "<div class='alert alert-danger'>Category code ".$formData['code']. " Already Exists</div>";
		return;
		}		

/*if (!$this->adapter->isValid())
{
//    throw new Exception('Not a valid data, Bad image data: '.implode(',', $this->adapter->getMessages()));
$this->view->msg = "<div class='alert alert-danger'>Please upload category image</div>";
$this->view->form = $form;
return;
}
*/
$file_name = NULL;
if ($this->adapter->isValid()){
try {
        
  /*      if (isset($_FILES['myfile']) )
{
	$file = $_FILES['myfile']['tmp_name'];
	$error = false;
	$size = false;

	if (!is_uploaded_file($file) || ($_FILES['myfile']['size'] > 2 * 1024 * 1024) )
	{
		$this->view->msg = "<div class='alert alert-danger'>Please upload only files smaller than 2Mb!</div>";  return;
	}
	if (!($size = @getimagesize($file) ) )
	{
		$this->view->msg  = "<div class='alert alert-danger'>Please upload only images, no other files are supported.</div>";  return;
	}
	if (!in_array($size[2], array(1, 2, 3, 7, 8) ) )
	{
		$this->view->msg  = "<div class='alert alert-danger'>Please upload only images of type JPEG.</div>";  return;
	}
	if (($size[0] < 25) || ($size[1] < 25))
	{
		$this->view->msg  = "<div class='alert alert-danger'>Please upload an image bigger than 25px.</div>";
	        return;
        }
}*/
            
	$image_name = $_FILES['myfile']['name'];
        $random = rand(10,10000);
        $time = time() + (7 * 24 * 60 * 60);
        $file_name = $time . $random . $image_name;
 
        move_uploaded_file($_FILES['myfile']['tmp_name'], SYSTEM_PATH ."/images/categories/originals/".$file_name);
        $thumb = new Application_Model_Thumbnail(SYSTEM_PATH ."/images/categories/originals/".$file_name);
	$thumb->resize(250,250);
	$thumb->save(SYSTEM_PATH .'/images/categories/250X250/'.$file_name);
        $thumb->resize(100,100);
	$thumb->save(SYSTEM_PATH .'/images/categories/100X100/'.$file_name);
 
}
catch (Zend_File_Transfer_Exception $e)
{
    throw new Exception('Bad image data: '.$e->getMessage());
}
}
 $formData['parent_id'] = $parent_id;
 $formData['category_image'] = $file_name;
 
    $this->user_session->msg = $this->list_categories->add($formData, $file_name);
	$this->_redirect('/admin/categories/listing-sub');		

}

/* edit categories from categories list */

public function editCatAction(){	
$category_id = $this->_request->getParam('id');
$this->view->category_id = $category_id;
$this->adapter = new Zend_File_Transfer_Adapter_Http();
$form = new Application_Form_CategoryForm();
//$form->submit->setLabel('Update'); 
   
  if(isset($this->user_session->msg)){
	  $this->view->msg = $this->user_session->msg;
	  unset($this->user_session->msg);
	  }
  

if ($category_id != 0){
		$result = $this->list_categories->getCategoryByID($category_id);
  $form->category_name->setValue($result->category_name);
  $form->code->setValue($result->code);
  $this->view->category_image = $result->category_image;
  $form->is_main->setValue($result->is_main);
  $form->is_featured->setValue($result->is_featured);
  		}else{
		$this->_redirect('admin/categories/list-main-cat');	
			}   
 
 $old_code = $result->code; 
 $old_name = $result->category_name; 
 
 $form->removeElement('parent_id');
 $this->view->form = $form;
   
    if (!$this->_request->isPost()) {
            return;
        }

    $formData = $this->_request->getPost();
	$formData['user_id'] = $this->user_session->user_id;
	$data = array("category_name" => $formData['category_name']);

		if(strcasecmp($old_code, $formData['code']) != 0){ 
		if($this->list_categories->checkCode($formData['code'])){
		$this->view->msg = "<div class='alert alert-danger'>Category code ".$formData['code']. " is occupied by an other category </div>";
		return;
		}
		}

		if (!$form->isValid($formData)) {
		//	$this->view->msg = "step va";
           return;
        } 
 
 //$this->view->msg = "step 1";
 // return;
 if ($this->adapter->isValid())
{

try {
		$image_name = $_FILES['myfile']['name'];
        $random = rand(10,10000);
        $time = time() + (7 * 24 * 60 * 60);
        $file_name = $time . $random . $image_name;
 
        move_uploaded_file($_FILES['myfile']['tmp_name'], SYSTEM_PATH ."/images/categories/originals/".$file_name);
        $thumb = new Application_Model_Thumbnail(SYSTEM_PATH ."/images/categories/originals/".$file_name);
	$thumb->resize(250,250);
	$thumb->save(SYSTEM_PATH .'/images/categories/250X250/'.$file_name);
        $thumb->resize(100,100);
	$thumb->save(SYSTEM_PATH .'/images/categories/100X100/'.$file_name);
	$this->user_session->msg = $this->list_categories->updateCategory($formData, $file_name,$category_id );
$this->deleteCatImage($result->category_image);
$this->user_session->msg = "<div style='background-color: yellow; color: dark-greenp; padding: 10px'>Category Updated</div>";	 
	$this->_redirect('admin/categories/edit-cat/id/'.$category_id);	
}
catch (Zend_File_Transfer_Exception $e)
{
    throw new Exception('Bad image data: '.$e->getMessage());
}
}else{
	 if($this->list_categories->UpdateCategory($formData, $result->category_image, $category_id)){
$this->user_session->msg = "<div style='background-color: yellow; color: dark-greenp; padding: 10px'>Category Updated</div>";	 
	$this->_redirect('admin/categories/edit-cat/id/'.$category_id);	
 
		 }
	}

//$formData['bd_list_id'] = $ad_id;
//$this->view->msg = $this->bdlist->updateCategories($category_id);
//$this->_redirect('members/manage-ld/id/'.$ad_id);
}

    private function deleteCatImage($image_file){
    	unlink(SYSTEM_PATH .'/images/categories/originals/'. $image_file);
    	unlink(SYSTEM_PATH .'/images/categories/100X100/'. $image_file);
    	unlink(SYSTEM_PATH .'/images/categories/250X250/'. $image_file);
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

    /* remove category bdlist form category list start*/ 
    public function removeCatAction()
    {
    	$category_id =  $this->_request->getParam("id");
    	$this->view->category_id = $category_id;
    	$this->view->finished = false;
		
		 if ($category_id != 0){
		$result = $this->list_categories->getCategoryName($category_id);
		$results = $this->list_categories->getCategoryImage($category_id);
		    $this->view->category_name = $result;
			$this->view->category_image = $results;
			}      
	  
    	 if (!$this->_request->isPost()) {
    	      	   return;
                  }  
               	
    	$result = $this->list_categories->deleteRecord($category_id);
    		
    	$this->view->msg = "<div class='alert alert-success'>Category Removed</div>";
    	$this->view->finished = true; 
    	$this->_redirect("/admin/categories/remove-cat/id/0"); 
    }
     /* remove category form category list end*/ 

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
   
		/*$results = Application_Model_Countries::getAllCities($this->db,$user_id);
		if (count($results) > 0) {
				 $this->Paginator($results);
				} else {
				$this->view->empty_rec = true;
				}
*/
}	
    public function listMainCatAction(){
		$query_string = $this->_request->getParam("query_string");
		$results = null; 
      $query_string = trim($query_string);
	  if($query_string !=''){
		  if(is_string($query_string)){
     $results = $this->list_categories->getCategory($query_string);
   }
   }
	else{	
	  $results = $this->list_categories->getList($this->db, 1); 
	}
		 if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
		
}	
  
    //ajaxed function
    public function getListSubCat1Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        $this->category_session->parent_id = $parent_id;
		$this->results = Application_Model_PageCategories::getSubCat1($this->db, $parent_id, $size);
        echo $this->results;
    }
	 
     
	  public function getHdSubCat1Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        $this->category_session->parent_id = $parent_id;
		$this->results = Application_Model_Category::getSubCat1($this->db, $parent_id, $size);
        echo $this->results;
    }
	 
	 
	  public function getHdSubCat2Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        $this->category_session->parent_id = $parent_id;
		$this->results = Application_Model_Category::getSubCat2($this->db, $parent_id, $size);
        echo $this->results;
    }
	 
	  public function getHdSubCat3Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        $this->category_session->parent_id = $parent_id;
		$this->results = Application_Model_Category::getSubCat3($this->db, $parent_id, $size);
        echo $this->results;
    }
	 
	 
    //ajaxed function
    public function getListSubCat2Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        $this->category_session->parent_id = $parent_id;
        $this->results = Application_Model_PageCategories::getSubCat2($this->db, $parent_id, $size);
        echo $this->results;
    }

    //ajaxed function 3rd child category
    public function getListSubCat3Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        $this->category_session->parent_id = $parent_id;
         $this->results = Application_Model_PageCategories::getSubCat3($this->db, $parent_id, $size);
       echo $this->results;
    }
   
    public function getListSubCat4Action() {
        $this->ajaxed();
        $parent_id = $this->getRequest()->getParam('parent_id');
        $size = $this->getRequest()->getParam('size');
        $this->category_session->parent_id = $parent_id;
        $results = Application_Model_PageCategories::getSubCat4($this->db, $parent_id, $size);
        echo $$this->results;
    }

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

