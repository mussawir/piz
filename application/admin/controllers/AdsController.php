<?php

class Admin_AdsController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		private $page_model = null;
		private $ads_model = null;
		
	public function init(){
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
				
		ini_set("max_execution_time",(60*300));
		
		$this->page_model = new Application_Model_Pages();
		$this->ads_model = new Application_Model_PageAds();
        
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
    
	public function newAction() 
	{
        if ($this->user_session->msg != null) {
            $this->view->msg = $this->user_session->msg;
            $this->user_session->msg = null;
        }
       
        $member_page_id = $this->_request->getParam('id');
        $ad_list = $this->ads_model->getAllPageAds($member_page_id);
        
        $form = new Application_Form_PageAdsForm();
        
        if(count($ad_list)>0)
        {   
            if(isset($ad_list->ad_title1)){
        		$form->ad_title1->setValue($ad_list->ad_title1);
                $form->ad_target_url1->setValue($ad_list->ad_target_url1);
                $form->ad_type1->setValue($ad_list->ad_type1);
                $form->ad_text1->setValue(stripslashes($ad_list->ad_text1));
                $this->view->ad_image1 = $ad_list->ad_image1;
                $this->view->ad_title1 = $ad_list->ad_title1;
            }
            if(isset($ad_list->ad_title2)){
                $form->ad_title2->setValue($ad_list->ad_title2);
                $form->ad_target_url2->setValue($ad_list->ad_target_url2);
                $form->ad_type2->setValue($ad_list->ad_type2);
                $form->ad_text2->setValue(stripslashes($ad_list->ad_text2));
                $this->view->ad_image2 = $ad_list->ad_image2;
                $this->view->ad_title2 = $ad_list->ad_title2;
            }
            if(isset($ad_list->ad_title3)){
                $form->ad_title3->setValue($ad_list->ad_title3);
                $form->ad_target_url3->setValue($ad_list->ad_target_url3);
                $form->ad_type3->setValue($ad_list->ad_type3);
                $form->ad_text3->setValue(stripslashes($ad_list->ad_text3));
                $this->view->ad_image3 = $ad_list->ad_image3;
                $this->view->ad_title3 = $ad_list->ad_title3;
            }
            if(isset($ad_list->ad_title4)){
                $form->ad_title4->setValue($ad_list->ad_title4);
                $form->ad_target_url4->setValue($ad_list->ad_target_url4);
                $form->ad_type4->setValue($ad_list->ad_type4);
                $form->ad_text4->setValue(stripslashes($ad_list->ad_text4));
                $this->view->ad_image4 = $ad_list->ad_image4;
                $this->view->ad_title4 = $ad_list->ad_title4;
            }
            if(isset($ad_list->banner_title1)){
                $form->banner_title1->setValue($ad_list->banner_title1);
                $form->banner_url1->setValue($ad_list->banner_url1);
                $this->view->banner_img1 = $ad_list->banner_img1;
                $this->view->banner_title1 = $ad_list->banner_title1;
            }
            if(isset($ad_list->banner_title2)){
                $form->banner_title2->setValue($ad_list->banner_title2);
                $form->banner_url2->setValue($ad_list->banner_url2);
                $this->view->banner_img2 = $ad_list->banner_img2;
                $this->view->banner_title2 = $ad_list->banner_title2;
            }
        }
        
        $this->view->form = $form;
		
        if ($this->_request->isPost()){
            $formData = $this->_request->getPost();
            
            $file_name = '';
            $random = rand(9,999999);
            try{
                // ad block image 1
                if(!empty($_FILES['ad_image1']['name'])){
                    $ad_image1 = $_FILES['ad_image1']['name'];
                    $file_name = $random.'_'.$ad_image1;
                    
                    if(isset($ad_list->ad_image1)){
                        unlink(SYSTEM_PATH."images/user/ads/".$ad_list->ad_image1);
                        unlink(SYSTEM_PATH."images/user/ads/500X500/".$ad_list->ad_image1);
                    }
                    
                    $formData["ad_image1"] = $file_name;
                    move_uploaded_file($_FILES["ad_image1"]['tmp_name'], SYSTEM_PATH."images/user/ads/".$file_name);
                    
                    $thumb = new Application_Model_Thumbnail(SYSTEM_PATH . "images/user/ads/" . $file_name);
                    $thumb->resize(500, 500);
                    $thumb->save(SYSTEM_PATH . "images/user/ads/500X500/" . $file_name);
                    
                } else if(!isset($_FILES['ad_image1']) && isset($ad_list->ad_image1)){
                    $formData["ad_image1"] = $ad_list->ad_image1;
                }
                
                // ad block image 2
                if(!empty($_FILES['ad_image2']['name'])){
                    $ad_image2 = $_FILES['ad_image2']['name'];
                    $file_name = $random.'_'.$ad_image2;
                    
                    if(isset($ad_list->ad_image2)){
                        unlink(SYSTEM_PATH."images/user/ads/".$ad_list->ad_image2);
                        unlink(SYSTEM_PATH."images/user/ads/500X500/".$ad_list->ad_image2);
                    }
                    
                    $formData["ad_image2"] = $file_name;                    
                    move_uploaded_file($_FILES["ad_image2"]['tmp_name'], SYSTEM_PATH."images/user/ads/".$file_name);
                    
                    $thumb = new Application_Model_Thumbnail(SYSTEM_PATH . "images/user/ads/" . $file_name);
                    $thumb->resize(500, 500);
                    $thumb->save(SYSTEM_PATH . "images/user/ads/500X500/" . $file_name);
                    
                } else if(!isset($_FILES['ad_image2']) && isset($ad_list->ad_image2)){
                    $formData["ad_image2"] = $ad_list->ad_image2;
                }
                
                // ad block image 3
                if(!empty($_FILES['ad_image3']['name'])){
                    $ad_image3 = $_FILES['ad_image3']['name'];
                    $file_name = $random.'_'.$ad_image3;
                    
                    if(isset($ad_list->ad_image3)){
                        unlink(SYSTEM_PATH."images/user/ads/".$ad_list->ad_image3);
                        unlink(SYSTEM_PATH."images/user/ads/500X500/".$ad_list->ad_image3);
                    }
                    
                    $formData["ad_image3"] = $file_name;
                    move_uploaded_file($_FILES["ad_image3"]['tmp_name'], SYSTEM_PATH."images/user/ads/".$file_name);
                    
                    $thumb = new Application_Model_Thumbnail(SYSTEM_PATH . "images/user/ads/" . $file_name);
                    $thumb->resize(500, 500);
                    $thumb->save(SYSTEM_PATH . "images/user/ads/500X500/" . $file_name);
                    
                } else if(!isset($_FILES['ad_image3']) && isset($ad_list->ad_image3)){
                    $formData["ad_image3"] = $ad_list->ad_image3;
                }
                
                // ad block image 4
                if(!empty($_FILES['ad_image4']['name'])){
                    $ad_image4 = $_FILES['ad_image4']['name'];
                    $file_name = $random.'_'.$ad_image4;
                    
                    if(isset($ad_list->ad_image4)){
                        unlink(SYSTEM_PATH."images/user/ads/".$ad_list->ad_image4);
                        unlink(SYSTEM_PATH."images/user/ads/500X500/".$ad_list->ad_image4);
                    }
                    
                    $formData["ad_image4"] = $file_name;
                    move_uploaded_file($_FILES["ad_image4"]['tmp_name'], SYSTEM_PATH."images/user/ads/".$file_name);
                    
                    $thumb = new Application_Model_Thumbnail(SYSTEM_PATH . "images/user/ads/" . $file_name);
                    $thumb->resize(500, 500);
                    $thumb->save(SYSTEM_PATH . "images/user/ads/500X500/" . $file_name);
                    
                } else  if(isset($_FILES['ad_image4']) && isset($ad_list->ad_image4)){
                    $formData["ad_image4"] = $ad_list->ad_image4;
                }
                
                // ad banner image 1
                if(!empty($_FILES['banner_img1']['name'])){
                    $banner_img1 = $_FILES['banner_img1']['name'];
                    $file_name = $random.'_'.$banner_img1;
                    
                    if(isset($ad_list->banner_img1)){
                        unlink(SYSTEM_PATH."images/user/ads/".$ad_list->banner_img1);
                        unlink(SYSTEM_PATH."images/user/ads/500X500/".$ad_list->banner_img1);
                    }
                    
                    $formData["banner_img1"] = $file_name;
                    move_uploaded_file($_FILES["banner_img1"]['tmp_name'], SYSTEM_PATH."images/user/ads/".$file_name);
                    
                    $thumb = new Application_Model_Thumbnail(SYSTEM_PATH . "images/user/ads/" . $file_name);
                    $thumb->resize(500, 500);
                    $thumb->save(SYSTEM_PATH . "images/user/ads/500X500/" . $file_name);
                    
                } else if(isset($_FILES['banner_img1']) && isset($ad_list->banner_img1)){
                    $formData["banner_img1"] = $ad_list->banner_img1;
                }
                
                // ad banner image 2
                if(!empty($_FILES['banner_img2']['name'])){
                    $banner_img2 = $_FILES['banner_img2']['name'];
                    $file_name = $random.'_'.$banner_img2;
                    
                    if(isset($ad_list->banner_img2)){
                        unlink(SYSTEM_PATH."images/user/ads/".$ad_list->banner_img2);
                        unlink(SYSTEM_PATH."images/user/ads/500X500/".$ad_list->banner_img2);
                    }
                    
                    $formData["banner_img2"] = $file_name;
                    move_uploaded_file($_FILES["banner_img2"]['tmp_name'], SYSTEM_PATH."images/user/ads/".$file_name);
                    
                    $thumb = new Application_Model_Thumbnail(SYSTEM_PATH . "images/user/ads/" . $file_name);
                    $thumb->resize(500, 500);
                    $thumb->save(SYSTEM_PATH . "images/user/ads/500X500/" . $file_name);
                    
                } else if(isset($_FILES['banner_img2']) && isset($ad_list->banner_img2)){
                    $formData["banner_img2"] = $ad_list->banner_img2;
                }
            } catch (Zend_File_Transfer_Exception $e)
    		{
    			throw new Exception('Error: '.$e->getMessage());
    		}
            
            $formData['page_id'] = $member_page_id;
            unset($formData['MAX_FILE_SIZE']);
            
            // if record found then update else insert
            if(count($ad_list)>0)
            {
                $result = $this->ads_model->updateAd($formData, $ad_list['page_ad_id']);
                if($result){
                    $this->user_session->msg =  "<div class='alert alert-success'>Ad updated successfully!</div>";
                } else {
                    $this->user_session->msg =  "<div class='alert alert-danger'>Some error occur. Please try again</div>";   
                }                
            } else {
                $result = $this->ads_model->add($formData);
                if($result){
                    $this->user_session->msg =  "<div class='alert alert-success'>Ad saved successfully!</div>";
                } else {
                    $this->user_session->msg =  "<div class='alert alert-danger'>Some error occur. Please try again</div>";   
                }
            }
            
            $this->_redirect('/admin/ads/new/id/'.$member_page_id);
        } // post condition end
        
	} // new function end
	
	public function indexAction()
    { 
	   
    } // index function end
	
	// for edit post
   public function editAction()
   {
	   if ($this->user_session->msg != null) {
            $this->view->msg = $this->user_session->msg;
            $this->user_session->msg = null;
        }
       
    	$id = $this->_request->getParam('id');
    	
		//$this->_redirect("/admin/page/edit/id/".$formData['page_id']);
	}
		
	// delete page
	public function deleteAction()
	{		
        $id = $this->_request->getParam('id');
		
		$this->_redirect('/admin/page/index');
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