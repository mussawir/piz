<?php 

class PagesController extends Zend_Controller_Action {
    private $baseurl = '';
	var $member_session = null;
	private $db = null;
    private $results = null;
	private $page = null;
	private $social = null;
	private $comment = null;
	 
    public function init() { 
		$this->_helper->layout->setLayout('layout');
        $request = Zend_Controller_Front::getInstance()->getRequest();
		$this->baseurl = $request->getScheme() . '://' . $request->getHttpHost();
        //$this->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->db = Zend_Db_Table::getDefaultAdapter();
        $this->member_session = new Zend_Session_Namespace("member_session");
		$this->comment_member = new Application_Model_Members();
		$this->customize = new Application_Model_CustomizePopup();
		$this->comment = new Application_Model_PageComments();
		$this->page = new Application_Model_Pages();
		$this->feedbacks_model = new Application_Model_Feedbacks();
		$this->feedbackscomment_model = new Application_Model_PageFeedbacks();
		$this->postcomment_model = new Application_Model_ProductComments();
		$this->feedbackslikes_model = new Application_Model_LikedFeedbacks();
		$this->social = new Application_Model_SocialLinks();
        $site_info = new Application_Model_SiteInfo();
        $this->view->site_url = $site_info->getUrls();
        $this->view->is_pages = true;
        $this->view->user_role = $this->member_session->role_id;
        $this->view->logged_user_id = $this->member_session->member_id;
	}
		
	public function indexAction()
    {		
	
    	//for social links
		$this->_helper->layout->setLayout('ad');
    	$links =  new Application_Model_SocialLinks();
    	$this->view->links = $links->getSocialLinks();
		
        $url = $this->get_url();
			$slug = parse_url($url);
			$url_slug = $slug['path'];
			
            $page =  substr($url_slug ,strrpos($url_slug, '/') + 1);
			//var_dump($page);
			//return;
            //is this page name is present in any url slug
        
		$results= $this->page->getPageByUrl($page);
		
		// Posts comment section
		$page_id = $results->page_id;
		$comment_member_id = $results->member_id;
		$member_comment = $this->comment_member->getDetails($comment_member_id);
		$comment_member_name = $member_comment->first_name .' '. $member_comment->last_name;
		$this->view->commentname = $comment_member_name;
		$pp_list = new Application_Model_PageProducts();
        $page_products = $pp_list->getPageProducts($page_id);
		$this->view->contact_number = $member_comment->contact_number;
		$this->view->wap_number = $member_comment->contact_number;
		// var_dump($page_products);exit;
		$this->view->share_url = $results->url_slug;
		if(isset($page_products)){
            $member_model = new Application_Model_Members();
            $member_comment_result = $member_model->getMemberDirectory($comment_member_id);
        }
		$this->view->page_products = $page_products;
		$this->view->member_result = $member_comment_result;
		$comments = $this->postcomment_model->getCommentsByPage($page_id);
		if($comments->toArray()){
			$this->view->comments = $comments;
		}
		// var_dump($comments);exit;
		
		$this->view->member_id = $comment_member_id;
		// End Posts comment section
        $this->view->page_id = $page_id;
		// $member_dir = $this->member_session->dir_name;
		// $this->view->member_dir = $member_dir;
		$customization = $this->customize->getCustomizationByID($results->member_id,$page_id);
		$this->view->custom = $customization;
		$feedback = $this->feedbacks_model->getFeedbackByID($page_id);
		// var_dump($feedback);exit;
		if(isset($feedback)){
			$this->view->feedback = $feedback;
			// $feedbackscomment = $this->feedbackscomment_model->getCommentsByPage($page_id,$feedback->feed_id);
			// $this->view->comments = $feedbackscomment;
			$likes = $this->feedbackslikes_model->getAllLikedPages($page_id,$feedback->feed_id);
			$this->view->likes = $likes;
			$dislikes = $this->feedbackslikes_model->getAllDisLikedPages($page_id,$feedback->feed_id);
			$this->view->dislikes = $dislikes;
		}
		
		
		// var_dump($results);
		// exit;
        // var_dump($results->toArray());exit;
        if(!isset($results)){
            $this->_redirect('/page-not-found');
        }
        
        if($results['page_for']==1){ // only for marketer
            if(!isset($this->member_session->role_id)){
                $this->_redirect('/page-not-found');
            } else if($this->member_session->role_id!=3){
                $this->_redirect('/page-not-found');
            }             
        }
        
        if($results['page_for']==2){ // for business and can view by marketer and business only
            if(!isset($this->member_session->role_id)){
                $this->_redirect('/page-not-found');
            } else if($this->member_session->role_id!=2 && $this->member_session->role_id!=3){
                $this->_redirect('/page-not-found');
            }
        }
        
        if($results['page_for']==3){ // for member and can view by marketer, business and member only
            if(!isset($this->member_session->role_id)){
                $this->_redirect('/page-not-found');
            } else if($this->member_session->role_id!=1 && $this->member_session->role_id!=2 && $this->member_session->role_id!=3){
                $this->_redirect('/page-not-found');
            }
        }
        
        $member_model = new Application_Model_Members();
        $member_result = $member_model->getMemberDirectory($results['member_id']); 
        
        // check if user visit by mobile device
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
        {
            $this->view->member_dir = $member_result['dir_name'].'/';
        } else {
            $this->view->member_dir = $member_result['dir_name'].'/';
        }
        
        $like_page_model = new Application_Model_LikedPages();
        $is_liked = false;
        if(isset($this->member_session->member_id)){
            $like_result = $like_page_model->getLikeByPage($results['page_id'], $this->member_session->member_id);
            $is_liked = isset($like_result) ? true : false;
            
            $this->view->member_name = $this->member_session->first_name . ' ' . $this->member_session->last_name;
            $this->view->member_email = $this->member_session->email;
        }
        
        $this->view->is_liked = $is_liked;
        
        // get all page likes 
        $this->view->likes_count = count($like_page_model->getAllLikedPages($results['page_id']));
		
		$this->view->list = $results;
        //var_dump($results);return;
	
        $result = $this->comment->getCommentsByPage($results->page_id);
		$this->view->comment = $result; 
		
        $form = new Application_Form_CommentForm();
		$this->view->form = $form;
		
        $brochure_model = new Application_Model_MemberBrochures();
        $brochures = $brochure_model->getMemberPageBrochures($results->member_id, $results->page_id); 
        
        $donwload_links = array();
        if(count($brochures)>0){
            foreach($brochures as $b){
                $html = "<button type='button' class='btn btn-primary' onclick=window.location.href='/pages/download-pdf/id/".$results->member_id."/file/".$b['brochure']."'>Download Brochure</button>";
                if(!empty($b['title'])){
                    $html .= '<p style="margin: 5px 0 20px;"><strong class="text-center">'.$b['title'].'</strong></p>';    
                }
                if(!empty($b['image'])){
                    $html .= '<div style="min-height:240px;"><img class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$b['image'].'" alt="Brochure Image" /></div>';
                }
                
                $donwload_links[] = $html;
            }
        }
        $this->view->donwload_links = $donwload_links;
        
        // members other pages
        $member_pages = $this->page->getMemberPages($results->member_id, 'page_id');
        
        $buffer=array();
        foreach($member_pages as $mp){
            if($mp['page_id'] < $results->page_id){                
                $buffer['prev']= $mp['url_slug'];
                if(isset($buffer['prev'])) continue;                    
            }
            
            if($mp['page_id'] > $results->page_id){
                if(isset($buffer['next'])) break;
                $buffer['next']= $mp['url_slug'];                    
            }
        }
        $this->view->next_prev =  $buffer;
        $this->view->mem_id = $results->member_id;
        /*
        // admin ad list
        $ad_model = new Application_Model_PageAds();
        $ad_list = $ad_model->getAllPageAds($results['page_id']); 
        $this->view->ad_list = $ad_list;
        */
        
		// set meta data on page
        Zend_Layout::getMvcInstance()->assign('metadata', array('keywords'=>$results->keyword_content, 'description'=>$results->description_content));
		
	} // index function end
	public function uploadCommentAction(){
		if ($this->_request->isPost())
        {
		$formData = $this->_request->getPost();
		$formData['email'] = $this->member_session->email;
		$formData['name'] = $this->member_session->first_name. ' ' . $this->member_session->last_name ;
		$result = $this->feedbackscomment_model->addComment($formData);
		$formData1 = json_encode($result);
		die($formData1);
		}
	}
	public function saveFeedbacklikeAction(){
		// $this->ajaxed();
		if ($this->_request->isPost())
        {
			$page_id = $_POST['page_id'];
        $feed_id = $_POST['feed_id'];
        $like_page_model = new Application_Model_LikedFeedbacks();
		$status = 0;
        $dislike_status = 1;
        $check_dislike = $like_page_model->getLikeByPage($page_id, $this->member_session->member_id,$feed_id,$dislike_status);
		if($check_dislike){
			$update = $like_page_model->updateLike($page_id, $this->member_session->member_id,$feed_id,$status);
			if($update){
				// $array = array('status'=>'exist');
				// $array = json_encode($array);
				// die($array);
				$this->actionSaveTest(array('status'=>'exist'));
			}
		}else{
			$result = $like_page_model->add($page_id, $this->member_session->member_id,$feed_id,$status);
			if($result)
			{
				// $array = array('status'=>'success');
				// $array = json_encode($array);
				// die($array);
				$this->actionSaveTest(array('status'=>'success'));
			} else {
				// $array = array('status'=>'error');
				// $array = json_encode($array);
				// die($array);
				$this->actionSaveTest(array('status'=>'error'));
			}
		}
		}
        
        
	}
 function actionSaveTest($arr){
		$array = $arr;
		$array = json_encode($arr);
		die($array);
	}
	public function saveFeedbackdislikeAction(){
		$this->ajaxed();
        
        $page_id = $this->getRequest()->getParam('page_id');
        $feed_id = $this->getRequest()->getParam('feed_id');
        $like_page_model = new Application_Model_LikedFeedbacks();
		$status = 1;
		$like_status = 0;
        $check_like = $like_page_model->getLikeByPage($page_id, $this->member_session->member_id,$feed_id,$like_status);
		if($check_like){
			// $array = array('status'=>1);
			// $array = json_encode($array);
			// die($array);
			$update = $like_page_model->updateLike($page_id, $this->member_session->member_id,$feed_id,$status);
			if($update){
				echo 'exist';
			}
		}else{
			$result = $like_page_model->add($page_id, $this->member_session->member_id,$feed_id,$status);
			if($result)
			{
				// $page = $this->page->getPageLikes($page_id);
				// $this->page->setPageLikes($page_id, ($page['likes']+1));
				echo 'success';
			} else {
				echo 'error';
			}
		}
        // $result = $like_page_model->add($page_id, $this->member_session->member_id,$feed_id,$status);
        
        // if($result)
        // {
            $page = $this->page->getPageLikes($page_id);
            $this->page->setPageLikes($page_id, ($page['likes']+1));
            // echo 'success';
        // } else {
            // echo 'error';
        // }
	}
    public function productPageAction(){
		$this->_helper->layout->setLayout('products');
		$query_string = $this->_request->getParam("q");
		$arr = explode('|',$query_string);
		$id = $arr[0];
		$dir = $arr[1];
		$member_pages = $this->page->getMemberPages($dir, 'page_id');
		
		$pp_list = new Application_Model_PageProducts();
        $result = $pp_list->getPageProduct($id);
		$member_model = new Application_Model_Members();
		$dir_result = $member_model->getMemberDirectory($dir);
		$details = $member_model->getDetails($dir);
		$slug = $member_pages['url_slug']->url_slug;
		// $dir = $this->_request->getParam("dir");
		// $dir = trim($dir);
		$this->view->details = $details;
		$this->view->products = $result;  
		$this->view->dir = $dir_result;  
		$this->view->member_dir = $dir_result['dir_name'].'/500X500/';
	
		// if(isset($_SERVER['HTTP_REFERER'])) {
    // $test = $_SERVER['HTTP_REFERER'];
	// if(strpos($test,'facebook')!==false){
		
		// $this->_redirect('/'.$slug.'#social_'.$id.'');
	// }
		// }
	
		
	} 
    public function loadPageAdsAction()
    {
        $this->ajaxed();
        
        $page_id = $this->getRequest()->getParam('page_id');
        $member_id = $this->getRequest()->getParam('member_id');
        $category_id = $this->getRequest()->getParam('category_id');
        /*$page = $this->getRequest()->getPost('page');
        $operator = ">";
        if($page=='prev'){
            $operator = "<";
        }*/
        
        //$page_count = $this->page->getMemberPageCount($member_id);
        $data = array();
        
        $ad_reasult = $this->page->getAdsByMemberPages($this->db, $member_id, $page_id);
        
        $html ='';
            foreach($ad_reasult as $ar){
                $html .= "<div class='col-md-12' style='margin-bottom: 15px;'>
                    <a href=".$ar['url_slug'].">";
                    if(isset($ar['banner_img']) && (!empty($ar['banner_img']))) {
                        $html .= "<img class='img-responsive' src='/images/uploads/".$ar['dir_name']."/500X500/".$ar['banner_img']."' alt='".$ar['title']."' /></a>";    
                    } else {
                        $html .= "<strong class='text-primary'>".$ar['title']."</strong></a><br/>
                        <p>".$ar['page_description']."</p>";
                    }
                    
                $html .= "</div>";
            }
            
        $data = array('status'=>'success', 'data'=> $html);
        
        echo json_encode($data);
    } // load page ads end
    
function timeago($date) {
	   $timestamp = strtotime($date);	
	   
	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");

	   $currentTime = time();
	   if($currentTime >= $timestamp) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . " ago ";
	   }
	}
	public function postCommentAction(){
		if ($this->_request->isPost())
        {
		$formData = $this->_request->getPost();
		$formData['email'] = $this->member_session->email;
		$formData['name'] = $this->member_session->first_name. ' ' . $this->member_session->last_name ;
		$result = $this->postcomment_model->addComment($formData);
		$formData1 = json_encode($result);
		die($formData1);
		}
	}
	public function testAction(){
		$page_id = 210;
		$pp_list = new Application_Model_PageProducts();
        $result = $pp_list->getPageProducts($page_id);
		$result = $this->postcomment_model->productCommentsJoin(211);
		echo '<pre>';
		print_r($result);exit;
	}
	public function sortingAction(){
		if ($this->_request->isPost())
        {
		$page_id = $this->getRequest()->getParam('page_id');
        $contact_number = 03242352355;
        $wap_number = 03242352355;
        $share_url = 'http://www.pageiz.com';
		$member_id = 304;
		$pp_list = new Application_Model_PageProducts();
        $result = $pp_list->getPageProductsbySorting($_POST['page_id'],$_POST['type']);
		$comments = $this->postcomment_model->getCommentsByPage($_POST['page_id']);
		$member_result = array();
        if(isset($result)){
            $member_model = new Application_Model_Members();
            $member_result = $member_model->getMemberDirectory($member_id);
        }
		if($result){
			$html =''; 
			
		foreach(array_chunk($result->toArray(),1) as $row){
			$html .= "<div class='row' style='margin-top:20px;margin-bottom:20px;'><input type='hidden' value='.$member_id.'>";
			foreach($row as $r){ 
			if($r['post_type'] === 1){
				$html .= '<input type="hidden" value="'.$r['post_type'].'"><input type="hidden" value="'.$share_url.'"><div id="social_'.$r['pp_id'].'" class="col-md-12 thumbnail" style="margin-bottom: 0px; border-radius: 0; padding: 10px;">';
                $html .= '<h4 class="">'.$r['name'].'</h4>';$html .= '<p class="" style="margin: 0 3px 10px;font-size: 11px;">'.$this->timeago($r['date_created']).' <i class="fa fa-clock-o"></i> </p>';
                if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    $html .= '<img id='.$r['pp_id'].' class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$r['photo'].'" alt="'.$r['name'].'"  onclick="var id = $(this).attr(&quot;id&quot;);$(&quot;#products_&quot;+id).slideToggle(500);" style="cursor:pointer;"/>';
                } else {
                    $html .= '<img id='.$r['pp_id'].' class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$r['photo'].'" alt="'.$r['name'].'" style="cursor:pointer;" onclick="var id = $(this).attr(&quot;id&quot;);$(&quot;#products_&quot;+id).slideToggle(500);"/>'; 
                }
				if($r['price']>0 && $r['hide_price']==0){
                    $html .= "<p style='margin-top: 10px;'><strong class='text-success'>Price: RM".$r['price']."</strong></p>";
                } 
                $html .= '<div id="products_'.$r['pp_id'].'" class="products_details" style="margin-top:10px;">
                        <a href="tel:'.$contact_number.'" class="btn btn-info btn-call"  style="float:left;margin-left: 2px;"><span class="fa fa-phone"></span></a>
                        
                        <div class="dropdown" style="float:left;margin-left: 2px;">
                           <button type ="button" class ="btn btn-success dropdown-toggle" id="whatsapp-menu" data-toggle="dropdown">
                              <span class="fa fa-whatsapp"></span> <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" role="menu" aria-labelledby="whatsapp-menu">                            
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=callto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Call '.$wap_number.'</a></li>
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Message</a></li>
                            <li role="presentation"><a role="menuitem" href="whatsapp://send?text='.$share_url.'" data-action="share/whatsapp/share">Share Page</a></li>
                           </ul>
                        </div>
                       
                        <span style="margin-left: 2px;"  style="float:left;">
                        <a onclick="window.open(this.href, '."'newwindow'".', '."'width=680, height=460'".'); return false;" href="https://www.facebook.com/sharer/sharer.php?u=http://pageiz.com/pages/product-page?q='.$r['pp_id'].'|'.$member_id.'" class="btn btn-primary"><span class="fa fa-facebook"><span></a>
                        </span>';
                
                if($r['discount']>0 && $r['hide_discount']==0){
                    $html .= "<p><strong class='text-success'>Discount: RM".$r['discount']."</strong></p>";
                }      
                $html .= "<div class='unique-heavy-heavy-bike' style='margin-top:20px;'>".stripslashes($r['description'])."</div>";
                if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    $html .= "<p class='text-center'><a href='".$r['buy_link']."' class='btn btn-sm btn-warning'>BUY NOW</a></p>";
                }
				$html .= '</div></div><div class="col-md-12 thumbnail" style="border-radiuss:0;">
					
					
					<svg style="/* height: 100%; */ height: 28px; /* stroke-width: 100%; */ width: 25px; /* margin-top: 0px; */">
						<svg viewBox="0 0 24 24" width="24px" height="24px" x="0" y="0" preserveAspectRatio="xMinYMin meet" class="icon">
						 <g class="large-icon" style="fill: currentColor">
						    <g>
							 <path d="M20,6v9.6c0,0.3-0.2,0.7-0.5,0.8L16,18.6V16H4V6H20M20,4H4C2.9,4,2,4.9,2,6v10c0,1.1,0.9,2,2,2h10v4l6.6-4.1c0.9-0.5,1.4-1.5,1.4-2.5V6C22,4.9,21.1,4,20,4L20,4z"></path>
						    </g>
						 </g>
						 </svg>
					 </svg>
					 <a onclick="opencomment('.$r['pp_id'].')" style="margin-top: 10px;color:#333333;font-weight:bold;cursor:pointer;position:relative;bottom: 11px; right: 2px;"> Comments</a>
				</div>';
				$html.= '<div class="col-md-12 thumbnail comment" id="comments_'.$r['pp_id'].'" style=" ">
					<form id="comment_post_'.$r['pp_id'].'" method="POST" action="" style="    margin-bottom: 9px;    padding-top: 15px; border: 0;">
						<input autocomplete="off" type="text" class="form-control" name="comment" id="comment_'.$r['pp_id'].'" style="resize:none;border-radius:0;    border: 1px solid #bdc7d8;" rows="2" placeholder="Write your comment." required>
						<input type="hidden" name="page_id" value="'.$page_id.'">
						
						<input type="hidden" name="pp_id" value="'.$r['pp_id'].'">
						<div class="form-group" id="comment_btn">
							<button type="submit" id="post_btn_'.$r['pp_id'].'" onclick="submit_comment('.$r['pp_id'].')" class=" btn btn-success btn-xs pull-right" style="">Comment <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
							
						</div>
					</form>';
					if($comments){ 
					$countter = 0;
						foreach($comments as $cmnt){
							if($cmnt['pp_id'] == $r['pp_id']){
								if($countter==0){
								$html.='								<hr id="hrline_'.$r['pp_id'].'">';
								}
								$countter++;
							
								$html.='<div class="row" style="padding:10px 0;background:#F6F7F9">
									<div class="col-md-2 col-xs-2">
										<img src="https://gitlab.com/uploads/user/avatar/56386/tt_avatar_small.jpg" class="img-responsive" style="margin: auto;">
									</div>
									<div class="col-md-10 col-xs-10" style="padding-left:0;    padding-right: 31px;">
										<p style="font-size: 12px; word-wrap: break-word;font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 300;"><span style="font-weight: bold; color: #36589F; font-size: 13px; font-family: Open Sans, Helvetica, Arial, sans-serif;">'.$cmnt['Name'].'</span> '.$cmnt['comment'].'</p>
										<div style="font-size: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 300;">
										'.$this->timeago($cmnt['comment_date']).'
										</div>
									</div>
								</div>';
							}
						}
					}
					
					
					$html.= '</div>';
			}if($r['post_type'] === 3){
				$html .= '<input type="hidden" value="'.$r['post_type'].'"><input type="hidden" value="'.$share_url.'"><div id="social_'.$r['pp_id'].'" class="col-md-12 thumbnail" style="margin-bottom: 0px; border-radius: 0; padding: 10px;">';
                $html .= '<h4 class="">'.$r['name'].'</h4>';$html .= '<p class="" style="margin: 0 3px 10px;font-size: 11px;">'.$this->timeago($r['date_created']).' <i class="fa fa-clock-o"></i> </p>';
                if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    $html .= '<img id='.$r['pp_id'].' class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$r['photo'].'" alt="'.$r['name'].'"  onclick="var id = $(this).attr(&quot;id&quot;);$(&quot;#products_&quot;+id).slideToggle(500);" style="cursor:pointer;"/>';
                } else {
                    $html .= '<img id='.$r['pp_id'].' class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$r['photo'].'" alt="'.$r['name'].'" style="cursor:pointer;" onclick="var id = $(this).attr(&quot;id&quot;);$(&quot;#products_&quot;+id).slideToggle(500);"/>'; 
                }
				if($r['price']>0 && $r['hide_price']==0){
                    $html .= "<p style='margin-top: 10px;'><strong class='text-success'>Price: RM".$r['price']."</strong></p>";
                } 
                $html .= '<div id="products_'.$r['pp_id'].'" class="products_details" style="margin-top:10px;">
                        <a href="tel:'.$contact_number.'" class="btn btn-info btn-call"  style="float:left;margin-left: 2px;"><span class="fa fa-phone"></span></a>
                        
                        <div class="dropdown" style="float:left;margin-left: 2px;">
                           <button type ="button" class ="btn btn-success dropdown-toggle" id="whatsapp-menu" data-toggle="dropdown">
                              <span class="fa fa-whatsapp"></span> <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" role="menu" aria-labelledby="whatsapp-menu">                            
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=callto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Call '.$wap_number.'</a></li>
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Message</a></li>
                            <li role="presentation"><a role="menuitem" href="whatsapp://send?text='.$share_url.'" data-action="share/whatsapp/share">Share Page</a></li>
                           </ul>
                        </div>
                       
                        <span style="margin-left: 2px;"  style="float:left;">
                        <a onclick="window.open(this.href, '."'newwindow'".', '."'width=680, height=460'".'); return false;" href="https://www.facebook.com/sharer/sharer.php?u=http://pageiz.com/pages/product-page?q='.$r['pp_id'].'|'.$member_id.'" class="btn btn-primary"><span class="fa fa-facebook"><span></a>
                        </span>';
                
                if($r['discount']>0 && $r['hide_discount']==0){
                    $html .= "<p><strong class='text-success'>Discount: RM".$r['discount']."</strong></p>";
                }      
                $html .= "<div class='unique-heavy-heavy-bike' style='margin-top:20px;'>".stripslashes($r['description'])."</div>";
                // if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    // $html .= "<p class='text-center'><a href='".$r['buy_link']."' class='btn btn-sm btn-warning'>BUY NOW</a></p>";
                // }
				
				$html .= '</div></div><div class="col-md-12 thumbnail" style="border-radiuss:0;">
					
					
					<svg style="/* height: 100%; */ height: 28px; /* stroke-width: 100%; */ width: 25px; /* margin-top: 0px; */">
						<svg viewBox="0 0 24 24" width="24px" height="24px" x="0" y="0" preserveAspectRatio="xMinYMin meet" class="icon">
						 <g class="large-icon" style="fill: currentColor">
						    <g>
							 <path d="M20,6v9.6c0,0.3-0.2,0.7-0.5,0.8L16,18.6V16H4V6H20M20,4H4C2.9,4,2,4.9,2,6v10c0,1.1,0.9,2,2,2h10v4l6.6-4.1c0.9-0.5,1.4-1.5,1.4-2.5V6C22,4.9,21.1,4,20,4L20,4z"></path>
						    </g>
						 </g>
						 </svg>
					 </svg>
					 <a onclick="opencomment('.$r['pp_id'].')" style="margin-top: 10px;color:#333333;font-weight:bold;cursor:pointer;position:relative;bottom: 11px; right: 2px;"> Comments</a>
				</div>';
				$html.= '<div class="col-md-12 thumbnail comment" id="comments_'.$r['pp_id'].'" style=" ">
					<form id="comment_post_'.$r['pp_id'].'" method="POST" action="" style="    margin-bottom: 9px;    padding-top: 15px; border: 0;">
						<input autocomplete="off" type="text" class="form-control" name="comment" id="comment_'.$r['pp_id'].'" style="resize:none;border-radius:0;    border: 1px solid #bdc7d8;" rows="2" placeholder="Write your comment." required>
						<input type="hidden" name="page_id" value="'.$page_id.'">
						
						<input type="hidden" name="pp_id" value="'.$r['pp_id'].'">
						<div class="form-group" id="comment_btn">
							<button type="submit" id="post_btn_'.$r['pp_id'].'" onclick="submit_comment('.$r['pp_id'].')" class=" btn btn-success btn-xs pull-right" style="">Comment <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
							
						</div>
					</form>';
					if($comments){ 
					$countter = 0;
						foreach($comments as $cmnt){
							if($cmnt['pp_id'] == $r['pp_id']){
								if($countter==0){
								$html.='								<hr id="hrline_'.$r['pp_id'].'">';
								}
								$countter++;
							
								$html.='<div class="row" style="padding:10px 0;background:#F6F7F9">
									<div class="col-md-2 col-xs-2">
										<img src="https://gitlab.com/uploads/user/avatar/56386/tt_avatar_small.jpg" class="img-responsive" style="margin: auto;">
									</div>
									<div class="col-md-10 col-xs-10" style="padding-left:0;    padding-right: 31px;">
										<p style="font-size: 12px; word-wrap: break-word;font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 300;"><span style="font-weight: bold; color: #36589F; font-size: 13px; font-family: Open Sans, Helvetica, Arial, sans-serif;">'.$cmnt['Name'].'</span> '.$cmnt['comment'].'</p>
										<div style="font-size: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 300;">
										'.$this->timeago($cmnt['comment_date']).'
										</div>
									</div>
								</div>';
							}
						}
					}
					
					
					$html.= '</div>';
			}
			elseif($r['post_type'] == 2){
				$html .= '<input type="hidden" value="'.$share_url.'"><div id="social_'.$r['pp_id'].'" class="col-md-12 thumbnail" style="margin-bottom: 0px; border-radius: 0; padding: 10px;">';
                $html .= '<p class="" style="margin: 0 3px 10px;font-size: 11px;">'.$this->timeago($r['date_created']).' <i class="fa fa-clock-o"></i> </p>';
                
                $html .= '<div id="products_'.$r['pp_id'].'" class="details" style="margin-top:10px;">
                        <a href="tel:'.$contact_number.'" class="btn btn-info btn-call"  style="float:left;margin-left: 2px;"><span class="fa fa-phone"></span></a>
                        
                        <div class="dropdown" style="float:left;margin-left: 2px;">
                           <button type ="button" class ="btn btn-success dropdown-toggle" id="whatsapp-menu" data-toggle="dropdown">
                              <span class="fa fa-whatsapp"></span> <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" role="menu" aria-labelledby="whatsapp-menu">                            
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=callto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Call '.$wap_number.'</a></li>
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Message</a></li>
                            <li role="presentation"><a role="menuitem" href="whatsapp://send?text='.$share_url.'" data-action="share/whatsapp/share">Share Page</a></li>
                           </ul>
                        </div>
                       
                        <span style="margin-left: 2px;"  style="float:left;">
                        <a onclick="window.open(this.href, '."'newwindow'".', '."'width=680, height=460'".'); return false;" href="https://www.facebook.com/sharer/sharer.php?u=http://pageiz.com/pages/product-page?q='.$r['pp_id'].'|'.$member_id.'" class="btn btn-primary"><span class="fa fa-facebook"><span></a>
                        </span>';
                    
                $html .= "<div class='unique-heavy-heavy-bike' style='margin-top:20px;'>".stripslashes($r['description'])."</div>";
                
                // if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    // $html .= "<p class='text-center'><a href='".$r['buy_link']."' class='btn btn-sm btn-warning'>BUY NOW</a></p>";
                // }
				
				$html .= '</div></div><div class="col-md-12 thumbnail" style="border-radiuss:0;">
					
					
					<svg style="/* height: 100%; */ height: 28px; /* stroke-width: 100%; */ width: 25px; /* margin-top: 0px; */">
						<svg viewBox="0 0 24 24" width="24px" height="24px" x="0" y="0" preserveAspectRatio="xMinYMin meet" class="icon">
						 <g class="large-icon" style="fill: currentColor">
						    <g>
							 <path d="M20,6v9.6c0,0.3-0.2,0.7-0.5,0.8L16,18.6V16H4V6H20M20,4H4C2.9,4,2,4.9,2,6v10c0,1.1,0.9,2,2,2h10v4l6.6-4.1c0.9-0.5,1.4-1.5,1.4-2.5V6C22,4.9,21.1,4,20,4L20,4z"></path>
						    </g>
						 </g>
						 </svg>
					 </svg>
					 <a onclick="opencomment('.$r['pp_id'].')" style="margin-top: 10px;color:#333333;font-weight:bold;cursor:pointer;position:relative;bottom: 11px; right: 2px;"> Comments</a>
				</div>';
				$html.= '<div class="col-md-12 thumbnail comment" id="comments_'.$r['pp_id'].'" style=" ">
					<form id="comment_post_'.$r['pp_id'].'" method="POST" action="" style="    margin-bottom: 9px;    padding-top: 15px; border: 0;">
						<input autocomplete="off" type="text" class="form-control" name="comment" id="comment_'.$r['pp_id'].'" style="resize:none;border-radius:0;    border: 1px solid #bdc7d8;" rows="2" placeholder="Write your comment." required>
						<input type="hidden" name="page_id" value="'.$page_id.'">
						
						<input type="hidden" name="pp_id" value="'.$r['pp_id'].'">
						<div class="form-group" id="comment_btn">
							<button type="submit" id="post_btn_'.$r['pp_id'].'" onclick="submit_comment('.$r['pp_id'].')" class=" btn btn-success btn-xs pull-right" style="">Comment <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
							
						</div>
					</form>';
					if($comments){ 
					$countter = 0;
						foreach($comments as $cmnt){
							if($cmnt['pp_id'] == $r['pp_id']){
								if($countter==0){
								$html.='								<hr id="hrline_'.$r['pp_id'].'">';
								}
								$countter++;
							
								$html.='<div class="row" style="padding:10px 0;background:#F6F7F9">
									<div class="col-md-2 col-xs-2">
										<img src="https://gitlab.com/uploads/user/avatar/56386/tt_avatar_small.jpg" class="img-responsive" style="margin: auto;">
									</div>
									<div class="col-md-10 col-xs-10" style="padding-left:0;    padding-right: 31px;">
										<p style="font-size: 12px; word-wrap: break-word;font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 300;"><span style="font-weight: bold; color: #36589F; font-size: 13px; font-family: Open Sans, Helvetica, Arial, sans-serif;">'.$cmnt['Name'].'</span> '.$cmnt['comment'].'</p>
										<div style="font-size: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 300;">
										'.$this->timeago($cmnt['comment_date']).'
										</div>
									</div>
								</div>';
							}
						}
					}
					
					
					$html.= '</div>';
				
			}
			elseif($r['post_type'] == 4){
				$html .= '<input type="hidden" value="'.$share_url.'"><div id="social_'.$r['pp_id'].'" class="col-md-12 thumbnail" style="margin-bottom: 0px; border-radius: 0; padding: 10px;">';
                $html .= '<p class="" style="margin: 0 3px 10px;font-size: 11px;">'.$this->timeago($r['date_created']).' <i class="fa fa-clock-o"></i> </p>';
                
                $html .= '<div id="products_'.$r['pp_id'].'" class="details" style="margin-top:10px;">
                        <a href="tel:'.$contact_number.'" class="btn btn-info btn-call"  style="float:left;margin-left: 2px;"><span class="fa fa-phone"></span></a>
                        
                        <div class="dropdown" style="float:left;margin-left: 2px;">
                           <button type ="button" class ="btn btn-success dropdown-toggle" id="whatsapp-menu" data-toggle="dropdown">
                              <span class="fa fa-whatsapp"></span> <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" role="menu" aria-labelledby="whatsapp-menu">                            
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=callto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Call '.$wap_number.'</a></li>
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Message</a></li>
                            <li role="presentation"><a role="menuitem" href="whatsapp://send?text='.$share_url.'" data-action="share/whatsapp/share">Share Page</a></li>
                           </ul>
                        </div>
                       
                        <span style="margin-left: 2px;"  style="float:left;">
                        <a onclick="window.open(this.href, '."'newwindow'".', '."'width=680, height=460'".'); return false;" href="https://www.facebook.com/sharer/sharer.php?u=http://pageiz.com/pages/product-page?q='.$r['pp_id'].'|'.$member_id.'" class="btn btn-primary"><span class="fa fa-facebook"><span></a>
                        </span>';
                    
                $html .= '<div class="video-container" style="margin-top:10px;">
					<iframe width="853" height="480" src=" '.$r['video_url'].'" frameborder="0" allowfullscreen></iframe>
				</div>';
                
                // if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    // $html .= "<p class='text-center'><a href='".$r['buy_link']."' class='btn btn-sm btn-warning'>BUY NOW</a></p>";
                // }
				
				$html .= '</div></div><div class="col-md-12 thumbnail" style="border-radiuss:0;">
					
					
					<svg style="/* height: 100%; */ height: 28px; /* stroke-width: 100%; */ width: 25px; /* margin-top: 0px; */">
						<svg viewBox="0 0 24 24" width="24px" height="24px" x="0" y="0" preserveAspectRatio="xMinYMin meet" class="icon">
						 <g class="large-icon" style="fill: currentColor">
						    <g>
							 <path d="M20,6v9.6c0,0.3-0.2,0.7-0.5,0.8L16,18.6V16H4V6H20M20,4H4C2.9,4,2,4.9,2,6v10c0,1.1,0.9,2,2,2h10v4l6.6-4.1c0.9-0.5,1.4-1.5,1.4-2.5V6C22,4.9,21.1,4,20,4L20,4z"></path>
						    </g>
						 </g>
						 </svg>
					 </svg>
					 <a onclick="opencomment('.$r['pp_id'].')" style="margin-top: 10px;color:#333333;font-weight:bold;cursor:pointer;position:relative;bottom: 11px; right: 2px;"> Comments</a>
				</div>';
				$html.= '<div class="col-md-12 thumbnail comment" id="comments_'.$r['pp_id'].'" style=" ">
					<form id="comment_post_'.$r['pp_id'].'" method="POST" action="" style="    margin-bottom: 9px;    padding-top: 15px; border: 0;">
						<input autocomplete="off" type="text" class="form-control" name="comment" id="comment_'.$r['pp_id'].'" style="resize:none;border-radius:0;    border: 1px solid #bdc7d8;" rows="2" placeholder="Write your comment." required>
						<input type="hidden" name="page_id" value="'.$page_id.'">
						
						<input type="hidden" name="pp_id" value="'.$r['pp_id'].'">
						<div class="form-group" id="comment_btn">
							<button type="submit" id="post_btn_'.$r['pp_id'].'" onclick="submit_comment('.$r['pp_id'].')" class=" btn btn-success btn-xs pull-right" style="">Comment <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
							
						</div>
					</form>';
					if($comments){ 
					$countter = 0;
						foreach($comments as $cmnt){
							if($cmnt['pp_id'] == $r['pp_id']){
								if($countter==0){
								$html.='								<hr id="hrline_'.$r['pp_id'].'">';
								}
								$countter++;
							
								$html.='<div class="row" style="padding:10px 0;background:#F6F7F9">
									<div class="col-md-2 col-xs-2">
										<img src="https://gitlab.com/uploads/user/avatar/56386/tt_avatar_small.jpg" class="img-responsive" style="margin: auto;">
									</div>
									<div class="col-md-10 col-xs-10" style="padding-left:0;    padding-right: 31px;">
										<p style="font-size: 12px; word-wrap: break-word;font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 300;"><span style="font-weight: bold; color: #36589F; font-size: 13px; font-family: Open Sans, Helvetica, Arial, sans-serif;">'.$cmnt['Name'].'</span> '.$cmnt['comment'].'</p>
										<div style="font-size: 10px;font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 300;">
										'.$this->timeago($cmnt['comment_date']).'
										</div>
									</div>
								</div>';
							}
						}
					}
					
					
					$html.= '</div>';
				
			}
                
				
				
            }
			$html.= "</div>";
		}
			$formData1 = json_encode($html);
			die($formData1);
			
		}
		}
	}
	public function testingAction(){
		$this->_helper->layout->setLayout('home');
        $comments_name = $this->member_session->first_name .' '. $this->member_session->last_name;
		$this->view->commentname = $comments_name;
        $page_id = 211;
		$pp_list = new Application_Model_PageProducts();
        $page_products = $pp_list->getPageProductsbySorting($page_id,1);
		// echo '<pre>';
        // var_dump($page_products->toArray());exit;
        $member_id = 304;
        $contact_number = 03242352355;
        $wap_number = 03242352355;
		 
		$this->view->member_id = $member_id;
		$this->view->page_id = $page_id;
        $share_url = 'http://www.pageiz.com';
        $this->view->contact_number = $contact_number;
		$this->view->wap_number = $wap_number;
		$this->view->share_url = $share_url;
        
        // $member_result = array();
        if(isset($page_products)){
            $member_model = new Application_Model_Members();
            $member_result = $member_model->getMemberDirectory($member_id);
        }
		// echo $member_result->dir_name;exit;
         
        $this->view->page_products = $page_products;
		$this->view->member_result = $member_result;
		$comments = $this->postcomment_model->getCommentsByPage(211);
		$this->view->comments = $comments;
	}
   public function loadProductsAction()
    {
        $this->ajaxed();
        
        $page_id = $this->getRequest()->getParam('page_id');
        $member_id = $this->getRequest()->getParam('member_id');
        $contact_number = $this->getRequest()->getParam('contact_number');
        $wap_number = $this->getRequest()->getParam('wap_number');
        $share_url = $this->getRequest()->getParam('share_url');
        
        $pp_list = new Application_Model_PageProducts();
        $result = $pp_list->getPageProducts($page_id);
        
        $member_result = array();
        if(isset($result)){
            $member_model = new Application_Model_Members();
            $member_result = $member_model->getMemberDirectory($member_id);
        }
         
        $html =''; 
		foreach(array_chunk($result->toArray(),1) as $row){
			$html .= "<div class='row' style='margin-top:20px;margin-bottom:20px;'><input type='hidden' value='.$member_id.'>";
			foreach($row as $r){ 
			if($r['post_type'] === 1){
				$html .= '<input type="hidden" value="'.$r['post_type'].'"><input type="hidden" value="'.$share_url.'"><div id="social_'.$r['pp_id'].'" class="col-md-12 thumbnail" style="margin-bottom: 15px; border-radius: 0; padding: 10px;">';
                $html .= '<h4 class="">'.$r['name'].'</h4>';$html .= '<p class="" style="margin: 0 3px 10px;font-size: 11px;">'.$this->timeago($r['date_created']).' <i class="fa fa-clock-o"></i> </p>';
                if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    $html .= '<img id='.$r['pp_id'].' class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$r['photo'].'" alt="'.$r['name'].'"  onclick="var id = $(this).attr(&quot;id&quot;);$(&quot;#products_&quot;+id).slideToggle(500);" style="cursor:pointer;"/>';
                } else {
                    $html .= '<img id='.$r['pp_id'].' class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$r['photo'].'" alt="'.$r['name'].'" style="cursor:pointer;" onclick="var id = $(this).attr(&quot;id&quot;);$(&quot;#products_&quot;+id).slideToggle(500);"/>'; 
                }
				if($r['price']>0 && $r['hide_price']==0){
                    $html .= "<p style='margin-top: 10px;'><strong class='text-success'>Price: RM".$r['price']."</strong></p>";
                } 
                $html .= '<div id="products_'.$r['pp_id'].'" class="products_details" style="margin-top:10px;">
                        <a href="tel:'.$contact_number.'" class="btn btn-info btn-call"  style="float:left;margin-left: 2px;"><span class="fa fa-phone"></span></a>
                        
                        <div class="dropdown" style="float:left;margin-left: 2px;">
                           <button type ="button" class ="btn btn-success dropdown-toggle" id="whatsapp-menu" data-toggle="dropdown">
                              <span class="fa fa-whatsapp"></span> <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" role="menu" aria-labelledby="whatsapp-menu">                            
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=callto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Call '.$wap_number.'</a></li>
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Message</a></li>
                            <li role="presentation"><a role="menuitem" href="whatsapp://send?text='.$share_url.'" data-action="share/whatsapp/share">Share Page</a></li>
                           </ul>
                        </div>
                       
                        <span style="margin-left: 2px;"  style="float:left;">
                        <a onclick="window.open(this.href, '."'newwindow'".', '."'width=680, height=460'".'); return false;" href="https://www.facebook.com/sharer/sharer.php?u=http://pageiz.com/pages/product-page?q='.$r['pp_id'].'|'.$member_id.'" class="btn btn-primary"><span class="fa fa-facebook"><span></a>
                        </span>';
                
                if($r['discount']>0 && $r['hide_discount']==0){
                    $html .= "<p><strong class='text-success'>Discount: RM".$r['discount']."</strong></p>";
                }      
                $html .= "<div class='unique-heavy-heavy-bike' style='margin-top:20px;'>".stripslashes($r['description'])."</div>";
                if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    $html .= "<p class='text-center'><a href='".$r['buy_link']."' class='btn btn-sm btn-warning'>BUY NOW</a></p>";
                }
				
				
				 
                $html .= "</div></div>";
			}if($r['post_type'] === 3){
				$html .= '<input type="hidden" value="'.$r['post_type'].'"><input type="hidden" value="'.$share_url.'"><div id="social_'.$r['pp_id'].'" class="col-md-12 thumbnail" style="margin-bottom: 15px; border-radius: 0; padding: 10px;">';
                $html .= '<h4 class="">'.$r['name'].'</h4>';$html .= '<p class="" style="margin: 0 3px 10px;font-size: 11px;">'.$this->timeago($r['date_created']).' <i class="fa fa-clock-o"></i> </p>';
                if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    $html .= '<img id='.$r['pp_id'].' class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$r['photo'].'" alt="'.$r['name'].'"  onclick="var id = $(this).attr(&quot;id&quot;);$(&quot;#products_&quot;+id).slideToggle(500);" style="cursor:pointer;"/>';
                } else {
                    $html .= '<img id='.$r['pp_id'].' class="img-responsive" src="/images/uploads/'.$member_result['dir_name'].'/500X500/'.$r['photo'].'" alt="'.$r['name'].'" style="cursor:pointer;" onclick="var id = $(this).attr(&quot;id&quot;);$(&quot;#products_&quot;+id).slideToggle(500);"/>'; 
                }
				if($r['price']>0 && $r['hide_price']==0){
                    $html .= "<p style='margin-top: 10px;'><strong class='text-success'>Price: RM".$r['price']."</strong></p>";
                } 
                $html .= '<div id="products_'.$r['pp_id'].'" class="products_details" style="margin-top:10px;">
                        <a href="tel:'.$contact_number.'" class="btn btn-info btn-call"  style="float:left;margin-left: 2px;"><span class="fa fa-phone"></span></a>
                        
                        <div class="dropdown" style="float:left;margin-left: 2px;">
                           <button type ="button" class ="btn btn-success dropdown-toggle" id="whatsapp-menu" data-toggle="dropdown">
                              <span class="fa fa-whatsapp"></span> <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" role="menu" aria-labelledby="whatsapp-menu">                            
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=callto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Call '.$wap_number.'</a></li>
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Message</a></li>
                            <li role="presentation"><a role="menuitem" href="whatsapp://send?text='.$share_url.'" data-action="share/whatsapp/share">Share Page</a></li>
                           </ul>
                        </div>
                       
                        <span style="margin-left: 2px;"  style="float:left;">
                        <a onclick="window.open(this.href, '."'newwindow'".', '."'width=680, height=460'".'); return false;" href="https://www.facebook.com/sharer/sharer.php?u=http://pageiz.com/pages/product-page?q='.$r['pp_id'].'|'.$member_id.'" class="btn btn-primary"><span class="fa fa-facebook"><span></a>
                        </span>';
                
                if($r['discount']>0 && $r['hide_discount']==0){
                    $html .= "<p><strong class='text-success'>Discount: RM".$r['discount']."</strong></p>";
                }      
                $html .= "<div class='unique-heavy-heavy-bike' style='margin-top:20px;'>".stripslashes($r['description'])."</div>";
                // if(isset($r['buy_link']) && (!empty($r['buy_link']))){
                    // $html .= "<p class='text-center'><a href='".$r['buy_link']."' class='btn btn-sm btn-warning'>BUY NOW</a></p>";
                // }
				
                $html .= "</div></div>";
			}
			elseif($r['post_type'] == 2){
				$html .= '<input type="hidden" value="'.$share_url.'"><div id="social_'.$r['pp_id'].'" class="col-md-12 thumbnail" style="margin-bottom: 15px; border-radius: 0; padding: 10px;">';
                $html .= '<p class="" style="margin: 0 3px 10px;font-size: 11px;">'.$this->timeago($r['date_created']).' <i class="fa fa-clock-o"></i> </p>';
                
                $html .= '<div id="products_'.$r['pp_id'].'" class="details" style="margin-top:10px;">
                        <a href="tel:'.$contact_number.'" class="btn btn-info btn-call"  style="float:left;margin-left: 2px;"><span class="fa fa-phone"></span></a>
                        
                        <div class="dropdown" style="float:left;margin-left: 2px;">
                           <button type ="button" class ="btn btn-success dropdown-toggle" id="whatsapp-menu" data-toggle="dropdown">
                              <span class="fa fa-whatsapp"></span> <span class="caret"></span>
                           </button>
                           <ul class="dropdown-menu" role="menu" aria-labelledby="whatsapp-menu">                            
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=callto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Call '.$wap_number.'</a></li>
                            <li role="presentation"><a role="menuitem" href="intent:'.$wap_number.'#Intent;scheme=smsto;package=com.whatsapp;action=android.intent.action.SENDTO;end" data-action="share/whatsapp/share">Message</a></li>
                            <li role="presentation"><a role="menuitem" href="whatsapp://send?text='.$share_url.'" data-action="share/whatsapp/share">Share Page</a></li>
                           </ul>
                        </div>
                       
                        <span style="margin-left: 2px;"  style="float:left;">
                        <a onclick="window.open(this.href, '."'newwindow'".', '."'width=680, height=460'".'); return false;" href="https://www.facebook.com/sharer/sharer.php?u=http://pageiz.com/pages/product-page?q='.$r['pp_id'].'|'.$member_id.'" class="btn btn-primary"><span class="fa fa-facebook"><span></a>
                        </span>';
                    
                $html .= "<div class='unique-heavy-heavy-bike' style='margin-top:20px;'>".stripslashes($r['description'])."</div>";
                
                $html .= "</div></div>";
			}
                
				
				
            }
			$html .= "</div>";
		}
            
            
        $data = array('status'=>'success', 'data'=> $html);
        
        echo json_encode($data);
    }
	
    public function downloadPdfAction()
    {
        $file = $this->getRequest()->getParam('file');
        $member_id = $this->getRequest()->getParam('id');
        
        $members = new Application_Model_Members();
        $member_dir = $members->getMemberDirectory($member_id); 
        $dir_path = SYSTEM_PATH . "/images/uploads/".$member_dir['dir_name'];
        
        $filename = $dir_path.'/'.$file;

        //Set the time out
        set_time_limit(0);

        //Call the download function with file path,file name and file type
        $this->download_file($filename, ''.$file.'', 'application/pdf');
    }
    public function reload($url)
		{
			$graph = 'https://graph.facebook.com/';
			$post = 'id='.urlencode($url).'&scrape=true';
			return $this->send_post($graph, $post);
		}

		private function send_post($url, $post)
		{
			$r = curl_init();
			curl_setopt($r, CURLOPT_URL, $url);
			curl_setopt($r, CURLOPT_POST, 1);
			curl_setopt($r, CURLOPT_POSTFIELDS, $post);
			curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($r, CURLOPT_CONNECTTIMEOUT, 5);
			$data = curl_exec($r);
			curl_close($r);
			return $data;
		}
    private function download_file($file, $name, $mime_type='')
    {
         /*
         This function takes a path to a file to output ($file),  the filename that the browser will see ($name) and  the MIME type of the file ($mime_type, optional).
         */
         
         //Check the file premission
         if(!is_readable($file)) die('File not found or inaccessible!');
         
         $size = filesize($file);
         $name = rawurldecode($name);
         
         /* Figure out the MIME type | Check in array */
         $known_mime_types=array(
         	"pdf" => "application/pdf",
         	"txt" => "text/plain",
         	"html" => "text/html",
         	"htm" => "text/html",
        	"exe" => "application/octet-stream",
        	"zip" => "application/zip",
        	"doc" => "application/msword",
        	"xls" => "application/vnd.ms-excel",
        	"ppt" => "application/vnd.ms-powerpoint",
        	"gif" => "image/gif",
        	"png" => "image/png",
        	"jpeg"=> "image/jpg",
        	"jpg" =>  "image/jpg",
        	"php" => "text/plain"
         );
         
         if($mime_type==''){
        	 $file_extension = strtolower(substr(strrchr($file,"."),1));
        	 if(array_key_exists($file_extension, $known_mime_types)){
        		$mime_type=$known_mime_types[$file_extension];
        	 } else {
        		$mime_type="application/force-download";
        	 };
         };
         
         //turn off output buffering to decrease cpu usage
         @ob_end_clean(); 
         
         // required for IE, otherwise Content-Disposition may be ignored
         if(ini_get('zlib.output_compression'))
          ini_set('zlib.output_compression', 'Off');
         
         header('Content-Type: ' . $mime_type);
         header('Content-Disposition: attachment; filename="'.$name.'"');
         header("Content-Transfer-Encoding: binary");
         header('Accept-Ranges: bytes');
         
         /* The three lines below basically make the 
            download non-cacheable */
         header("Cache-control: private");
         header('Pragma: private');
         header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
         
         // multipart-download and download resuming support
         if(isset($_SERVER['HTTP_RANGE']))
         {
        	list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
        	list($range) = explode(",",$range,2);
        	list($range, $range_end) = explode("-", $range);
        	$range=intval($range);
        	if(!$range_end) {
        		$range_end=$size-1;
        	} else {
        		$range_end=intval($range_end);
        	}
        	
        	$new_length = $range_end-$range+1;
        	header("HTTP/1.1 206 Partial Content");
        	header("Content-Length: $new_length");
        	header("Content-Range: bytes $range-$range_end/$size");
         } else {
        	$new_length=$size;
        	header("Content-Length: ".$size);
         }
         
         /* Will output the file itself */
         $chunksize = 1*(1024*1024); //you may want to change this
         $bytes_send = 0;
         if ($file = fopen($file, 'r'))
         {
        	if(isset($_SERVER['HTTP_RANGE']))
        	fseek($file, $range);
         
        	while(!feof($file) && 
        		(!connection_aborted()) && 
        		($bytes_send<$new_length)
        	      )
        	{
        		$buffer = fread($file, $chunksize);
        		print($buffer); //echo($buffer); // can also possible
        		flush();
        		$bytes_send += strlen($buffer);
        	}
         fclose($file);
         } else
         //If no permissiion
         die('Error - can not open file.');
         //die
        die();
        
      } // download function end
	
	public function savePageCommentsAction()
    {
        $this->ajaxed();
        
       $name = $this->getRequest()->getPost('name');
       $email = $this->getRequest()->getPost('email');
       $comment = $this->getRequest()->getPost('comment');
       $page_id = $this->getRequest()->getPost('page_id');
        
        $data = array('name' => $name,
				'email' => $email,
				'comment' => $comment,
				'page_id' => $page_id,
				'comment_date' => date('Y-m-d H:i:s')
				); 

		$result = $this->comment->addComment($data);
        
        if($result)
        {
            echo 'success';
        }
        else{
            echo 'error';
        }       
    }
	
	
		  // page not found page
	public function pageNotFoundAction(){
	
    	/*for text blocks*/
    	/*$text_block =  new Application_Model_TextBlocks();
    	$this->view->text_block = $text_block->getAllTextBlocks();*/	
    		/*for image blocks*/
    	/*$image_block =  new Application_Model_ImageBlocks();
    	$this->view->image_block = $image_block->getAllImageBlocks();*/
	}
	
		public function pageBySlugAction(){
			/*for text blocks*/
	$text_block =  new Application_Model_TextBlocks();
	$this->view->text_block = $text_block->getAllTextBlocks();	
		/*for image blocks*/
	$image_block =  new Application_Model_ImageBlocks();
	$this->view->image_block = $image_block->getAllImageBlocks();
			}
	
    public function saveLikeAction()
    {
        $this->ajaxed();
        
        $page_id = $this->getRequest()->getParam('page_id');
        
        $like_page_model = new Application_Model_LikedPages();
        $result = $like_page_model->add($page_id, $this->member_session->member_id);
        
        if($result)
        {
            $page = $this->page->getPageLikes($page_id);
            $this->page->setPageLikes($page_id, ($page['likes']+1));
            echo 'success';
        } else {
            echo 'error';
        }
    } // save like function end
    
    public function registrationAjaxAction()
    {
        $this->ajaxed();
        
        $members_model = new Application_Model_Members();
        
        $formData = $this->_request->getPost();
        
        $result = array();
        $is_exist = $members_model->checkEmail($formData['email']);
        if ($is_exist)
        {
            $result = array('status'=>'warning', 'msg'=>"<div class='alert alert-warning'>" . $formData['email'] ." is already exists.</div>");
            echo json_encode($result);
        } else {
            
            $member_pwd = rand(111111, 99999999);
            $formData['pwd'] = md5($member_pwd);
            $formData['parent_id'] = 0;
            $formData['role_id'] = 1;            
            $formData['root_id'] = 0;

            $verification_code = $this->getRandomString();
            $formData['verification_code'] = $verification_code;
            
            $redirect_to = $formData['redirect_to'];
            unset($formData['redirect_to']);
            
            $member_id = $members_model->add($formData);
            if (isset($member_id))
            {
                // create pages master record
                //$master_page = new Application_Model_MemberPagesMaster();
                //$master_data = array('member_id'=>$member_id, 'status'=>'FREE', 'pages'=>1,'price'=>0, 'page_status'=>'OFFLINE');
                //$mster_page_id = $master_page->add($master_data);
                
                //$this->page->addMemberPage(array('master_p_id'=>$mster_page_id,'member_id'=>$member_id));
            
                //$transactions = new Application_Model_Transactions();
                
                require (realpath(dirname(__file__) . '/../../..') .'/library/Zend/Mail/Transport/Smtp.php');
                $config = new Zend_Mail_Transport_Smtp('smtp.gmail.com', array(
                    'auth' => 'login',
                    'username' => 'colinkr.test@gmail.com',
                    'password' => 'colinkr123',
                    'port' => '587',
                    'ssl' => 'tls'));
                Zend_Mail::setDefaultTransport($config);

                $subject = "Member Registration";
                $body = '
                     <!DOCTYPE html>
                     <html>
                      <head>
                       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                       <title>Member Registration</title>
                       <style type="text/css">
                            body {margin: 0; padding: 0; min-width: 100%!important;}
                       </style>
                      </head>
                      <body><table>
                        <tr><td align="left"><img style="width:200px;" src="http://netefct.com/images/logo.png"/><td></tr>
                       <tr><td>Hello ' . $formData['first_name'] . ' ' . $formData['last_name'] .
                        ',<br/><br/>Thank You For Joinging Us.<br/>' .
                        'Your registration is currently inactive.<br/>' .
                        '<a href="http://netefct.com/index/do-verification/code/'.$verification_code.'/ref/'.$redirect_to.'" target="_blank">Click here to complete the registration process</a>' .
                        '<br/><br/>Best Regards<br/><Strong>netefct.com</Strong>
                      </td><tr>
                     </table>
                      </body>
                     </html>';

                //$subscribers = array($formData['first_name']=>$formData['email']); //'mussawir'=>'mussawir20@gmail.com'
                $mail = new Zend_Mail();
                $mail->setFrom('noreply@netefct.com', 'netefct.com');
                $mail->addTo(trim($formData['email']), $formData['first_name']);
                $mail->setSubject($subject);
                $mail->setBodyHtml($body);
                $mail->send();
                
                $result = array('status'=>'success');
                echo json_encode($result);   
            } else {
                $result = array('status'=>'error');
                echo json_encode($result);
            }
        }
        
    } // registraiton funcito end
    
    private function getRandomString($length=20) 
    {
        $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
        $randomStr='';
        for($i=0; $i < $length; $i++) {
            $randomStr .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $randomStr;
    }
    
    public function ajaxed() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        if (!$this->_request->isXmlHttpRequest()){
		  $this->_redirect('index');	
			return; // if not a ajax request leave function
		}
    }
	
	public function Paginator($results) {
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
    }
	
	private function get_url()
   {
      return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['REQUEST_URI']
      );
    }
    
    public function __call($method,$args)
    {
        $url = $this->_request->getRequestUri();
     
        $param = substr($url,(strrpos($url,'/') + 1));
     
        //Forward to the controller
        $this->_forward('index','pages','default', array($param));
    }
	
	/*public function __call($method,$args)
	{
		/*for text blocks*
	$text_block =  new Application_Model_TextBlocks();
	$this->view->text_block = $text_block->getAllTextBlocks();	
		/*for image blocks*
	$image_block =  new Application_Model_ImageBlocks();
	$this->view->image_block = $image_block->getAllImageBlocks();

  if ('Action' == substr($method, -6)) {
            // If the action method was not found, render the error template
            $url = $this->get_url();
			$slug = parse_url($url);
			$url_slug = $slug['path'];
			
            $page =  substr($url_slug ,strrpos($url_slug, '/') + 1);
            //is this page name is present in any url slug
    //var_dump($page);return;
		$results = $this->page->getPageByUrl(strtolower($page));
           
        if($results == true){

    		//$this->view->page_data = $results;
            $this->view->list = $results;
    		$result = $this->comment->getCommentsByPage($results->page_id);
    		$this->view->comment = $result;
    		$form = new Application_Form_CommentForm();
    		$this->view->form = $form;

            return $this->render('index');
    	   }
           else{
                return $this->render('page-not-found');
        }
        }

        // all other methods throw an exception
        throw new Exception('Invalid method "'
                            . $method
                            . '" called',
                            500);
    }*/ // call function end
		
} //class end