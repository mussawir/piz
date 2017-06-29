<?php
class Application_Model_Postcodes extends Zend_Db_Table
{ 
    protected $_name = 'postcodes'; 
    protected $_primary = 'pc_id';
     private $id = null;
private $result;


public function add($data){
$this->id = $this->insert($data);
if($this->id){
    return  "<div class='alert alert-success'>Postcode ".$data['postcode'] ." Added Successfully </div>" ;
}  else {
    return "<div class='alert alert-danger'>Some error in saving record</div>";
}
}

public function bulkAdd($formData){
        $pc_cities = explode(',',$formData['postcodes'] );
        $state_id = $formData['state_id'];
        $country_id = $formData['country_id'];  
       
$counter = 0;
foreach ($pc_cities as $pc_city) {
$pc_array = explode(' ', $pc_city); 

$data = array("postcode" => trim($pc_array[0]),"area_name" => trim($pc_array[1]),"state_id" => $state_id, "country_id" => $country_id);
if(!$this->checkPostcodeArea($data)){
$this->id = $this->insert($data);
}
$counter++;
}
    return  "<div class='alert alert-success'>".$counter ." postcodes are added/updated successfully </div>" ;
}

//Each postcode must be unique in a state even in a country and must have unique value of post and area
public function checkPostcodeArea($data){
$select = $this->select();
$select->from($this)->where('country_id = ?', $data['country_id'])->where('state_id = ?', $data['state_id'])
->where('postcode = ?', $data['postcode'])->where('area_name = ?', $data['area_name']);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}


public function edit($formData)
{
$areas = $formData['areas'];
$area_name = $formData['area_name'];
$postcode = $formData['postcode'];
        $data = array("postcode" => $postcode, "areas" => $areas, "area_name" => $area_name);
		//update('table', $data, $where);
		
		$where = "pc_id = " . $formData['pc_id'];
		$this->id = $this->update($data, $where);
		if($this->id){
    return  "<div class='alert alert-success'>".$areas ." Added Successfully </div>" ;
	}  else {
    return "Ops something gone wrong!";
	}
}

public function getPostCodeById($pc_id)
{
$select = $this->select();
$select->from($this)->where('pc_id = ?', $pc_id);
$result = $this->fetchRow($select);
return $result;
}

public function checkPostcodeOnly($postcode){
$select = $this->select();
$select->from($this)->where('postcode = ?', $postcode);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}




public function checkPostcode($data){
$select = $this->select();
$select->from($this)->where('country_id = ?', $data['country_id'])->where('state_id = ?', $data['state_id'])->where('city_id = ?', $data['city_id'])->where('postcode = ?', $data['postcode']);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}


public function getCountriesPostcodes($country_id, $db){
$select = $this->select();
$select->from($this)->where('country_id = ?', $country_id);

$result = $this->fetchAll($select);
return $result;
}

	
/**
* @author : Musavir 
* @version : 1.0
**/	
public function getAllPostcodes($db){
		$select = new Zend_Db_Select($db);
		$cols = array('pc_id', 'postcode', 'area_name', 'areas', 'country_id', 'state_id', 'city_id');
		$select->from(array('pc' =>'postcodes'), $cols)->join(array('c' => 'countries'),'pc.country_id = c.country_id',array('country_id', 'country_name') )
		->join(array('st' => 'states'),'pc.state_id = st.state_id',array('state_id', 'state_name') )
		->join(array('ct' => 'cities'),'pc.city_id = ct.city_id',array('city_id', 'city_name') )
		
		;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//$output = $this->listScript($results);
		return $results; 
	}


   

/**
* Non Ajaxed Postcode Search Function 

**/

//static function 
public static function getPostcodeStatic($db, $pc_id){
	$select = new Zend_Db_Select($db);
		$cols = array("postcode");
		$select->from('postcodes', $cols)
		->where("pc_id = ?", $pc_id);
		$stmt = $db->query($select);
		$result = $stmt->fetchObject();
		return $result->postcode;
} 


public function getPostcode($db, $postcode){
		$select = new Zend_Db_Select($db);
		$cols = array('pc_id', 'postcode', 'area_name', 'areas', 'country_id', 'state_id', 'city_id');
		$select->from(array('pc' =>'postcodes'), $cols)->join(array('c' => 'countries'),'pc.country_id = c.country_id',array('country_id', 'country_name') )
		->join(array('st' => 'states'),'pc.state_id = st.state_id',array('state_id', 'state_name') )
		->join(array('ct' => 'cities'),'pc.city_id = ct.city_id',array('city_id', 'city_name') )
		->where("postcode LIKE ?", $postcode .'%');
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//$output = $this->listScript($results);
		return $results; 
	}
	
	
public function getAreaName($pc_id){
	$select = $this->select();
$select->from($this)->where('pc_id = ?', $pc_id);

$result = $this->fetchRow($select);
return $result->area_name;
	}	
	
	
	
	function listScript($results){
		
			 //format result for showing into a listbox
		$output = "";

		$output = '<div class="row-fluid">

                    <div class="span12">
						<div class="box gradient">

                                <div class="title">
                                    <h4>
                                        <span>List of Postcodes</span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
		if (count($results) > 0){
                                $output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                               <th>Postcodes</th>
											   <th>Main Area</th>
                                               <th>Other Areas</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Country</th>
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
                                     foreach ($results as $result){
											$output .= '<tr class="odd gradeX">
											    <td>'. $result['postcode'].'</td>
                                                <td>'. $result['area_name'].'</td>
                                                <td>'. $result['areas'].'</td>
                                                <td>'. $result['city_name'].'</td>
                                                <td>'. $result['state_name'].'</td>
                                         	    <td>'. $result['country_name'].'</td>
                                                 
                                            </tr>';
												}//end foreach
              }
			  else{
			$output = "No Postcode"	;
				}
			$output .=" </tbody>
                                       
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		return $output;
		}
} 

