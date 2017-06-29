<?php
class Application_Model_EmailServices extends Zend_Db_Table
{ 
    protected $_name = 'email_services';
    protected $_primary = 'es_id';
    
    public function add($data){
        $result = $this->insert($data); 
        if($result)
        {
            return true;
		}  else {
			return false;
		}
    }
    
    public function getServiceInfo($api_key, $list_id){
	 $select = $this->select();
	 $select->from($this)->where("api_key = ?", $api_key, "and list_id=?",$list_id)->order('date_created DESC');
	 $result = $this->fetchRow($select);
	 return $result;
    }
 
}