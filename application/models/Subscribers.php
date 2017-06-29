<?php
 
class Application_Model_Subscribers extends Zend_Db_Table
{ 
    protected $_name = 'subscribers';
    protected $_primary = 'sub_id';
    protected $result = null;
  
 
  // public function getPageByID($id){
	 // $select = $this->select();
	 // $select->from($this)->where("page_id = ?", $id);
	 // $result = $this->fetchRow($select);
	 // return $result;
  // }
  
 // public function authorization($page_id, $member_id){
	 // $select = $this->select();
	 // $select->from($this)->where("page_id = ?", $page_id)->where("member_id = ?", $member_id);
	 // $result = $this->fetchRow($select);
	 // if(count($result) > 0 ){
		// return true; 
	 // }else{
		 // return false;
	 // }
 // }
 
  // add new page
  public function add($formData) {
  $data = array(
				'full_name' => $formData['full_name'],
				'phone' => $formData['phone'],
				'email' => $formData['email'],
				'member_id' => $formData['member_id'],
				);
				 
 $result = $this->insert($data); 
	return $result;
   }
  public function addlandingpagesubscriber($formData) {
  $data = array(
				'email' => $formData['email']
				);
				 
 $result = $this->insert($data); 
	return $result;
   }
 // add draft page
      
}
?>