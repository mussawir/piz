<?php
 
class Application_Model_StripBanner extends Zend_Db_Table
{ 
    protected $_name = 'strip_banner';
    protected $_primary = 'sb_id';
    protected $result = null;
  
 
 public function getStripBanner($id){ 
	 $select = $this->select();
	 $select->from($this)->where("sb_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
 // add new strip_banner
public function addStripBanner($formData) {
	/*
if(isset($formData['is_main']) && $formData['is_main'] == true){
 $data = array('is_main' => 0);
 $result = $this->update($data);	
}*/	
	
 $data = array('banner_img' => $formData['banner_img'],
				'target_url' => $formData['target_url'],
				
				'is_main' => $formData['is_main']
				);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Strip Banner Added Successfully </div>" ;
		}  else {
			return "Some error in saving record";
		}
   }
 
 
     // for get all Banner
 public function getAllStripBanners(){
$select = $this->select();
$select->from($this, array('sb_id','banner_img','target_url','is_main'));
$result = $this->fetchAll($select);
return $result;
 }
  
   public function getMainStripBanner(){
	$select = $this->select();
	$select->from($this)->where("is_main = ?", 1);
	$result = $this->fetchRow($select);
	return $result; 
	 }
  

 public function getLastInsertedRecord()
{
$select = $this->select();
$select->from($this)->where("is_main = 1")->order('sb_id DESC');
$result = $this->fetchRow($select);
return $result;
}	
 

  public function editStripBanner($formData)
  {
	  
	if(isset($formData['is_main']) && $formData['is_main'] == true){
 $data = array('is_main' => 0);
 $result = $this->update($data);	
}	
  
	  
	 $data = array('banner_img' => $formData['banner_img'],
	'target_url' => $formData['target_url'],
	
		'is_main' => $formData['is_main']
		);
     $where = "sb_id= ". $formData['sb_id'];
	 $result = $this->update($data,$where);
	 return $result;
  }
  

   public function removeStripBanner($db, $id){
	   
	   $rowset = $this->fetchAll();
	   $rowCount = count($rowset);
	   if($rowCount < 2 || $rowCount == 1) return 3;

		$id = $this->delete($db->quoteInto("sb_id = ?", $id));
		if($id > 0){
			return 1;
		}else{
			return 2;
		}
	 }
  
}