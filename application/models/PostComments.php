<?php

class Application_Model_PostComments extends Zend_Db_Table
{ 
    protected $_name = 'post_comments';
    protected $_primary = 'pc_id';
    protected $result = null;


    public function addComment($data) {
     $data = array('Name' => $formData['name'],
				'email' => $formData['email'],
				'comment' => $formData['comment'],
				'page_id' => $formData['page_id'],
				'pp_id' => $formData['pp_id'],
				'comment_date' => date('Y-m-d')
				); 
 
    $result = $this->insert($data); 
		 if($result){
			return $result; 
		}  else {
			return "Some error occured";
		}
   }

		public function getComment($id){
		$select = $this->select();
		$select->from($this)->where("pc_id = ?", $id);
		$result = $this->fetchRow($select);
		return $result;
		}	
 
     // for get total comments
 	public function getAllComments($db){
	$select = new Zend_Db_Select($db);
	$cols = array('pc_id','post_id', 'comment','comment_date','name','email','status');
   $select->from(array('pc' =>'comments_post'), $cols)->order("comment_date DESC")
   ->join(array('p' => 'posts'),'p.post_id = pc.post_id',array('url','heading'));
   $stmt = $db->query($select);
   $results = $stmt->fetchAll(); 
   return $results;
	}
 
public function getCommentsByPost($id) 
{
$select = $this->select();
$select->from($this)->where("status = 2")->where("post_id = ?", $id);
$result = $this->fetchAll($select);
return $result;
}
		/*for individual comments*/
public function getCommentsByPostID($db,$data) 
{
	$select = new Zend_Db_Select($db);
	$cols = array('pc_id','post_id', 'comment','comment_date','name','email','status');
   $select->from(array('pc' =>'comments_post'), $cols)->order("comment_date DESC")
   ->joinLeft(array('p' => 'posts'),'p.post_id = pc.post_id',array('heading'))->where("pc.post_id = ?", $data);
   $stmt = $db->query($select);
   $results = $stmt->fetchAll(); 
   return $results;
   /*
$select = $this->select();
$select->from($this)->where("post_id = ?", $id);
$result = $this->fetchAll($select);
return $result;*/
}
	/*for count total no of comments by post id*/
public function getCommentsCountByPost($id) 
{
$select = $this->select();
$select->from($this)->where("post_id = ?", $id);
$result = $this->fetchAll($select);
return count($this->result);
}

    public function getPendingComments($db){
	$select = new Zend_Db_Select($db);
	$cols = array('pc_id','post_id', 'comment','comment_date','name','email','status');
   $select->from(array('pc' =>'comments_post'), $cols)->order("comment_date DESC")->where("status = 1")
   ->joinLeft(array('p' => 'posts'),'p.post_id = pc.post_id',array('url','heading'));//->where("p.page_id =?", 'c.page_id' );
   $stmt = $db->query($select);
   $results = $stmt->fetchAll(); 
   return $results;
	}
 
	public function getApprovedComments($db){
	$select = new Zend_Db_Select($db);
	$cols = array('pc_id','post_id', 'comment','comment_date','name','email','status');
   $select->from(array('pc' =>'comments_post'), $cols)->order("comment_date DESC")->where("status = 2")
   ->joinLeft(array('p' => 'posts'),'p.post_id = pc.post_id',array('url','heading'));//->where("p.page_id =?", 'c.page_id' );
   $stmt = $db->query($select);
   $results = $stmt->fetchAll(); 
   return $results;
	}
	
	public function getRejectedComments($db){
	$select = new Zend_Db_Select($db);
	$cols = array('pc_id','post_id', 'comment','comment_date','name','email','status');
   $select->from(array('pc' =>'comments_post'), $cols)->order("comment_date DESC")->where("status = 3")
   ->joinLeft(array('p' => 'posts'),'p.post_id = pc.post_id',array('url','heading'));//->where("p.page_id =?", 'c.page_id' );
   $stmt = $db->query($select);
   $results = $stmt->fetchAll(); 
   return $results;
	}
 
	public function deleteComment($id){

	$where = "pc_id = " . (int) $id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }  
	}
    
    public function deleteCommentByPost($post_id){

    	$where = "post_id = " . (int)$post_id;
        $id = $this->delete($where);
	}
	
	public function approveComment($db, $id){ 
	$data = array('status'=> 2);
	$result = $this->update($data,$db->quoteInto("pc_id = ?", $id));
	if($result)
		return true;
	else
		return false;
}

	public function rejectComment($db, $id){ 
	$data = array('status'=> 3);
	$result = $this->update($data,$db->quoteInto("pc_id = ?", $id));
	if($result)
		return true;
	else
		return false;
}

	public function updateComment($db, $formData){
$data = array('name' => $formData['name'],
		'email' => $formData['email'],
		'comment' => $formData['comment'],);

$result = $this->update($data, $db->quoteInto("pc_id = ?", $formData['pc_id']));
 
		if($result){
			return  "<div class='alert alert-success'>Comment has been updated successfully.</div>" ;
		}  else {
			return "Some error in saving record";
		}
  }
 
    public function getAllPendingComments()
    {
        $select = $this->select();
    	$select->from($this)->where("status = 1");
    	$result = $this->fetchAll($select);
    	return count($result);
    }
    
    public function getAllPendingCommentsByUser($db, $user_id){
        $select = new Zend_Db_Select($db);
    	$cols = array('pc_id','post_id', 'comment','comment_date','name','email','status');
        $select->from(array('pc' =>'comments_post'), $cols)->order("comment_date DESC")->where("status = 1")->where("user_id = ?",$user_id)
        ->join(array('p' => 'posts'),'p.post_id = pc.post_id',array('user_id'));
        $stmt = $db->query($select);
        $results = $stmt->fetchAll(); 
        return count($results);
	}
    
    public function getPendingCommentsByUser($db, $user_id){
    	$select = new Zend_Db_Select($db);
    	$cols = array('pc_id','post_id', 'comment','comment_date','name','email','status');
       $select->from(array('pc' =>'comments_post'), $cols)->order("comment_date DESC")->where("status = 1")->where("user_id= ?", $user_id)
       ->joinLeft(array('p' => 'posts'),'p.post_id = pc.post_id',array('url','heading','user_id'));
       $stmt = $db->query($select);
       $results = $stmt->fetchAll(); 
       return $results;
	}
    
    public function getAllCommentsByUser($db, $user_id){
    	$select = new Zend_Db_Select($db);
    	$cols = array('pc_id','post_id', 'comment','comment_date','name','email','status');
       $select->from(array('pc' =>'comments_post'), $cols)->order("comment_date DESC")->where("user_id= ?", $user_id)
       ->join(array('p' => 'posts'),'p.post_id = pc.post_id',array('url','heading'));
       $stmt = $db->query($select);
       $results = $stmt->fetchAll(); 
       return $results;
	}
}