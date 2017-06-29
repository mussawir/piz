<?php
class Admin_GalleryController extends Zend_Controller_Action
{

    protected $user_session = null;
    protected $db;
    protected $baseurl = '';
	protected $photos = null; 

    public function init(){
          Zend_Layout::startMvc(
                        array('layoutPath' => APPLICATION_PATH . '/admin/layouts', 'layout' => 'layout')
        );
        $this->db = Zend_Db_Table::getDefaultAdapter();
	    $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
        $this->user_session = new Zend_Session_Namespace("user_session"); // default namespace
		$this->photos = new Application_Model_Photos();
		
		
		if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");
		}
		
		$auth = Zend_Auth::getInstance();
        //if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
			$this->_redirect('/admin/index/login');;
                }
Application_Model_ViewSettings::common($this->view, $this->user_session);
	$this->view->role = $this->user_session->role_id;
	$this->view->name = $this->user_session->user_name;
    $this->view->logged_user_id = $this->user_session->user_id;
		
}
    // this is default output function
    public function indexAction() {

    	$results = $this->photos->getAllPhotos();
        $this->view->data = $results;

		  /*code for delete bulk data start*/
	
    if(isset($_POST['submit'])) {
	$id_array = $_POST['bulkdata']; // return array
	$id_count = count($_POST['bulkdata']); // count array
	//var_dump($id_array);
	//return;
	for($i=0; $i < $id_count; $i++) {
		$id = $id_array[$i];

		$result = $this->photos->getPhotoByID($id);
		
		if(isset($result->photo_name) && strlen($result->photo_name) > 0 ){
			unlink(SYSTEM_PATH.'images/photo_gallery/500X500/'.$result->photo_name);
		unlink(SYSTEM_PATH.'images/photo_gallery/'.$result->photo_name);
		unlink(SYSTEM_PATH.'images/photo_gallery/200X200/'.$result->photo_name);
		
		$this->photos->removePhoto($id);
	   }
	 }//for loop end
	 $this->_redirect('/admin/gallery');
	}// if condition end
	
	/*code for delete bulk data end*/
   }

  //new for add new photo
  
  public function newPhotoAction() 
  {
    $form = new Application_Form_GalleryForm();
    $this->view->form = $form;
    if($this->user_session->msg!=null)
    {
      $this->view->msg = $this->user_session->msg;
      $this->user_session->msg = null;
    }
    
    if (!$this->_request->isPost())return;
    $formData = $this->_request->getPost();
    if (!$form->isValid($formData)) return;
      
    //For Images
    $file_name = NULL;
  
     try {
      $photo_name = $_FILES['photo_name']['name'];
	  	if(isset($photo_name) && strlen($photo_name) > 0 ) 
	{
            $random = rand(9,999999);
            $file_name = $random . $photo_name;
            $formData["photo_name"] = $file_name;

            move_uploaded_file($_FILES["photo_name"]['tmp_name'], SYSTEM_PATH."images/photo_gallery/".$file_name);
            $thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/photo_gallery/".$file_name);
            $thumb->resize(500,500);
            $thumb->save(SYSTEM_PATH."images/photo_gallery/500X500/".$file_name);
            $thumb->resize(200,200);
            $thumb->save(SYSTEM_PATH."images/photo_gallery/200X200/".$file_name);
     }
	 else
            {
	$this->view->msg =  "<div class='alert alert-danger'>Please Upload a Image.</div>";
		 return;
            }
	 }
        
    catch (Zend_File_Transfer_Exception $e)
    {
      throw new Exception('Bad data: '.$e->getMessage());
    }

    $result = $this->photos->addPhoto($formData);
    $this->view->msg = $result;
    //clear all form fields 

  $form->reset();
  }

  //new for bulk upload
  
  public function bulkUploadPhotosAction() 
  {
    $form = new Application_Form_BulkUploadGalleryForm();
    $this->view->form = $form;
    if($this->user_session->msg!=null)
    {
      $this->view->msg = $this->user_session->msg;
      $this->user_session->msg = null;
    }
    
    if (!$this->_request->isPost())return;
    $formData = $this->_request->getPost();
    if (!$form->isValid($formData)) return;
        //var_dump($formData);
        //return;
    //For Images

$file_name = NULL;

     try {
      $photo_name = $_FILES['photo_name']['name'];
        //var_dump($photo_name);
        // return;
		 	if(isset($photo_name) && strlen($photo_name) > 0 ) 
	{
      for($i=0; $i < count($photo_name); $i++){

      $random = rand(9,99999);
      $file_name = $random . $photo_name[$i];

           //var_dump($file_name);
           
      $formData["photo_name"] = $file_name;
   
      move_uploaded_file($_FILES['photo_name']['tmp_name'][$i], SYSTEM_PATH."/images/photo_gallery/".$file_name);
      $thumb = new Application_Model_Thumbnail(SYSTEM_PATH."/images/photo_gallery/".$file_name);
      $thumb->resize(500,500);
      $thumb->save(SYSTEM_PATH.'/images/photo_gallery/500X500/'.$file_name);
      $thumb->resize(200,200);
      $thumb->save(SYSTEM_PATH.'/images/photo_gallery/200X200/'.$file_name);
      
      $result = $this->photos->addPhoto($formData);
    }
    //return;
	 }
	 	 else
            {
	$this->view->msg =  "<div class='alert alert-danger'>Please Upload a Images.</div>";
		 return;
            }
	 }
    catch (Zend_File_Transfer_Exception $e)
    {
      throw new Exception('Bad data: '.$e->getMessage());
    }

    
    $this->view->msg = $result;
    //clear all form fields 

  $form->reset();
 }

	// edit photo galleries
	public function editPhotoAction(){

	$id = $this->_request->getParam('id');
    if(!isset($id)) $this->_redirect('admin/gallery/index');
    $form = new Application_Form_GalleryForm();
   // get photo data from photos table
    $result = $this->photos->getPhotoByID($id);
    $this->view->id = $result->photo_id;
    $form->photo_name->setValue($result->photo_name);
    $this->view->photo = $result->photo_name;
    $form->link->setValue($result->link);
    $form->caption->setValue($result->caption);
    $form->category->setValue($result->pg_cat_id);
    $form->description->setValue($result->description);
	$form->submit->setLabel("Update");
    $this->view->form = $form;
             if (!$this->_request->isPost()) return;
              $formData = $this->_request->getPost();
             if (!$form->isValid($formData)) return;

      //For Image upload
    $file_name = NULL;
    $image_name= $_FILES["photo_name"]["name"];

    if(isset($image_name) && strlen($image_name) > 0 ) {

    try {
               if(isset($result->photo_name)){

$image_file = SYSTEM_PATH."/images/photo_gallery/500X500/".$result->photo_name;

if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/photo_gallery/".$result->photo_name);
     }

if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/photo_gallery/200X200/".$result->photo_name);
     }
if (file_exists($image_file)) {
           unlink(SYSTEM_PATH."/images/photo_gallery/500X500/".$result->photo_name);
     }
 }

            $photo_name = $_FILES['photo_name']['name'];
            $random = rand(9,999999);
            $file_name = $random . $photo_name;
            $formData["photo_name"] = $file_name;

            move_uploaded_file($_FILES["photo_name"]['tmp_name'], SYSTEM_PATH."images/photo_gallery/".$file_name);
            $thumb = new Application_Model_Thumbnail(SYSTEM_PATH."images/photo_gallery/".$file_name);

            $thumb->resize(500,500);
            $thumb->save(SYSTEM_PATH."images/photo_gallery/500X500/".$file_name);

            $thumb->resize(200,200);
            $thumb->save(SYSTEM_PATH."images/photo_gallery/200X200/".$file_name);


        }

    catch (Zend_File_Transfer_Exception $e)
        {
            throw new Exception('Bad data: '.$e->getMessage());
        }
}else{

$formData['photo_name']=  $result->photo_name;
}

    $formData['photo_id']= $id;
    $result = $this->photos->editPhoto($formData);
    $this->view->msg = $result;
    $this->_redirect("/admin/gallery/edit-photo/id/".$id);
	}

	// delete photo 
	public function deletePhotoAction()
	{
	 $this->_helper->viewRenderer->setNoRender();
     $this->_helper->layout()->disableLayout();

		$id = $this->_request->getParam('id');
		$result = $this->photos->getPhotoByID($id);
		//var_dump($result);
		//return;
		unlink(SYSTEM_PATH.'images/photo_gallery/500X500/'.$result->photo_name);
		unlink(SYSTEM_PATH.'images/photo_gallery/'.$result->photo_name);
		unlink(SYSTEM_PATH.'images/photo_gallery/200X200/'.$result->photo_name);

		$this->photos->removePhoto($id);
		$this->_redirect('/admin/gallery/index');
	}

	// Paginator action
  public function Paginator($results, $records) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage($records);
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