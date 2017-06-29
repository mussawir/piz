<?php

class Admin_GdController extends Zend_Controller_Action
{
    protected $user_session = null;
    private $db = null;
    private $baseurl = null;
    private $authAdapter = null;

    public function init()
    {
        Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . '/admin/layouts',
                'layout' => 'layout'));
                
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
        $this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl(); //actual base url function
        $this->user_session = new Zend_Session_Namespace("user_session");

        ini_set("max_execution_time", (60 * 300));


        if (!isset($this->user_session->user_id))
        {
            $this->_redirect("/admin/login/admin-login");
        }
        
        //if not loggedin redirect to login page
        $auth = Zend_Auth::getInstance();        
        if (!$auth->hasIdentity())
        {
            $this->_redirect('/admin/login/admin-login');
        }
    }
    
    public function indexAction()
    {
        ########## Google Settings.. Client ID, Client Secret #############
        $google_client_id               = '787369762880-65kvt8cblmlm653k5gcks9uipmn401qo.apps.googleusercontent.com';
        $google_client_secret           = 'Ya__r8ROl0oqKO5hb3JpRJlF';
        $google_redirect_url            = "http://aliinfotech.com/admin/gd/index";
        $page_url_prefix                = 'http://aliinfotech.com';
        
        ########## Google analytics Settings.. #############
        $google_analytics_profile_id    = 'ga:106789249'; //Analytics site Profile ID
        $google_analytics_dimensions    = 'ga:fullReferrer,ga:sourceMedium,ga:socialNetwork'; //no change needed (optional)
        $google_analytics_metrics       = 'ga:organicSearches'; //no change needed (optional)
        $google_analytics_sort_by       = '-ga:organicSearches'; //no change needed (optional)
        //$google_analytics_max_results   = '20'; //no change needed (optional)
        
        require_once (APPLICATION_PATH . '/../library/GoogleClientApi/Google_Client.php');
        require_once (APPLICATION_PATH . '/../library/GoogleClientApi/contrib/Google_AnalyticsService.php');
        
        // Initialise the Google Client object
        $gClient = new Google_Client();
        $gClient->setApplicationName('Colinkr');
        $gClient->setClientId($google_client_id);
        $gClient->setClientSecret($google_client_secret);
        $gClient->setRedirectUri($google_redirect_url);
        $gClient->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
        $gClient->setUseObjects(true);
        
        $val = $this->_request->getParam("logout");
        if ($val == "1") {
            unset($_SESSION['token']);
        }
        
        //authenticate user
        if (isset($_GET['code'])) {    
                $gClient->authenticate();
                $token = $gClient->getAccessToken();
                $_SESSION["token"] = $token;
                header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
        }
        
        if (!$gClient->getAccessToken() && !isset($_SESSION['token'])) {
            
            $authUrl = $gClient->createAuthUrl();
            $this->view->authUrl = $authUrl;
        }
        
        //check for session variable
        if (isset($_SESSION["token"])) 
        {            
            //set start date to previous month
            $start_date = date("Y-m-d", strtotime("-1 month") );
           
            //end date as today
            $end_date = date("Y-m-d");
           
            try{
                //set access token
                $gClient->setAccessToken($_SESSION["token"]);
               
                //create analytics services object
                $service = new Google_AnalyticsService($gClient);
               
                //analytics parameters (check configuration file)
                $params = array('dimensions' => $google_analytics_dimensions,'sort' => $google_analytics_sort_by);//'filters' => 'ga:medium==organic','max-results' => $google_analytics_max_results);
               
                //get results from google analytics
                $by_search = $service->data_ga->get($google_analytics_profile_id,$start_date,$end_date, $google_analytics_metrics, $params);
            
                $google_analytics_dimensions    = 'ga:userType,ga:sessionCount,ga:browser,ga:country,ga:region';
                $google_analytics_metrics       = 'ga:users';
                $google_analytics_sort_by       = '-ga:users';
                $params = array('dimensions' => $google_analytics_dimensions,'sort' => $google_analytics_sort_by);//,'max-results' => $google_analytics_max_results);
                $by_user_type = $service->data_ga->get($google_analytics_profile_id, $start_date, $end_date, $google_analytics_metrics, $params);
             
                $google_analytics_dimensions    = 'ga:socialInteractionNetwork,ga:socialInteractionAction,ga:socialInteractionTarget';
                $google_analytics_metrics       = 'ga:socialInteractions';
                $google_analytics_sort_by       = '-ga:socialInteractions';
                $params = array('dimensions' => $google_analytics_dimensions,'sort' => $google_analytics_sort_by);//,'max-results' => $google_analytics_max_results);
                $by_social_interactions = $service->data_ga->get($google_analytics_profile_id, $start_date, $end_date, $google_analytics_metrics, $params);
                
                $google_analytics_dimensions    = 'ga:date,ga:pagePath,ga:operatingSystem,ga:userType,ga:source';
                $google_analytics_metrics       = 'ga:pageviews';
                $google_analytics_sort_by       = '-ga:date';
                $params = array('dimensions' => $google_analytics_dimensions,'sort' => $google_analytics_sort_by);//,'max-results' => $google_analytics_max_results);
                $by_pageviews = $service->data_ga->get($google_analytics_profile_id, $start_date, $end_date, $google_analytics_metrics, $params);
                
                //var_dump($by_search->getRows());return;
            
                $this->view->by_user_type = $by_user_type;
                $this->view->by_search = $by_search;
                $this->view->by_social_interactions = $by_social_interactions;
                $this->view->by_pageviews = $by_pageviews;
            }
            catch(Exception $e){ //do we have an error?
                echo $e->getMessage(); //display error
            }  
        }
    }

    public function index2Action()
    {
        //$this->_redirect('/admin/gd/login');
       
       /* include_once(APPLICATION_PATH . '/../library/google/class.analytics.php');
    	define('ga_email','colinkr.test@gmail.com');
    	define('ga_password','colinkr123');
    	define('ga_profile_id','106794517');
    
    	// Start date and end date is optional
    	// if not given it will get data for the current month
    	$start_date = '2015-08-12';
	    $end_date = '2015-09-15';
     
    	$init = new fetchAnalytics(ga_email,ga_password,ga_profile_id,$start_date,$end_date);
                        
    	$this->view->trafficCount = $init->trafficCount();
    	$this->view->referralTraffic = $init->referralCount();
    	$this->view->trafficCountNum = $init->sourceCountNum();
    	$this->view->trafficCountPer = $init->sourceCountPer();
    	$this->view->perDayCount = $init->perDayCount(); */  
                
        require_once (APPLICATION_PATH . '/../library/src/Google/autoload.php');
        require_once (APPLICATION_PATH . '/../library/src/Google/Client.php');
        require_once (APPLICATION_PATH . '/../library/src/Google/Service/Analytics.php');
        
        // Initialise the Google Client object 
      /*  $client = new Google_Client();
        $client->setApplicationName('Colinkr');
         
        $client->setAssertionCredentials(new Google_Auth_AssertionCredentials(
                '787369762880-blqa1b2s529ccpihll4hce7aoqmoot79@developer.gserviceaccount.com',
                array('https://www.googleapis.com/auth/analytics.readonly'),
                file_get_contents("APPLICATION_PATH . '/../library/Colinkr-3bb187887da8.p12")
            )
        );
         
        // Get this from the Google Console, API Access page
        $client->setClientId('787369762880-blqa1b2s529ccpihll4hce7aoqmoot79.apps.googleusercontent.com');
        $client->setAccessType('offline_access');
         
        // Create the Analytics object, build the query and make a call ot the API
        $analytics = new Google_Service_Analytics($client);
        $analytics_id   = 'ga:106794517';
        $from_date       = date('Y-m-d', strtotime('-1 month'));//strtotime('-52 week'));
        $to_date      = date('Y-m-d');
        
            try {
                $optParams = array();
                // visits by user type
                $optParams['dimensions'] = "ga:userType";
                $optParams['sort'] = "-ga:visits";
                //$optParams['filters'] = "";
                //$optParams['max-results'] = "";                
                
                $metrics = 'ga:visits';
                
                $by_user_type = $analytics->data_ga->get($analytics_id, $from_date, $to_date, $metrics, $optParams);
                
                // Get visits by date
                $optParams = array(
                    'metrics' => 'ga:visits',
                    'dimensions' => 'ga:date',
                );
                
                $by_date = $analytics->data_ga->get($analytics_id, $from_date, $to_date, $metrics, $optParams);
                
                // Page Tracking
               /* $optParams = array(
                    'metrics' => 'ga:pageviews',
                    'dimensions' => 'ga:pageTitle',
                );
                
                $page_tracking = $analytics->data_ga->get($analytics_id, $from_date, $to_date, $metrics, $optParams);
                *
                
                //$dimensions = array('dimensions' => 'ga:pagePath','ga:country', 'ga:region', 'ga:city');
                //$page_tracking = $analytics->data_ga->get($analytics_id, $from_date, $to_date, $metrics, $dimensions);
                $page_tracking = array();
                $this->view->by_user_type = $by_user_type;
                $this->view->by_date = $by_date;
                $this->view->page_tracking = $page_tracking;
                
                //var_dump($page_tracking);
                //return;
                
            } catch(Exception $e) {
                echo 'There was an error : - ' . $e->getMessage();
            } */
            
            $client_id = '787369762880-blqa1b2s529ccpihll4hce7aoqmoot79.apps.googleusercontent.com';
            $client_secret = 'Ya__r8ROl0oqKO5hb3JpRJlF';
            $redirect_uri = 'http://aliinfotech.com/colinkr/admin/gd/index.php';
        
            $client = new Google_Client();
            $client->setApplicationName("Colinkr");
            $client->setClientId($client_id);
            $client->setClientSecret($client_secret);
            $client->setRedirectUri($redirect_uri);
            $client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
            $client->setAccessType('offline');   // Gets us our refresh token
                
            //For loging out.
            //if ($_GET['logout'] == "1") {
        	  // unset($_SESSION['token']);
            //}
                                
            // Step 1:  The user has not authenticated we give them a link to login    
            if (!$client->getAccessToken() && !isset($_SESSION['token'])) {
        
            	$authUrl = $client->createAuthUrl();
        
            	print "<a class='login' href='$authUrl'>Connect Me!</a>";
             }    
            //var_dump($client->getAccessToken());return;
            
            // Step 2: The user accepted your access now you need to exchange it.
            if (isset($_GET['code'])) {
                
            	$client->authenticate($_GET['code']);  
                
            	$_SESSION['token'] = $client->getAccessToken();
            	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            }
        
            // Step 3: We have access we can now create our service
            if (isset($_SESSION['token'])) 
            {
                print "<a class='logout' href='".$_SERVER['PHP_SELF']."?logout=1'>LogOut</a><br>";
                
                print "Access from google: " . $_SESSION['token']."<br>"; 
                
            	$client->setAccessToken($_SESSION['token']);
                
            	$service = new Google_Service_Analytics($client);
                
                // CODE FOR ManagementAccountSummaries START HERE
                // request user accounts
                $accounts = $service->management_accountSummaries->listManagementAccountSummaries();
               
                foreach ($accounts->getItems() as $item) {
        
        		echo "<b>Account:</b> ",$item['name'], "  " , $item['id'], "<br /> \n";
        		
        		foreach($item->getWebProperties() as $wp) {
        			echo '-----<b>WebProperty:</b> ' ,$wp['name'], "  " , $wp['id'], "<br /> \n";    
        			$views = $wp->getProfiles();
        			if (!is_null($views)) {
                                        // note sometimes a web property does not have a profile / view
        
        				foreach($wp->getProfiles() as $view) {
        
        					echo '----------<b>View:</b> ' ,$view['name'], "  " , $view['id'], "<br /> \n";    
        				}  // closes profile
        			}
        		} // Closes web property
        		
        	} // closes account summaries
        } // if end 
        
    } // index action end
    
    public function loginAction()
    {
        $auth = TBS_Auth::getInstance();

        $providers = $auth->getIdentity();
        //var_dump($providers);
        //return;

        // Here the response of the providers are registered
        if ($this->_hasParam('provider')) {
            $provider = $this->_getParam('provider');

            switch ($provider) {
                case "facebook":
                    if ($this->_hasParam('code')) {
                        $adapter = new TBS_Auth_Adapter_Facebook(
                                $this->_getParam('code'));
                        $result = $auth->authenticate($adapter);
                    }
                    if($this->_hasParam('error')) {
                        throw new Zend_Controller_Action_Exception('Facebook login failed, response is: ' . 
                            $this->_getParam('error'));
                    }
                    break;
                case "twitter":
                    if ($this->_hasParam('oauth_token')) {
                        $adapter = new TBS_Auth_Adapter_Twitter($_GET);
                        $result = $auth->authenticate($adapter);
                    }
                    break;
                case "google":
                
                    if ($this->_hasParam('code')) {
                        $adapter = new TBS_Auth_Adapter_Google(
                                $this->_getParam('code'));
                        $result = $auth->authenticate($adapter);
                    }
                    if($this->_hasParam('error')) {
                        throw new Zend_Controller_Action_Exception('Google login failed, response is: ' . 
                            $this->_getParam('error'));
                    }
                    break;
            }
            
            // What to do when invalid
            if (isset($result) && !$result->isValid()) {
                $auth->clearIdentity($this->_getParam('provider'));
                throw new Zend_Controller_Action_Exception('Login failed');
            } else {
                $this->_redirect('/admin/gd/connect');
            }
        }
        else 
        { 
            // Normal login page
            $this->view->googleAuthUrl = TBS_Auth_Adapter_Google::getAuthorizationUrl();
            $this->view->googleAuthUrlOffline = TBS_Auth_Adapter_Google::getAuthorizationUrl(true);
            $this->view->facebookAuthUrl = TBS_Auth_Adapter_Facebook::getAuthorizationUrl();
            $this->view->twitterAuthUrl = TBS_Auth_Adapter_Twitter::getAuthorizationUrl();
        }
    }
    
    public function connectAction()
    {
        $auth = TBS_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            throw new Zend_Controller_Action_Exception('Not logged in!', 404);
        }
        $this->view->providers = $auth->getIdentity();
    }

    public function logoutAction()
    {
        TBS_Auth::getInstance()->clearIdentity();
        $this->_redirect('/admin/index');
    }

    public function __call($method, $args)
    {
        if ('Action' == substr($method, -6))
        {
            // If the action method was not found, forward to the
            // index action
            return $this->_forward('index');
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "' . $method . '" called', 500);
    }
}
