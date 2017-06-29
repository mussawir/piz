<?php
 
class Application_Model_Pages extends Zend_Db_Table
{ 
    protected $_name = 'pages';
    protected $_primary = 'page_id';
    protected $result = null;
  
 
  public function getPageByID($id){
	 $select = $this->select();
	 $select->from($this)->where("page_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
  }
  
 public function authorization($page_id, $member_id){
	 $select = $this->select();
	 $select->from($this)->where("page_id = ?", $page_id)->where("member_id = ?", $member_id);
	 $result = $this->fetchRow($select);
	 if(count($result) > 0 ){
		return true; 
	 }else{
		 return false;
	 }
 }
 
  // add new page
  public function addPage($formData) {
  $data = array(
				 'banner_link' => $formData['banner_link'],
				'url_slug' => $formData['url_slug'],
				'user_id' => $formData['user_id'],
				'date_published' => $formData['date_published'],
				'is_in_draft' => $formData['is_in_draft'],
				'date_created' => $formData['date_created'],
				'title' => $formData['title'],
				'contents' => $formData['contents'],
				'tags' => $formData['tags'],
			    'keyword_content' => $formData['keyword_content'],
                'description_content' => $formData['description_content'],
                'draft_content' => $formData['draft_content'],
                'page_for' => $formData['page_for']);
				 
 $result = $this->insert($data); 
	return $result;
   }
  
 // add draft page
    public function addDraftPage($formData){
        $data = array('user_id' => $formData['user_id'],
            'url_slug' => $formData['url_slug'],
            'is_in_draft' => $formData['is_in_draft'],
            'date_created' => $formData['date_created'],
            'title' => $formData['title'],
            'tags' => $formData['tags'],
            'contents' => $formData['contents'],
			'keyword_content' => $formData['keyword_content'],
            'description_content' => $formData['description_content'],
            'draft_content' => $formData['draft_content'],
            'page_for' => $formData['page_for']);

        $result = $this->insert($data);
      return $result;
    }
 
	public function getPageByUrl($page)
    {
        $select = $this->select();
        $select->from($this)->where("is_in_draft = 0")->where('is_blocked = 0')->where("url_slug = ?", $page);
        $result = $this->fetchRow($select);
        if($result){
		$views = $result->views + 1;
		$data = array('views' => $views);
 $where = $this->getAdapter()->quoteInto('page_id = ?', $result->page_id);
 $this->update($data, $where);
		}
					
		return $result;
			
    }

	// check for slug name from db for new page
	public function checkPageSlug($slug){
	$select = $this->select();
	$select->from($this)->where('url_slug = ?', $slug);
	$result = $this->fetchRow($select);
	return $result;
	}
	// check for slug name from db for edit
	public function checkPagesSlug($slug,$id){
	$select = $this->select();
	$select->from($this)->where('url_slug = ?', $slug)->where('page_id != ?', $id);
	$result = $this->fetchRow($select);
	return $result;
	}
 
  // for get all published post
    public function getAllPages($db)
    {
        $select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), '*')->order('p.page_id DESC')
        ->join(array('u' => 'users'),'u.user_id = p.user_id',array('u.user_name'));
    	 
        $result = $db->query($select)->fetchAll();        
    	return $result;
    }

    // for get all draft post
    public function getAllDraftPages($db)
    {
    	$select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), '*')->where("p.is_in_draft = 1")->order('p.page_id DESC')
        ->join(array('u' => 'users'),'u.user_id = p.user_id',array('u.user_name'));
    	$result = $db->query($select)->fetchAll();
    	return $result;
      }

	 //this function is used for finding page from admin page list
    public function findPage($name)
    {
        $select = $this->select();
        $select->from($this)->where("title like ? ", "%" . $name . "%")->
            orWhere("date_published like ? ", "%" . $name . "%");
        $result = $this->fetchAll($select);
        return $result;
    }
 
	// for update post
    public function updatePage($formData)
    {

	$data = array(
		    "banner_link" => $formData['banner_link'],
            "title" => $formData['title'],
            "tags" => $formData['tags'],
            "url_slug" => $formData['url_slug'],
            "is_in_draft" => $formData['is_in_draft'],
            "date_published" => $formData['date_published'],
            "contents" => $formData['contents'],
            'keyword_content' => $formData['keyword_content'],
                'description_content' => $formData['description_content'],
                'is_comment' => ($formData['is_comment']=="0" ? 0 : 1),
            'draft_content' => $formData['draft_content'],
            'page_for' => $formData['page_for']);
            
        $where = "page_id = " . (int)$formData["page_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Page Updated Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error occur. Please try again</div>";
        }
    }
	public function updateAdminMemberPage($formData,$page_id)
    {

	$expiry_date = $formData['expiry_date'];
        $expiry_date = date('Y/m/d', strtotime("+1 months", strtotime($expiry_date)));
        $data = array('date_created' => date('Y-m-d H:i:s'),
                'member_id' =>   $formData['member_id'],
				'marketer_id' =>   $formData['marketer_id'],
                'expiry_date' => $expiry_date,
				'video_hidden'=>  $formData['video_hidden'],
				'slider_hidden'=> $formData['slider_hidden'],
				'form_hidden'=>  $formData['inpage'],
				'popup_hidden'=> $formData['pop'],
				'total_posts' => $formData['total_posts'],
				'notes' => $formData['notes']
				);
            
        $where = "page_id = " . (int)$page_id; 
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Page Updated Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error occur. Please try again</div>";
        }
    }
		// Create URL for new page 
    public function newPageURL($formData)
    {
	$data = array(
		    "url_slug" => $formData['url_slug'],
            "page_type" => $formData['page_type'],
            "categories" => $formData['categories']
			);
            
        $where = "page_id = " . (int)$formData["page_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return true;
        } else
        {
            return false;
        }
    }

    // for update draft post
    public function updateDraftPage($formData)
    {
        $data = array(
            "title" => $formData['title'],
            "tags" => $formData['tags'],
            "url_slug" => $formData['url_slug'],
            "is_in_draft" => $formData['is_in_draft'],
            "contents" => $formData['contents'],
			'keyword_content' => $formData['keyword_content'],
            'description_content' => $formData['description_content'],
            'draft_content' => $formData['draft_content'],
            'page_for' => $formData['page_for']);
            
        $where = "page_id = " . (int)$formData["page_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Page Updated and Save in Draft Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error occur. Please try again</div>";
        }
    }

	// get page by slug
	 public function getPostByUrl($page)
    {
        $select = $this->select();
        $select->from($this)->where("url_slug = ?", $page);
        $result = $this->fetchRow($select);
        return $result;
    }

	   //for delete page
  public function deletePage($id){
        $where = "page_id = " . (int) $id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }
 }
	
    // for remove page
    public function removePage($db, $id)
    {
        //$rowset = $this->fetchAll();
        //$rowCount = count($rowset);
        //if ($rowCount < 2 || $rowCount == 1) return 3;

        $id = $this->delete($db->quoteInto("page_id = ?", $id));
        if ($id > 0)
        {
            return 1;
        } else
        {
            return 2;
        }
    }
	
	public function getLastInsertedRecord()
    {
    $select = $this->select();
    $select->from($this)->where("is_in_draft = 0")->order('page_id DESC');
    $result = $this->fetchRow($select);
    return $result;
    }


	// Update url slug
    public function updateUrlSlug($url, $id)
    {
        $where = "page_id = " . (int)$id;
		
        $this->id = $this->update(array("url_slug" => $url), $where);

        if ($this->id)
        {
            return true;
        } else
        {
            return false;
        }
    }
    
    public function getDraftedPages()
    {
        $select = $this->select();
    	$select->from($this)->where("is_in_draft = 1");
    	$result = $this->fetchAll($select);
    	return count($result);
    }
    
    // pages for memeber
    public function addMemberPage($formData) 
    {
        $expiry_date = date('Y/m/d', strtotime('+1 year'));
        $expiry_date = date('Y/m/d', strtotime("+1 months", strtotime($expiry_date)));
        $data = array('date_created' => date('Y-m-d H:i:s'),
                'master_p_id' => $formData['master_p_id'],
                'member_id' => $formData['member_id'],
                'expiry_date' => $expiry_date,
                'is_member_pg' => 1,
                'is_in_draft' => 1);
				 
        $result = $this->insert($data); 
        return $result;
    }
    public function addAdminMemberPage($formData) 
    {
		$expiry_date = $formData['expiry_date'];
        $expiry_date = date('Y/m/d', strtotime("+1 months", strtotime($expiry_date)));
        $data = array('date_created' => date('Y-m-d H:i:s'),
                'master_p_id' => $formData['master_p_id'],
                'member_id' =>   $formData['member_id'],
				'marketer_id' =>   $formData['marketer_id'],
                'expiry_date' => $expiry_date,
                'is_member_pg' => 1,
                'is_in_draft' =>  1,
				'video_hidden'=>  $formData['video_hidden'],
				'slider_hidden'=> $formData['slider_hidden'],
				'popup_hidden'=> $formData['pop'],
				'form_hidden'=> $formData['inpage'],
				'total_posts' => $formData['total_posts'],
				'notes' => $formData['notes']
				);
				 
        $result = $this->insert($data); 
        return $result;
    }
    public function assingPages($formData) 
    {
        $result = $this->insert($formData); 
        return $result;
    }
    
    public function getPageByMaster($master_p_id)
    {
        $select = $this->select();
    	$select->from($this)->where("master_p_id = ?", $master_p_id);
    	$result = $this->fetchAll($select);
    	return $result;
    }
   
    public function getPagesByMember($member_id)
    {
        $select = $this->select();
    	$select->from($this)->where("member_id = ?", $member_id);
    	$result = $this->fetchAll($select);
    	return $result;
    }
    
    public function getMemberPageCount($member_id) 
    {
        $select = $this->select();
        $select->from($this, array('count(*) as page_id'))->where("member_id = ?", $member_id);
        $rows = $this->fetchAll($select);
        
        return($rows[0]->page_id);        
    }

    public function getMemberPages($member_id, $order_by='title')
    {
        $select = $this->select();
    	$select->from($this)->where("member_id = ?", $member_id)
        ->where("is_member_pg = ?", 1)
        ->where("date_published IS NOT NULL")
        ->where("is_blocked = ?", 0)
        ->order($order_by.' asc');
    	$result = $this->fetchAll($select);
    	return $result;
    }
    
    public function getMemberPageList($member_id, $exclude_page_id)
    {
        $select = $this->select();
    	$select->from($this)->where("member_id = ?", $member_id)
        ->where("is_member_pg = ?", 1)
        ->where("date_published IS NOT NULL")
        ->where("is_blocked = ?", 0)
        ->where('page_id != ?', $exclude_page_id);
    	$result = $this->fetchAll($select);
    	return $result;
    }
	public function updateLink($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('video_link'=> $link), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
	public function updateplace($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('form_place'=> $link), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
	public function updatePic($page_id, $link,$colName)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array($colName=> $link), $where);
		$this->id = $this->update(array('slide_enabled'=> 1), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
	
	public function turnOn($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('content_hidden'=> $link), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
	public function turnOff($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('content_hidden'=> $link), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
	public function turnOffAds($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('ads_hidden'=> $link), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
	public function turnOnAds($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('ads_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOnVids($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('video_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOffVids($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('video_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOnSlide($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('slider_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOffSlide($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('slider_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOnfeedback($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('feedback_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOfffeedback($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('feedback_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOnForm($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('form_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOffForm($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('form_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOnBanner($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('banner_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function turnOffBanner($page_id, $link)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('banner_hidden'=> $link), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
	public function pinPost($page_id, $post_id)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('pinned_post'=> $post_id), $where);

        if ($this->id)
        { 
            return true;
        } else {
            return false;
        }
    }
    public function updateBanner($page_id, $file_name)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('banner_img'=> $file_name), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
	
	public function updateAdImage($page_id, $file_name)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('ad_img'=> $file_name), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
    
    // update member page
    public function updateMemberPage($formData)
    {
        $data = array(
		"banner_link" => $formData['banner_link'],
            "title" => $formData['title'],
            "tags" => $formData['tags'],
            "url_slug" => $formData['url_slug'],
            "is_in_draft" => $formData['is_in_draft'],
            "date_published" => $formData['date_published'],
            "contents" => $formData['contents'],
            'keyword_content' => $formData['keyword_content'],
                'description_content' => $formData['description_content'],
                'is_comment' => ($formData['is_comment']=="0" ? 0 : 1),
            'draft_content' => $formData['draft_content'],
            'business_name' => $formData['business_name'],
            'contact_number' => $formData['contact_number'],
            'page_description' => $formData['page_description'],
            'categories' => isset($formData['categories'])?$formData['categories']:0,
            'address' => $formData['address'],
            'is_featured' => $formData['is_featured'],
            'country_id' => $formData['country_id'],
            'state_id' => $formData['state_id'],
            'city_id' => $formData['city_id'],
            'pc_id' => $formData['pc_id'],
            'country_name' => $formData['country_name'],
            'state_name' => $formData['state_name'],
            'city_name' => $formData['city_name'],
            'postcode' => $formData['postcode'],
			'dir_name' => $formData['dir_name'],
            'wap_number' => $formData['wap_number'],
            'wechat_number' => $formData['wechat_number']
			);
			
            
        $where = "page_id = " . (int)$formData["page_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Page Updated Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error occur. Please try again</div>";
        }
    }
    
    public function updateMemberDraftPage($formData)
    {
        $data = array(
            "title" => $formData['title'],
            "tags" => $formData['tags'],
            "url_slug" => $formData['url_slug'],
            "is_in_draft" => $formData['is_in_draft'],
            "contents" => $formData['contents'],
			'keyword_content' => $formData['keyword_content'],
            'description_content' => $formData['description_content'],
            'draft_content' => $formData['draft_content'],
            'business_name' => $formData['business_name'],
            'contact_number' => $formData['contact_number'],
            'area_serviced' => $formData['area_serviced'],
            'page_description' => $formData['page_description'],
            'categories' => $formData['categories'],
            //'page_type' => $formData['page_type'],
            'address' => $formData['address'],
            'is_featured' => $formData['is_featured'],
            'country_id' => $formData['country_id'],
            'state_id' => $formData['state_id'],
            'city_id' => $formData['city_id'],
            'pc_id' => $formData['pc_id'],
            'country_name' => $formData['country_name'],
            'state_name' => $formData['state_name'],
            'city_name' => $formData['city_name'],
            'postcode' => $formData['postcode'],
            'wap_number' => $formData['wap_number'],
            'wechat_number' => $formData['wechat_number']);
            
        $where = "page_id = " . (int)$formData["page_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Page Updated and Save in Draft Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error occur. Please try again</div>";
        }
    }
    
    public function updatePageMember($page_id, $member_id)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('member_id'=> $member_id), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
    
    public function getPageBanner($page_id){
        $select = $this->select();
        $select->from($this, array('page_id','banner_img'))->where('page_id = ?',$page_id);
        $result = $this->fetchRow($select);
        return $result;
    }
    
    // for admin section
    public function getAllMemberPages($db)
    {
        $select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), '*')
        ->join(array('m' => 'members'),'p.member_id = m.member_id',array('m.first_name', 'm.last_name'))
        ->join(array('c' => 'page_categories'),'c.category_id = p.categories', array('c.category_name'))
   	    ->where("is_member_pg = ?", 1);
        $result = $db->query($select)->fetchAll();        
    	return $result;
    }
    
	
	 // for admin section get all member pages without category
    public function getAllMemberPagesWC($db)
    {
        $select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), '*')
        ->join(array('m' => 'members'),'p.member_id = m.member_id',array('m.first_name', 'm.last_name'))
        ->where("is_member_pg = ?", 1);
        $result = $db->query($select)->fetchAll();        
    	return $result;
    }
    // search pages
    public function getPageSearch($db, $query)
    {
        $select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), '*')
        ->where("p.is_in_draft = 0")->where("p.title LIKE ? ", "%" . trim($query) . "%");
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function getBusinessSearch($db, $query, $state, $city)
    {
        $select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), '*')
        ->where("p.is_in_draft = 0")
		->where("p.is_blocked = 0")
        ->where("p.is_member_pg = ?", 1)
        ->where("p.title LIKE ? ", trim($query) . "%")
        ->orWhere("p.state_id = ?", $state)
        ->orWhere("p.city_id = ?", $city);
        
        //$sql = $select->__toString();echo "$sql\n";return;
        
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function searchByCountry($db, $query)
    {
    	$stmt = $db->query("SELECT * FROM pages where is_member_pg = 1 and is_in_draft = 0 and is_blocked = 0
        and (url_slug COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%' 
        OR business_name COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR contact_number COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%') ");
    	//$stmt->setFetchMode(Zend_Db::FETCH_NUM);
        $rows = $stmt->fetchAll();
        return $rows;
  }
    
    public function searchByState($db, $query, $state)
    {
        $stmt = $db->query("SELECT * FROM pages where is_member_pg = 1 and is_in_draft = 0 and is_blocked = 0 and state_id = ".$state."
        and (title COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%' 
        OR url_slug COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%' 
        OR contents COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR business_name COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR page_description COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR tags COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR keyword_content COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR contact_number COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%') ");
    	//$stmt->setFetchMode(Zend_Db::FETCH_NUM);
        $rows = $stmt->fetchAll();
        return $rows ;
    }
    
    public function searchByStateCity($db, $query, $state, $city)
    {
        $stmt = $db->query("SELECT * FROM pages where is_member_pg = 1 and is_in_draft = 0 and is_blocked = 0 and state_id = ".$state." and city_id = ".$city."
        and (title COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%' 
        OR url_slug COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%' 
        OR contents COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR business_name COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR page_description COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR tags COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR keyword_content COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%'
        OR contact_number COLLATE UTF8_GENERAL_CI LIKE '%". $query ."%') ");
    	//$stmt->setFetchMode(Zend_Db::FETCH_NUM);
        $rows = $stmt->fetchAll();
        return $rows ;
    }
    
    public function searchCategoryByCountry($db, $category)
    {
        $select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), array('p.title', 'p.url_slug','p.is_blocked', 'p.banner_img', 'page_description'))
        ->join(array('m' => 'members'),'p.member_id = m.member_id',array('m.member_id', 'm.dir_name'))
        ->where("p.is_in_draft = 0")
		->where("p.is_blocked = 0")
        ->where("p.is_member_pg = ?", 1)
        ->where("p.country_id = ?", 1)
        ->where("p.categories = ? ", $category)
        ->order('p.title ASC');
        
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function searchCategoryByState($db, $category, $state)
    {
        $select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), array('p.title', 'p.url_slug','p.is_blocked', 'p.banner_img', 'page_description'))
        ->join(array('m' => 'members'),'p.member_id = m.member_id',array('m.member_id', 'm.dir_name'))
        ->where("p.is_in_draft = 0")
		->where("p.is_blocked = 0")
        ->where("p.is_member_pg = ?", 1)
        ->where("p.country_id = ?", 1)
        ->where("p.state_id = ?", $state)
        ->where("p.categories = ? ", $category)
        ->order('p.title ASC');
        
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function searchCategoryByStateCity($db, $category, $state, $city)
    {
        $select = new Zend_Db_Select($db);
    	$select->from(array('p' => 'pages'), array('p.title', 'p.url_slug','p.is_blocked', 'p.banner_img', 'page_description'))
        ->join(array('m' => 'members'),'p.member_id = m.member_id',array('m.member_id', 'm.dir_name'))
        ->where("p.is_in_draft = 0")
		->where("p.is_blocked = 0")
        ->where("p.is_member_pg = ?", 1)
        ->where("p.country_id = ?", 1)
        ->where("p.state_id = ?", $state)
        ->where("p.city_id = ?", $city)
        ->where("p.categories = ? ", $category)
        ->order('p.title ASC');
        
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function getMembersAds($page_id, $member_id)
    {
        $select = $this->select();
    	$select->from($this, array('title','ad_img', 'page_id'))
        ->where("member_id = ?", $member_id)
        ->where("page_id != ?", $page_id);//$sql = $select->__toString();echo "$sql\n";return;
    	$result = $this->fetchAll($select);
    	return $result;
    }
    
    public function getPageComments($page_id)
    {
        $select = $this->select();
    	$select->from($this, array('comments', 'page_id'))->where("page_id = ?", $page_id);
    	$result = $this->fetchRow($select);
    	return $result;
    }
    
    public function setPageComment($page_id, $comments)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('comments'=> $comments), $where);

        if ($this->id) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getPageLikes($page_id)
    {
        $select = $this->select();
    	$select->from($this, array('likes', 'page_id'))->where("page_id = ?", $page_id);
    	$result = $this->fetchRow($select);
    	return $result;
    }
    
    public function setPageLikes($page_id, $likes)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('likes'=> $likes), $where);

        if ($this->id) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getMemberPageAds($db, $page_id, $member_id)
    {
        $select = new Zend_Db_Select($db);
        $select->from(array('p' => 'pages'), 'p.title')
        ->join(array('mpa' => 'member_page_ads'),'mpa.main_page_id = p.page_id', 'mpa.member_id=p.member_id',array('mpa.main_page_id', 'mpa.member_id', 'mpa.box_page_id', 'mpa.sort_order'))
        ->where('p.page_id = ?',$page_id)->where('p.member_id = ?',$member_id)
        ->order('mpa.sort_order ASC');//$sql = $select->__toString();echo "$sql\n";return;
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function getAdsByCategory($db, $category_id, $exclude_page_id)
    {
        $select = new Zend_Db_Select($db);
        $select->from(array('p' => 'pages'), array('p.page_id' ,'p.title', 'p.url_slug', 'p.banner_img', 'p.is_blocked', 'p.page_description'))
        ->join(array('m' => 'members'),'p.member_id = m.member_id',array('m.member_id', 'm.dir_name'))
        ->where('p.categories = ?', $category_id)
        ->where('p.page_id != ?',$exclude_page_id)
        ->where('p.is_blocked = 0')
        ->order('RAND()')
        ->limit(6);
        //$sql = $select->__toString();echo "$sql\n";return;
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function getAdsByMemberPages($db, $member_id, $exclude_page_id)
    {
        $select = new Zend_Db_Select($db);
        $select->from(array('p' => 'pages'), array('p.page_id' ,'p.title', 'p.url_slug', 'p.banner_img', 'p.is_blocked', 'p.page_description', 'p.dir_name'))
        ->where('p.page_id != ?',$exclude_page_id)
        ->where('p.member_id = ?', $member_id )
        ->where('p.is_blocked = 0')
        ->where('p.is_hidden = 0');
        //$sql = $select->__toString();echo "$sql\n";return;
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function getRandomAds($db)
    {
        $select = new Zend_Db_Select($db);
        $select->from(array('p' => 'pages'), array('p.page_id' ,'p.title', 'p.url_slug', 'p.banner_img', 'is_blocked'))
        ->join(array('m' => 'members'),'p.member_id = m.member_id',array('m.member_id', 'm.dir_name'))
        ->where("p.is_in_draft = 0")
        ->where('p.is_blocked = 0')
        ->order('RAND()')
        ->limit(6);
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function getAdPage($page_id, $member_id, $operator){
    	 $select = $this->select();
    	 $select->from($this)->where("is_in_draft = 0")
         ->where("member_id = ?", $member_id)
         ->where("page_id $operator ?", $page_id);
    	 $result = $this->fetchRow($select);
    	 return $result;
    }
    
    public function getAdPageByCategory($category_id){
    	 $select = $this->select();
    	 $select->from($this)->where("is_in_draft = 0")
         ->where("categories = ?", $category_id);
    	 $result = $this->fetchRow($select);
    	 return $result;
    }
	
	    
    public function block($page_id)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('is_blocked'=> 1), $where);

        if ($this->id) {
            return true;
        } else {
            return false;
        }
    }
	
	  public function unblock($page_id)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('is_blocked'=> 0), $where);

        if ($this->id) {
            return true;
        } else {
            return false;
        }
    }
    
    public function hidePage($page_id)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('is_hidden'=> 1), $where);

        if ($this->id) {
            return true;
        } else {
            return false;
        }
    }
	
    public function unhidePage($page_id)
    {
        $where = "page_id = " . (int)$page_id;
        $this->id = $this->update(array('is_hidden'=> 0), $where);

        if ($this->id) {
            return true;
        } else {
            return false;
        }
    }  
}
?>