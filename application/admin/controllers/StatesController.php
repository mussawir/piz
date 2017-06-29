<?php
/**
 Author: Narjis Fatima
 Date: August 2014
 Karachi
 */
class Admin_StatesController extends Zend_Controller_Action
{
		var $user_session = null;
		private $states = null;
		var $states_session = null;
		private $db = null;
       	private $authAdapter = null;
        protected $baseurl = '';
		

	public function init(){
	Zend_Layout::startMvc(
	array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
	$this->db = Zend_Db_Table::getDefaultAdapter();
    $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
    $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
	
	$this->user_session = new Zend_Session_Namespace("user_session");
 	$this->states_session = new Zend_Session_Namespace("states_session");
 	
	$this->states = new Application_Model_States();			
//authorization for this controller
			ini_set("max_execution_time",0);
               $this->states = new Application_Model_States();
               $auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
			$this->_redirect($this->baseurl.'/admin/index/login');;
                }
				$role = array(
					'1' => 'Admin',
					'2' => 'Editor',
					'3' => 'Author',
					'4' => 'Super Super Admin',
					);
		if(isset($this->user_session->role_id)){
		$this->view->user = array(
					'user_name'	=>$this->user_session->firstname,
					'user_id' => $this->user_session->user_id,
					'email' => $this->user_session->email,
					'role_id' => $this->user_session->role_id,
					'role_name'	=> $role[$this->user_session->role_id],
					);	
				}
}

				
public function bulkUploadAction(){
	$file_adapter = new Zend_File_Transfer_Adapter_Http();
	
	$form = new Application_Form_BulkStatesForm();
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
	
	if(isset($_FILES['bulk_states'])){
		$excel_file = $_FILES['bulk_states']['name'];
	}
//	echo $file_name;die;
	if(!$excel_file == null){
	try{
		move_uploaded_file($_FILES['bulk_states']['tmp_name'],SYSTEM_PATH.'/uploads/'.$excel_file);
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
						'state_name' => $input_data[0],
						'country_id' => $formData['country']);
			$this->states->addState($data);
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
	
	
public function newAction(){
	$form = new Application_Form_StatesForm();
		//show message if it is set true 
	if (isset($this->user_session->msg)){
	$this->view->msg = $this->user_session->msg;
	unset($this->user_session->msg);
	}
	
	if(isset($this->states_session->country_id)){
	$form->country->setValue($this->states_session->country_id);
	unset($this->states_session->country_id);
	}
	
		$this->view->form =  $form;
		if($this->states_session->add_more == true){
			$this->view->msg = $this->states_session->msg;
			$this->view->add_more = $this->states_session->add_more;
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
	
	if(trim($formData['country']) == 0) {
			$this->view->msg = "<div class='alert alret-danger'>Please Select Country</div>";
		return;
	    }
	if($this->states->checkState($formData)){
		$this->view->msg = "<div class='alert alert-danger'>The ".$formData['state_name'] . " State already Exists.</div>";
		return;
		}
		
		$this->states_session->country_id = $formData['country'];
		$this->view->msg = $this->states->addState($formData);
		$this->user_session->msg = $this->view->msg;
		$this->_redirect('/admin/states/new');
		
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
	//end of $file_name == null
	
	

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
 
public function editAction(){
    
	$state_id = $this->_request->getParam('state_id');
	$form = new Application_Form_StatesForm();
	
	
	$this->view->state_id = $state_id;
	$result = $this->states->getState($state_id);
	
	$form->state_name->setValue($result->state_name);
	$this->view->form = $form;
    if(!$this->_request->isPost()){
		$this->view->form = $form;
		return;
	}
	
	$formData = $this->_request->getPost();	
	$formData['state_id'] = $state_id;
	
	$this->view->msg = $this->states->editState($formData);
	}
 
 public function statesCountryAction(){
     $this->ajaxed();
     $country_id = $this->_request->getParam('country_id');
  $result = $this->states->getStateByCountryid($country_id);
  $output = "";
		
		$output = '<div class="row-fluid">
                    <div class="">
						<div class="box gradient">
                                <div class="title">
                                    <h4>
                                        <span>List of States </span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
								$output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>State Name</th>
												<th>Edit</th>
												<th>Block</th>
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
		if (count($result) > 0){
                                /* $output .= '<?php if (count($this->paginator)){ ?>'; */
                                     foreach ($result as $key=>$val){
									 
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['state_name'].'</td>
												<td><a class="btn btn-sm btn-warning float-right" href="'.$this->baseurl.'/admin/states/edit/state_id/'.$val["state_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$this->baseurl.'/admin/states/confirm-block/state_id/'.$val["state_id"].'">Block</a></td>
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                                <td>' . $val['state_name'].'</td>
                                                <td><a class="btn btn-sm btn-warning float-right" href="'.$this->baseurl.'/admin/states/edit/state_id/'.$val["state_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$this->baseurl.'/admin/states/confirm-block/state_id/'.$val["state_id"].'">Block</a></td>
                                              
                                            </tr>';
											 }
											}//end foreach
											/* $output .= '<tr><td colspan="9">';
														$output .= 	'<?php echo $this->paginationControl($this->paginator, "Sliding", "my_pagination_control.phtml");?>'; 
														$output .=	'</td>
															</tr><?php }else{ ?>
											
											
															<tr><td colspan="7"><h4>There are no cities created! Please create one</h4></td></tr>
															<?php } ?>'; */
              }
			  else{
			$output .= "<tr><td colspan='2'>No States</td></tr>"	;
				}
			$output .=" </tbody>
                                        
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		echo $output;
 }
 
 
 public function listAction(){ 
 
    $query_string = $this->_request->getParam("query_string");
		$results = null; 
		$query_string = trim($query_string);  
     $this->view->countries = Application_Model_Countries::getCountries($this->db,1);
	  
	  if($query_string !=''){
        if(is_string($query_string)){
        $results = $this->states->getSearchStates($query_string);
		
          }
		  
		  /*if(is_string($query_string)){
           $results = $this->countries->getCountrycode($query_string);
              }*/

		  
		   
           }
		  
		else{
	
  $results = $this->states->getList();
}


		
		 //$results = $this->countries->getList($this->db, 1);
//	print_r($results);
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

public function blockAction(){
$this->noRender();
	$id = $this->_request->getParam('id');
	$where = $this->db->quoteInto('state_id = ?', $id);
	$data = array('is_blocked' => 1);
	$this->db->update('states',$data, $where);
	$this->user_session->msg = "State is blocked!";
	$this->_redirect("/admin/states/list");						
	}

	public function unblockAction(){
$this->noRender();
	$id = $this->_request->getParam('id');
	$where = $this->db->quoteInto('state_id = ?', $id);
	$data = array('is_blocked' => 0);
	$this->db->update('states',$data, $where);
	$this->user_session->msg = "State is unblocked!";
	$this->_redirect("/admin/states/list");						
	
	}	

		private function noRender(){
// Because of following code we don't need a phtml file 
		  $this->_helper->viewRenderer->setNoRender();
		  $this->_helper->layout()->disableLayout();

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