<?php
 
class Application_Model_Feedbacks extends Zend_Db_Table
{ 
    protected $_name = 'feedbacks';
    protected $_primary = 'feed_id';
    protected $result = null;
  
 
  public function getFeedbackByID($id){
	 $select = $this->select();
	 $select->from($this)->where("page_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
  }
  
  // add new page
  public function addFeedback($formData) {
  $data = array(
				 'banner' => $formData['banner'],
				'video' => $formData['video'],
				'f_type' => $formData['f_type'],
				'page_id' => $formData['page_id'],
				'date_created' => date('Y-m-d | h:i:sa')
				);
				 
 $result = $this->insert($data); 
	return $result;
   }
   // for update post
	public function updateFeedback($formData) {
  $data = array(
				 'banner' => $formData['banner'],
				'video' => $formData['video'],
				'f_type' => $formData['f_type']
				);
				 $where = "feed_id = " . (int)$formData["feed_id"];
 $result = $this->update($data,$where); 
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
	
}
?>