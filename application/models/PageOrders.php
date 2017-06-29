<?php
class Application_Model_PageOrders extends Zend_Db_Table
{ 
    protected $_name = 'page_orders'; 
    protected $_primary = 'po_id';
    
    public function add($formdata) 
    {
        $formdata['date_ordered'] =date('Y-m-d H:i:s');
        
        $page_ad_id = $this->insert($formdata);
    	if($page_ad_id){
  			return true;
   		}  else {
  			return false;    		
        }
   }
   
  
   
   public function getAll()
   {
        $select = $this->select();
        $select->from($this);
        $result = $this->fetchAll($select);
        return $result;
   }
   
} // class end