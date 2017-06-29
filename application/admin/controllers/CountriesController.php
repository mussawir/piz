<?php
/**
 Author: Musavir Ifitkahr:
 Date: SEPTEMBER 2014
 kuala lumpur Malaysia
 This controller is specifically created for managing countries. It is a multilingual 
 */
class Admin_CountriesController extends Zend_Controller_Action
{
		var $user_session = null;
        private $country_session = null;
        private $db = null;
        private $authAdapter = null;
        private $countries = null;
        protected $baseurl = '';
		private $results = null;
        
	public function init(){
	Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function

                $this->user_session = new Zend_Session_Namespace("user_session");
                $this->country_session = new Zend_Session_Namespace("country_session");
				
//authorization for this controller
			ini_set("max_execution_time",0);
            
               $this->countries = new Application_Model_Countries();
               $auth = Zend_Auth::getInstance();
               
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
			$this->_redirect('/admin/login/login');
        }
        
        if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");			
		}
	
	   Application_Model_ViewSettings::common($this->view, $this->user_session);
    	$this->view->role = $this->user_session->role_id;
    	$this->view->name = $this->user_session->user_name;
        $this->view->logged_user_id = $this->user_session->user_id;
					
    } // init function end
				
    public function indexAction(){
	
	}

/* Country related functions */

    public function newAction(){
	
	$form = new Application_Form_CountryForm();
	$this->view->form = $form;

	if(!$this->_request->isPost()){
		$this->view->form = $form;
		return;
	}
	
	$formData = $this->_request->getPost();
	if (!$form->isValid($formData)) {
	$this->view->form = $form;
	return;
	}	

if($this->countries->checkCountry($formData['country_name'])){
		$this->view->msg = "<div style='color:red'>Country Already Exists</div>";
		return;
		}
		
if($this->countries->checkCountryCode($formData['country_code'])){
		$this->view->msg = "<div style='color:red'>Country Code Already Exists</div>";
		return;
		}
		
		$formData['user_id'] = $this->user_session->user_id;
		$this->view->msg = $this->countries->add($formData);
		
        $form->reset();
        
	} // new function end
	

		public function listAction(){
		$query_string = $this->_request->getParam("query_string");
		$results = null; 
		$query_string = trim($query_string);
       if($query_string !=''){
        if(is_string($query_string)){
        $results = $this->countries->getSearchCountry($query_string);
		
          }
		  
		  /*if(is_string($query_string)){
           $results = $this->countries->getCountrycode($query_string);
              }*/

		  
		   
           }
		
		else{
  $results = $this->countries->getList($this->db, 1);
}
		
		 //$results = $this->countries->getList($this->db, 1);
//	print_r($results);
        if (count($results) > 0) {
		 $this->Paginator($results);
        } else {
        $this->view->empty_rec = true;
		}
 
}

public function editAction(){
	$country_id = $this->_request->getParam('country_id');
	$form = new Application_Form_CountryForm();
	
	
	
	 $result = $this->countries->editCountry($country_id);
	
	 $form->country_name->setValue($result->country_name);
    $form->country_code->setValue($result->country_code);
	$this->view->form = $form;
    if(!$this->_request->isPost()){
		$this->view->form = $form;
		return;
	}
	
	$formData = $this->_request->getPost();
	if (!$form->isValid($formData)) {
	$this->view->form = $form;
	return;
	}	
	$formData['country_id'] = $country_id;
	
	$this->view->msg = $this->countries->edit($formData);
}
/* Country related functions end here */



 
  /* New code here */
    //ajaxed function
    public function getStateAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $this->country_session->country_id = $country_id;
        $this->results = Application_Model_Countries::getStateAjaxed($this->db, $country_id);
        echo $this->results;
    }

/*<?php echo $this->paginationControl($this->paginator, "Sliding", "my_pagination_control.phtml"); ?>*/

    public function getCountriesCitiesAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $this->country_session->country_id = $country_id;
        $this->results = Application_Model_Countries::getCountriesCities($this->db, $country_id, $this->baseurl);
        if (count($this->results) > 0) {
				 $this->Paginator($this->results);
				} else {
				$this->view->empty_rec = true;
				}
				$output = "";
		
		$output = '<div class="row-fluid">
                    <div class="">
						<div class="box gradient">
                                <div class="title">
                                    <h4>
                                        <span>List of Cities </span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
								$output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Cities Name</th>
												<th>Edit</th>
												<th>Block</th>
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
		if (count($this->results) > 0){
                                /* $output .= '<?php if (count($this->paginator)){ ?>'; */
                                     foreach ($this->results as $key=>$val){
									 
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['city_name'].'</td>
												<td><a class="btn btn-sm btn-warning float-right" href="'.$this->baseurl.'/admin/cities/edit/city_id/'.$val["city_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$this->baseurl.'/admin/cities/confirm-block/city_id/'.$val["city_id"].'">Block</a></td>
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                                <td>' . $val['city_name'].'</td>
                                                <td><a class="btn btn-sm btn-warning float-right" href="'.$this->baseurl.'/admin/cities/edit/city_id/'.$val["city_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$this->baseurl.'/admin/cities/confirm-block/city_id/'.$val["city_id"].'">Block</a></td>
                                              
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
			$output .= "<tr><td colspan='2'>No Cities</td></tr>"	;
				}
			$output .=" </tbody>
                                        
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		echo $output;
		// echo $this->results;
    }
      public function getCountriesPostcodesAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $this->country_session->country_id = $country_id;
        $this->results = Application_Model_Countries::getCountriesPostcodes($this->db, $country_id);
        echo $this->results;
    }



public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }


	public function getCountriesAction()
	{
		$this->ajaxed();
		$this->results = Application_Model_Countries::getCountries($this->db,0);
		echo $this->results;
	}
	

    public function getCitiesAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $state_id = $this->getRequest()->getParam('state_id');
        $this->country_session->country_id = $country_id;
        $this->results = Application_Model_Countries::getCities($this->db, $country_id,$state_id);
        echo $this->results;
    }

	public function getPostcodesAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $state_id = $this->getRequest()->getParam('state_id');
		$city_id = $this->getRequest()->getParam('city_id');

        $cities = new Application_Model_Cities();
        $city_info = $cities->getCityById($city_id);

	    $this->results = Application_Model_Countries::getPostcodesByAreaName($this->db, $country_id,$state_id,$city_info['city_name']);
        echo $this->results;
    }

	
    public function getStateCitiesAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $state_id = $this->getRequest()->getParam('state_id');
        $this->results = Application_Model_Countries::getStateCities($this->db, $country_id,$state_id, $this->baseurl);
        echo $this->results;
    }


	
    public function getCityNameAction() {
        $this->ajaxed();
        $country_id = $this->getRequest()->getParam('country_id');
        $state_id = $this->getRequest()->getParam('state_id');
        $city_id = $this->getRequest()->getParam('city_id');
        $this->country_session->country_id = $country_id;
        $this->results = Application_Model_Countries::getCityName($this->db, $country_id,$state_id,$city_id);
       echo($this->results['city_name']);
    }

	public function getAllCitiesAction(){
	$this->ajaxed();
	$results = Application_Model_Countries::getAllCities($this->db,$this->baseurl);
	echo $results;
	}

 
 public function getAllStatesAction(){
	$this->ajaxed();
	$user_id  = $this->user_session->user_id;
 	$results = Application_Model_Countries::getAllStates($this->db,$user_id);
	echo $results;
	}
 
 
 
 public function getCountryCitiesAction(){
	$this->ajaxed();
	$country_id = $this->getRequest()->getParam('country_id');
    $results = Application_Model_Countries::getCountriesCities($this->db,$country_id);
	echo $results;
	}
 
 public function statesByCountryAction(){
	$this->ajaxed();
	$country_id = $this->getRequest()->getParam('country_id');
    		 $user_id  = $this->user_session->user_id;
   
		$results = Application_Model_Countries::getCountryStates($this->db,$country_id,$user_id);
	
	echo $results;
	}
 

   
//this function is used for every function that recieves a ajax call
    public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        if (!$this->_request->isXmlHttpRequest()){
		$this->_redirect('index');	
			return; // if not a ajax request leave function
		}
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