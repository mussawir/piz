<?php
class Application_Model_PageAdmins extends Zend_Db_Table
{ 
    protected $_name = 'page_admins'; 
    protected $_primary = 'p_admin_id';
    
    public function add($page_id, $member_id) 
    {
        $p_admin_id = $this->insert(array('page_id'=>$page_id, 'member_id'=>$member_id)); 
    	if($p_admin_id){
    	   return true;
    	}  else {
    	   return false;
    	}
   }
   
   public function updatePageAdmin($page_id)
   {
        $data = array('');
    	$where = "page_id = ". $page_id;
    		
        $member_id = $this->update($data,$where);
        if($member_id){ 
            return true;
        }  else {
            return false;
        }
   }
   
    public function deletePageAdmin($page_id, $member_id)
    {     
        $where = "mpm_id = " .(int) $member_id;
     	$id = $this->delete($where);
     	if ($id) {
		    return true;
        } else {
		    return false;
       	}
    }
   
   public function getAllPageAdmin(){
        $select = $this->select();
        $select->from($this);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getPagesByAdmin($member_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id);
        $result = $this->fetchAll($select);
        return $result;
   }
} // class end