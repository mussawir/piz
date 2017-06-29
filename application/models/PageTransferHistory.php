<?php
class Application_Model_PageTransferHistory extends Zend_Db_Table
{ 
    protected $_name = 'page_transfer_history'; 
    protected $_primary = 'pth_id';
    
    public function add($formdata)
    {
        $formdata['trans_date'] = date('Y-m-d H:i:s');
        $pth_id = $this->insert($formdata); 
    	if($pth_id){
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
   
   public function getTransMembersByPage($page_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getTransPageRoot($page_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id);
        $result = $this->fetchRow($select);
        return $result;
   }
} // class end