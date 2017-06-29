<?php
class Application_Model_MemberPagesMaster extends Zend_Db_Table
{ 
    protected $_name = 'member_pages_master'; 
    protected $_primary = 'mpm_id';
    
    public function add($formData) 
    {
        $expiry_date = date('Y/m/d', strtotime('+1 year'));
        $expiry_date = date('Y/m/d', strtotime("+1 months", strtotime($expiry_date)));
        $formData['date_created'] = date('Y-m-d H:i:s');
        $formData['start_date'] = date('Y-m-d'); 
        $formData['end_date'] = $expiry_date; 
       
        $mpm_id = $this->insert($formData); 
		 if($mpm_id){
			return $mpm_id;
		}  else {
			return null;
		}
   }
   public function addAdminMemberMaster($formData) 
    {
        $expiry_date = $formData['end_date'];
        $expiry_date = date('Y/m/d', strtotime("+1 months", strtotime($expiry_date)));
        $formData['date_created'] = date('Y-m-d H:i:s');
        $formData['start_date'] = date('Y-m-d'); 
        $formData['end_date'] = $expiry_date; 
       
        $mpm_id = $this->insert($formData); 
		 if($mpm_id){
			return $mpm_id;
		}  else {
			return null;
		}
   }
   public function addMaster($formData) 
   {
        $formData['date_created'] = date('Y-m-d H:i:s');
        
        $mpm_id = $this->insert($formData); 
		 if($mpm_id){
			return $mpm_id;
		}  else {
			return null;
		}
   }
   
   public function updateMasterPage($master_page_id)
   {
        $expiry_date =  date('Y/m/d', strtotime('+1 year'));
        
        $data = array("start_date" => date('Y-m-d'), "end_date" => $expiry_date);
    	$where = "mpm_id = ". $master_page_id;
    		
        $member_id = $this->update($data,$where);
        if($member_id){ 
            return true;
        }  else {
            return false;
        }
   }
   public function updateAdminMasterPage($formData,$master_page_id)
   {
        $expiry_date = $formData['end_date'];
        $expiry_date = date('Y/m/d', strtotime("+1 months", strtotime($expiry_date)));
        
        $data = array("status"=>$formData['status'], "price"=>$formData['price'], "end_date" => $expiry_date);
    	$where = "mpm_id = ". $master_page_id;
    		
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
   
   public function getAllMasterPages(){
        $select = $this->select();
        $select->from($this);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getMasterPagesByMemeber($member_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id)->where('is_active = ?',1);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getMemeberMasterPages($member_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getMasterPage($master_page_id){
        $select = $this->select();
        $select->from($this)->where('mpm_id = ?',$master_page_id);
        $result = $this->fetchRow($select);
        return $result;
   }
   
   public function updatePageMember($old_member_id, $new_member_id)
    {
        $where = "member_id = " . (int)$old_member_id;
        $this->id = $this->update(array('member_id'=> $new_member_id), $where);

        if ($this->id)
        {
            return true;
        } else {
            return false;
        }
    }
} // class end