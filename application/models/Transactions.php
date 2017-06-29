<?php
class Application_Model_Transactions extends Zend_Db_Table
{ 
    protected $_name = 'transactions'; 
    protected $_primary = 'trans_id';
    
    public function add($formData) 
    {
        $formData['trans_date'] = date('Y-m-d H:i:s');       
       
        $result = $this->insert($formData); 
		 if($result){
			return  true;
		}  else {
			return false;
		}
   }
   
   public function updateTransactions($formData)
   {
        $data = array("member_id" => $formData['member_id']);
    	$where = "trans_id = ". $formData['trans_id'];
    		
        $member_id = $this->update($data,$where);
        if($member_id){ 
            return true;
        }  else {
            return false;
        }
   }
   
    public function delete($member_id)
    {     
        $where = "trans_id = " .(int) $member_id;
     	$id = $this->delete($where);
     	if ($id) {
		    return true;
        } else {
		    return false;
       	}
    }
    
   public function getAllTransactions(){
        $select = $this->select();
        $select->from($this);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getTransactions($member_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getMemeberTransactions($member_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id)->where('points_debit > ?',0);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getTransactionById($id){
        $select = $this->select();
        $select->from($this)->where('trans_id = ?',$id);
        $result = $this->fetchRow($select);
        return $result;
   }
      
} // class end