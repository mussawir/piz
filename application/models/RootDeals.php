<?php
class Application_Model_RootDeals extends Zend_Db_Table
{ 
    protected $_name = 'root_deals'; 
    protected $_primary = 'root_deals_id';
    
    public function add($formData) 
    {
        $root_deals_id = $this->insert($formData); 
		 if($root_deals_id){
			return true;
		}  else {
			return false;
		}
   }
   
   public function updateMember($formData)
   {
        $data = array("member_id" => $formData['member_id']);
    	$where = "root_deals_id = ". $formData['root_deals_id'];
    		
        $root_deals_id = $this->update($data,$where);
        if($root_deals_id){ 
            return true;
        }  else {
            return false;
        }
   }
   
    public function delete($root_deals_id)
    {     
        $where = "root_deals_id = " .(int) $root_deals_id;
     	$id = $this->delete($where);
     	if ($id) {
		    return true;
        } else {
		    return false;
       	}
    }
   
   public function getRootMembersByUser($user_id){
        $select = $this->select();
        $select->from($this)->where('user_id = ?',$user_id);
        $result = $this->fetchAll($select);
        return $result;
   }
      
   public function getRootMember($root_id){
        $select = $this->select();
        $select->from($this)->where('root_id = ?',$root_id);
        $result = $this->fetchRow($select);
        return $result;
   }
   
    
} // class end