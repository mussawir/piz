<?php

class Admin_ResetDefaultController extends Zend_Controller_Action
{
	    protected $user_session = null;
        private $db = null;
        private $baseurl = null;
        private $authAdapter = null;
		
	public function init()
    {
		Zend_Layout::startMvc(
		array('layoutPath'=>  APPLICATION_PATH . '/admin/layouts',  'layout' => 'layout'));
		$this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
		$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
		$this->user_session = new Zend_Session_Namespace("user_session");
				
		ini_set("max_execution_time",(60*300));
				
		if(!isset($this->user_session->user_id)){
			$this->_redirect("/admin/login/login");			
		}
		$auth = Zend_Auth::getInstance();
		//if not loggedin redirect to login page
		if (!$auth->hasIdentity()){
		$this->_redirect("/admin/login/login");
        }
		Application_Model_ViewSettings::common($this->view, $this->user_session);
		
	}
    
    public function indexAction()
    {
        $is_ok = $this->_request->getParam('x');
        if(isset($is_ok))
        {
            $folderName = $this->baseurl."/images/user";
                        
            $it = new RecursiveDirectoryIterator($folderName, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it,
                         RecursiveIteratorIterator::CHILD_FIRST);
                         
            foreach($files as $file) {
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($folderName); // remove folder            

            // create directory
            if (!file_exists($folderName)) {
                
                //mkdir($folderName,0775);
            }
            
            // unzip file to newely created directory
            $user_zip_path = $this->baseurl."/images/user_backup.zip";
            $zip = new ZipArchive;
            $res = $zip->open($user_zip_path);
            
            /*if ($res === TRUE) {
                $zip->extractTo($folderName);
                $zip->close();
                  
                $this->view->msg = "<div class='alert alert-success'>Front-end set to default.</div>";
            } else {
                $this->view->msg = "<div class='alert alert-danger'>Some error occurred. Please try again.</div>";
            }*/
        }
    }
    
} // class end