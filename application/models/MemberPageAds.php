<?php
class Application_Model_MemberPageAds extends Zend_Db_Table
{ 
    protected $_name = 'member_page_ads'; 
    protected $_primary = 'mp_ad_id';
    
    public function add($page_id, $member_id, $box_page_id, $sort_order) 
    {
        $mp_ad_id = $this->insert(array('main_page_id'=>$page_id, 'member_id'=>$member_id, 'box_page_id'=>$box_page_id, 'sort_order'=>$sort_order)); 
        if($mp_ad_id){
    	   return true;
  		}  else {
    	   return false;
  		}
   }
   
   public function updateLike($page_id)
   {
        $data = array("start_date" => date('Y-m-d'), "end_date" => $expiry_date);
    	$where = "page_id = ". $page_id;
    		
        $member_id = $this->update($data,$where);
        if($member_id){ 
            return true;
        }  else {
            return false;
        }
   }
   
    public function deleteMasterPage($member_id)
    {     
        $where = "mpm_id = " .(int) $member_id;
     	$id = $this->delete($where);
     	if ($id) {
		    return true;
        } else {
		    return false;
       	}
    }
   
   public function getMemberPageAds($page_id, $member_id){
        $select = $this->select();
        $select->from($this)->where('main_page_id = ?',$page_id)->where('member_id = ?',$member_id);
        $result = $this->fetchAll($select);
        return $result;
   }

   public function getLikeByPage($page_id, $member_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('member_id = ?',$member_id);
        $result = $this->fetchRow($select);
        return $result;
   }
} // class end