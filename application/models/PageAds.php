<?php
class Application_Model_PageAds extends Zend_Db_Table
{ 
    protected $_name = 'page_ads'; 
    protected $_primary = 'page_ad_id';
    
    public function add($formdata) 
    {
        $formdata['date_created'] =date('Y-m-d H:i:s');
        
        $page_ad_id = $this->insert($formdata);
    	if($page_ad_id){
  			return true;
   		}  else {
  			return false;    		
        }
   }
   
   public function updateAd($formdata, $page_ad_id)
   {
        $where = "page_ad_id = ". $page_ad_id;
    		
        $page_ad_id = $this->update($formdata,$where);
        if($page_ad_id){ 
            return true;
        }  else {
            return false;
        }
   }
   
    public function deletePageAd($page_ad_id)
    {     
        $where = "page_ad_id = " .(int) $page_ad_id;
     	$id = $this->delete($where);
     	if ($id) {
		    return true;
        } else {
		    return false;
       	}
    }
   
   public function getAllPageAds($page_id)
   {
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id);
        $result = $this->fetchRow($select);
        return $result;
   }
   
   public function getPageAd($page_ad_id){
        $select = $this->select();
        $select->from($this)->where('page_ad_id = ?',$page_ad_id);
        $result = $this->fetchRow($select);
        return $result;
   }
} // class end