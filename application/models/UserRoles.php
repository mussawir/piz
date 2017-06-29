<?php
 
class Application_Model_UserRoles extends Zend_Db_Table
{ 
    protected $_name = 'roles';
    protected $_primary = 'role_id';
    protected $result = null;
  
 
 public function getCategoryByID($id){
	 $select = $this->select();
	 $select->from($this)->where("role_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
	/*for select form*/
	public function getAllRolesName()
   {
	$select = $this->select();
	$cols = array("role_id","role");
	$select->from($this);
	$result = $this->fetchAll($select);
	return $result;
   }
     // for get all roles 
    public function getAllRoles(){
     $select = $this->select();
     $select->from($this);
     $result = $this->fetchAll($select);
     return $result;
   }

}