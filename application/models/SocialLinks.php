<?php
 
class Application_Model_SocialLinks extends Zend_Db_Table
{ 
    protected $_name = 'social_links';
    protected $_primary = 'sl_id';
    protected $result = null;
  
   
   public function getSocialLinks(){
	$select = $this->select();
	$select->from($this);
	$result = $this->fetchRow($select);
	return $result; 
	 }
 
 
  public function updateSocailLinks($formData)
  {
	  $data = array('facebook' => $formData['facebook'],
				'linkedin' => $formData['linkedin'],
				'twitter' => $formData['twitter'],
				'youtube' => $formData['youtube'],
				'instagram' => $formData['instagram'],
				'google_plus' => $formData['google_plus'],
				'tumblr' => $formData['tumblr']);
				$where = $this->getAdapter()->quoteInto('sl_id = ?',$formData['id']);
	 $result = $this->update($data,$where);
	 if($result){
			return  1;
		}  else {
			return 0;
		}
	 return $result;
  }
  
}