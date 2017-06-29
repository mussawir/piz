<?php

class Application_Model_ProductComments extends Zend_Db_Table
{ 
    protected $_name = 'post_comments';
    protected $_primary = 'comment_id';
    protected $result = null;


public function getComment($id){
$select = $this->select();
$select->from($this)->where("comment_id = ?", $id);
$result = $this->fetchRow($select);
return $result;
 }
 
 
public function getCommentsByPost($id,$pp_id) 
{
$select = $this->select();
$select->from($this)->where("page_id = ?", $id)->where("pp_id = ?", $pp_id)->order('comment_date DESC');
$result = $this->fetchAll($select);
return $result;
} 

public function getCommentsByPage($id) 
{
$select = $this->select();
$select->from($this)->where("page_id = ?", $id)->order('comment_date DESC');
$result = $this->fetchAll($select);
return $result;
} 

public function getLastInsertRecord()
{
$select = $this->select();
$select->from($this)->order('comment_id DESC');
$result = $this->fetchRow($select);
return $result;
}	
  public function productCommentsJoin($page_id){
		$db = Zend_Db_Table::getDefaultAdapter(); //set in my config file
		$select = new Zend_Db_Select($db);
		$select->from('page_products') 
			->joinInner(
				'post_comments',
				'page_products.pp_id = post_comments.pp_id') //by specifying an empty array, I am saying that I don't care about the columns from this table
			->where('page_products.page_id = ?', $page_id);
		$resultSet = $db->fetchAll($select);
		return $resultSet;
  }
public function addComment($formData) {
  $data = array(
				'email' => $formData['email'],
				'Name' => $formData['name'],
				'comment' => $formData['comment'], 
				'page_id' => $formData['page_id'],  
				'comment_date' => date('Y-m-d | h:i:sa'),
				'pp_id' => $formData['pp_id']
				);
 
 $result = $this->insert($data); 
		 if($result){
			return  $data;
		}  else {
			return "Some error in saving record";
		}
   }
  
   
	public function deleteComment($id){

	$where = "comment_id = " . (int) $id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }  
	}
	

	public function updateComment($db, $formData){
$data = array('name' => $formData['name'],
		'email' => $formData['email'],
		'comment' => $formData['comment'],);

$result = $this->update($data, $db->quoteInto("comment_id = ?", $formData['comment_id']));
 
		if($result){
			return  "<div class='alert alert-success'>Comment has been updated successfully.</div>" ;
		}  else {
			return "Some error in saving record";
		}
  }
 
}