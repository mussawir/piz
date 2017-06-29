<?php
class Application_Model_States extends Zend_Db_Table
{ 
    protected $_name = 'states'; 
    protected $_primary = 'state_id';
    private $id = null;
	private $result;


public function addState($formData){
         $data = array(
		"country_id" => $formData['country'],
		"lang_id" => Zend_Registry::get('lang_id'),
		"state_name" => $formData['state_name'],
		);
		//	print_r($data);die;
$this->state_id = $this->insert($data);
if($this->state_id){ 
  return  "<div class='alert alert-success'>". $formData['state_name'] ." Added Successfully </div>" ;
	}  else {
    return "<div class='alert alert-danger'>Ops something gone wrong!</div>";
	}
}

public function getState($state_id){
$select = $this->select();
$select->from($this)->where('state_id = ?', $state_id)
->where('lang_id = ?', Zend_Registry::get('lang_id'));
$result = $this->fetchRow($select);
return $result;
} 

//static function 
public static function getStateStatic($db, $state_id){
	$select = new Zend_Db_Select($db);
		$cols = array("state_name");
		$select->from('states', $cols)
		->where("state_id = ?", $state_id);
		$stmt = $db->query($select);
		$result = $stmt->fetchObject();
		return $result->state_name;
} 

public function getSearchStates($state_name)
{
$select = $this->select();
		$cols = array("state_id","state_name");
		$select->from($this, $cols)->where("state_name like ? ", "%" .$state_name);
		$result = $this->fetchAll($select);
		if(is_object($result)){
				return $result;
		}else return false; 
}

public function editState($formData)
{
$state_name = $formData['state_name'];
	   
    	//$user_id = $formData['user_id'];
		
        $data = array('state_name' => $state_name);
		//update('table', $data, $where);
		
		$where = "state_id = " . $formData['state_id'];
		$id = $this->update($data, $where);
		if($id==0 || $id>0){
    return  "<div class='alert alert-success'>".$state_name ." Updated Successfully </div>" ;
	}  else {
    return "Ops something gone wrong!";
	}
}

public function getStateByCountryid($country_id)
{
$select = $this->select();
$select->from($this)->where('lang_id = ?',  Zend_Registry::get('lang_id'))->where('country_id=?',$country_id);
$result = $this->fetchAll($select);	
return $result;
}


public function getList()
{
$select = $this->select();
$select->from($this)->where('lang_id = ?',  Zend_Registry::get('lang_id'));
$result = $this->fetchAll($select);	
return $result;
}

public function checkState($data){
$select = $this->select();
$select->from($this)->where('state_name = ?', $data['state_name'])
->where('country_id = ?', $data['country'])
->where('lang_id = ?', Zend_Registry::get('lang_id'));
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}

}