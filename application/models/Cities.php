<?php
class Application_Model_Cities extends Zend_Db_Table
{ 
    protected $_name = 'cities'; 
    protected $_primary = 'city_id';
     private $id = null;
private $result;


public function add($formData){
        $city_name = $formData['city_name'];
    	$state_id = $formData['state_id'];
		$country_id = $formData['country_id'];	
		
        $data = array("city_name" => $city_name,"state_id" => $state_id, "country_id" => $country_id);
$this->id = $this->insert($data);
if($this->id){
    return  "<div class='alert alert-success'>City ".$city_name ." Added Successfully </div>" ;
}  else {
    return "<div class='alert alert-danger'>Some error in saving record</div>";
}
}

public function bulkAdd($formData){
        $cities = explode(',',$formData['cities'] );
        $state_id = $formData['state_id'];
        $country_id = $formData['country_id'];  
       
$counter = 0;
foreach ($cities as $city) {
$data = array("city_name" => trim($city),"state_id" => $state_id, "country_id" => $country_id);

if(!$this->checkCity($data))
$this->id = $this->insert($data);
$counter++;
}

    return  "<div class='alert alert-success'>".$counter ." cities are added/updated successfully </div>" ;
}




public function updateCityById($formData)
{
$city_name = $formData['city_name'];
$city_id = $formData['city_id'];
$data = array('city_name' => $city_name);
$where = "city_id=". $city_id;
$id = $this->update($data, $where);
if($id==0 || $id>0){
    return  "<div class='alert alert-success'>".$city_name ." Updated Successfully </div>" ;
	}  else {
    return "Ops something gone wrong!";
	}
}

public function checkCity($data){
$select = $this->select();
$select->from($this)->where('country_id = ?', $data['country_id'])->where('state_id = ?', $data['state_id'])
->where('city_name = ?', $data['city_name']);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}

public function getCityById($city_id)
{
$select = $this->select();
$select->from($this)->where('city_id=?',$city_id);
$result = $this->fetchRow($select);
return $result;
}

public function getCities($data){
$select = $this->select();
$lang_id = Zend_Registry::get("lang_id");	
		
$select->from($this)->where('country_id = ?', $data['country'])->where('state_id = ?', $data['state'])
->where('lang_id = ?', $lang_id)->where('user_id = ?', $data['user_id']);
$result = $this->fetchAll($select);
if(is_object($result)){
	return $result;
	}else return false; 
}

public function getSearchCities($query_string)
{
$select = $this->select();	
		
$select->from($this)->where("city_name like ? ", "%" . $query_string. "%");
$result = $this->fetchAll($select);
return $result;
}


//static function 
public static function getCityStatic($db, $city_id){
	$select = new Zend_Db_Select($db);
		$cols = array("city_name");
		$select->from('cities', $cols)
		->where("city_id = ?", $city_id);
		$stmt = $db->query($select);
		$result = $stmt->fetchObject();
		return $result->city_name;
} 



public function getAllCities($db){
$select = $this->select();	
$cols = array("city_id","city_name");
$select->from($this,$cols)->order("city_name ASC");
$results = $this->fetchAll($select);
return $results;
}


public function getCitiesByCountry($country_id,$db){
$select = $this->select();	

$cols = array("city_id","city_name");
$select->from($this,$cols)->where("country_id = ?", $country_id);
$results = $this->fetchAll($select);
return $results;
}


public function updateCity($formData, $city_id){
        $city_name = $formData['city_name'];
       	$city_code = $formData['city_code'];
		$state_id = $formData['state_id'];		
		$country_id = $formData['country_id'];
   
	    $data = array("city_name" => $city_name,"city_code" => $city_code,
            "state_id" => $state_id, "country_id" => $country_id);
        $where = "city_id = ". (int) $city_id;

$this->id = $this->update($data, $where);
if($this->id == 0){
    return  $city_name ." is updated: "  ;
}  else {
    return "Some error in saving record";
}
}

public function getcountrycityId($city_id){

$select = $this->select();
$select->from($this)->where('city_id=?',$city_id);
$results = $this->fetchRow($select);
return $results;
}


public function deleteRecord($category_id){
                    $result = $this->fetchRow($this->select(array("category_id"))
->where("category_id = ?", (int) $category_id));
        $where = "category_id = " . (int) $category_id;
        if ($result) {
            $id = $this->delete($where);
            $this->result = true;
        }else{
            $this->result = false;

        }
        return $this->result;
    }

} 

