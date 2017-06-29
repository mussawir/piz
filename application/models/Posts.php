<?php

class Application_Model_Posts extends Zend_Db_Table
{
    protected $_name = 'posts';
    protected $_primary = 'post_id';
    protected $result = null;


    public function getPostByID($id)
    {
        $select = $this->select();
        $select->from($this)->where("post_id = ?", $id);
        $result = $this->fetchRow($select);
        return $result;
    }

    public function getPostByUrl($page)
    {  
        $select = $this->select();
        $select->from($this)->where("url = ?", $page);
        $result = $this->fetchRow($select);
        return $result;
    }

	// check for slug name from db for new post
	public function checkPostSlug($slug){
	$select = $this->select();
	$select->from($this)->where('url = ?', $slug);
	$result = $this->fetchRow($select);
	return $result;
	}
	
	// check for slug name from db for edit post
	public function checkPostsSlug($slug, $id){
	$select = $this->select();
	$select->from($this)->where('url = ?', $slug)->where('post_id != ?', $id);
	$result = $this->fetchRow($select);
	return $result;
	}
	
    // add new post
    public function addPost($formData)
    {
        $data = array(
            'user_id' => $formData['user_id'],
            'url' => $formData['url'],
            'date_created' => $formData['date_created'],
            'date_published' => $formData['date_published'],
            'heading' => $formData['heading'],
            "is_in_draft" => $formData['is_in_draft'],
            'image' => $formData['image'],
            'contents' => $formData['contents'],
			'description_content' => $formData['description_content'],
			'keyword_content' => $formData['keyword_content'],
            'is_comment' => $formData['is_comment'],
             'draft_content' => $formData['draft_content'],
            'tags' => $formData['tags']);
			
        $result = $this->insert($data);
         return $result;
    }

    // add draft post
    public function addDraftPost($formData)
    {
        $data = array(
            'user_id' => $formData['user_id'],
            'url' => $formData['url'],
            "is_in_draft" => $formData['is_in_draft'],
            'date_created' => $formData['date_created'],
            'date_published' => $formData['date_published'],
            'heading' => $formData['heading'],
            'image' => $formData['image'],
            'contents' => $formData['contents'],
			'description_content' => $formData['description_content'], 
			'keyword_content' => $formData['keyword_content'],
			'draft_content' => $formData['draft_content'],
            'tags' => $formData['tags']);

        $result = $this->insert($data);
		return $result;
    }
	
    // for get all published post
    public function getAllPosts($db)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
			'url',
            'contents',
            'tags',
            'is_comment',
            'is_in_draft',
            'date_published');
        $select->from(array('p' => 'posts'), $cols)->where('p.is_in_draft=0')->order('p.date_published DESC')
        ->join(array('u' => 'users'),'u.user_id = p.user_id',array('u.user_name'));//->where("p.page_id =?", 'c.page_id' );
        //->joinLeft(array('pc' => 'post_comments'),'pc.post_id = p.post_id',array('comment_date','comment','pc_id'));//->where("p.page_id =?", 'c.page_id' );
        $stmt = $db->query($select);
        $results = $stmt->fetchAll();
        return $results;
    }
    
    public function getAllPostsByUser($db, $user_id)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
			'url',
            //'short_dsc' => 'left(contents, 500)',
            'contents',
            'tags',
            'is_comment',
            'is_in_draft',
            'date_published');
        $select->from(array('p' => 'posts'), $cols)->where('p.user_id=?',$user_id)->where('p.is_in_draft=0')->order('p.date_published DESC')
        ->join(array('u' => 'users'),'u.user_id = p.user_id',array('u.user_name'));
        $stmt = $db->query($select);
        $results = $stmt->fetchAll();
        return $results;
    }

    // for get all draft post
    public function getAllDraftPosts($db)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
			'url',
            //'short_dsc' => 'left(contents, 500)',
            'contents',
            'tags',
            'is_comment',
            'is_in_draft',
            'date_published');
        $select->from(array('p' => 'posts'), $cols)->where("p.is_in_draft = 1")->order('p.date_published DESC')
        ->join(array('u' => 'users'),'u.user_id = p.user_id',array('u.user_name'));
        $stmt = $db->query($select);
        $results = $stmt->fetchAll();
        return $results;
    }

    public function getAllDraftPostsByUser($db, $user_id)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
			'url',
            //'short_dsc' => 'left(contents, 500)',
            'contents',
            'tags',
            'is_comment',
            'is_in_draft',
            'date_published');
        $select->from(array('p' => 'posts'), $cols)->where("p.user_id=?",$user_id)->where("p.is_in_draft = 1")->order('p.date_published DESC')
        ->join(array('u' => 'users'),'u.user_id = p.user_id',array('u.user_name'));
        $stmt = $db->query($select);
        $results = $stmt->fetchAll();
        return $results;
    }

    // for get recent posts
    public function getRecentPosts()
    {
        $select = $this->select();
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
			'url',
            'short_dsc' => 'left(contents, 500)',
            'contents',
            'tags',
            'is_comment',
            'is_in_draft',
            'date_published');
        $select->from($this,$cols)->where("is_in_draft = 0")->order('date_published DESC');
        $result = $this->fetchAll($select);
        return $result;
    }

    //this function is used for finding post from admin posts list
    public function findPost($db,$name)
    {
        $select = new Zend_Db_Select($db);
        $cols = array(
            'post_id',
            'user_id',
            'date_created',
            'heading',
            'image',
            //'short_dsc' => 'left(contents, 500)',
            'contents',
			'description_content',
			'keyword_content',
            'is_comment',
            'tags',
            'is_in_draft',
            'date_published','url');
        $select->from(array('p' => 'posts'), $cols)
            ->join(array('u' => 'users'),'u.user_id = p.user_id',array('u.user_name'))
            ->join(array('pmc' => 'post_map_category'),'pmc.post_id = p.post_id',array('pmc.category_id'))
            ->join(array('pcat' => 'categories_post'),'pcat.category_id = pmc.category_id',array('pcat.name'))
            ->where("p.is_in_draft = 0")->where("p.heading like ? ", "%" . $name . "%")
            ->orWhere("p.url like ? ", "%" . $name . "%")
            ->orWhere("pcat.name = ?", $name);
        $stmt = $db->query($select);
        $result = $stmt->fetchAll();
        return $result;
    }

	// for update url slug
    public function updateUrl($url)
    {
        $where = "post_id = " . (int)$url["post_id"];
        $this->id = $this->update($url, $where);

        if ($this->id)
        {
            return true;
        } else
        {
            return null;
        }
    }
	
    // for update post
    public function updatePost($formData)
    {

        $data = array(
            "heading" => $formData['heading'],
            "image" => $formData['image'],
            "url" => $formData['url'],
            "is_in_draft" => $formData['is_in_draft'],
            "contents" => $formData['contents'],
            "is_comment" => $formData['is_comment'],
			"description_content" => $formData['description_content'],
			"keyword_content" => $formData['keyword_content'],
             'draft_content' => $formData['draft_content'],
            "tags" => $formData['tags']);
        $where = "post_id = " . (int)$formData["post_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Post Updated Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error in update record</div>";
        }
    }

    // for update draft post
    public function updateDraftPost($formData)
    {

        $data = array(
            "heading" => $formData['heading'],
            "image" => $formData['image'],
            "url" => $formData['url'],
            "is_in_draft" => $formData['is_in_draft'],
            "contents" => $formData['contents'],
            "is_comment" => $formData['is_comment'],
			"description_content" => $formData['description_content'],
			"keyword_content" => $formData['keyword_content'],
			'draft_content' => $formData['draft_content'],
            "tags" => $formData['tags']);
        $where = "post_id = " . (int)$formData["post_id"];
        $this->id = $this->update($data, $where);

        if ($this->id)
        {
            return "<div class='alert alert-success'> Post Updated and Save in Draft Successfully. </div>";
        } else
        {
            return "<div class='alert alert-danger'>Some error in update record</div>";
        }
    }


    // for remove post
    public function removePost($db, $id)
    {

        //$rowset = $this->fetchAll();
        //$rowCount = count($rowset);
        //if ($rowCount < 2 || $rowCount == 1) return 3;

        $id = $this->delete($db->quoteInto("post_id = ?", $id));
        if ($id > 0)
        {
            return 1;
        } else
        {
            return 2;
        }
    }
    
    public function getDraftedPosts()
    {
        $select = $this->select();
    	$select->from($this)->where("is_in_draft = 1");
    	$result = $this->fetchAll($select);
    	return count($result);
    }
    
    public function getDraftedPostsByUser($user_id)
    {
        $select = $this->select();
    	$select->from($this)->where("is_in_draft = 1")->where("user_id = ?", $user_id);
    	$result = $this->fetchAll($select);
    	return count($result);
    }
    
}