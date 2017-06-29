<?php
class Application_Model_Countries extends Zend_Db_Table
{ 
    protected $_name = 'countries'; 
    protected $_primary = 'country_id';

//static function 
public static function getCountryNameStatic($db, $country_id){
	$select = new Zend_Db_Select($db);
		$cols = array("country_name");
		$select->from('countries', $cols)
		->where("country_id = ?", $country_id);
		$stmt = $db->query($select);
		$result = $stmt->fetchObject();
		return $result->country_name;
} 

public static function getAllCountriesList($db)
{
        //$lang_id = Zend_Registry::get("lang_id");
		$select = new Zend_Db_Select($db);
		$cols = array("country_id","country_name", "country_code");
		$select->from('countries', $cols)->order("country_name ASC");//->where("lang_id = ?", $lang_id);
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results;
}

public static function getAllStatesList($db)
{
$select = new Zend_Db_Select($db);
		$cols = array("state_id","state_name");
		$select->from('states', $cols)
		->order("state_name ASC")->Group("state_id");
		//echo $select;die;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results;
}

public static function getStateByCountryId($db,$country_id)
{
$select = new Zend_Db_Select($db);
		$cols = array("state_id","state_name");
		$select->from('states', $cols)
		->where("country_id = ?", $country_id)->order("state_name ASC")->Group("state_id");
		//echo $select;die;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
}
	
public static function getCountries($db, $size){
	$lang_id = Zend_Registry::get("lang_id");
		$select = new Zend_Db_Select($db);
		$cols = array("country_id","country_name", "country_code");
		$select->from('countries', $cols)->order("country_name ASC")->where("lang_id = ?", $lang_id);
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//format result for showing into a listbox
		$output = "";
		$output = "<select name='country' id='country' size='".$size."'  OnChange='getState();' class='dropdown form-control'>";
		$output .= "<option value= '0'>Select Country </option>";
                foreach($results as $result){
			$output .= "<option value= '" . $result['country_id']."'>". $result['country_name']."</option>";
		}
                $output .="</select>";
	return $output;
	}
	
	
	
//Countries list by language id for country controller 
public static function getList($db, $size){
       $select = new Zend_Db_Select($db);
		$cols = array("country_id","country_name", "country_code");
		$select->from('countries', $cols)->order("country_name ASC")->where("lang_id = ?", Zend_Registry::get("lang_id"));
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results;
	}

//Sorted list
public function sortedList(){
       $select = $this->select();
		$cols = array("country_id","country_name");
		$select->from('countries', $cols)->order("sort_order ASC");
		$result = $this->fetchAll($select);
		if(is_object($result)){
				return $result;
		}else return false; 
	}
	

public function getSearchCountry($country_name){
		$select = $this->select();
		$cols = array("country_id","country_name","country_code");
		$select->from($this, $cols)->where("country_name like ? ", "%" .$country_name);
		$result = $this->fetchAll($select);
		if(is_object($result)){
				return $result;
		}else return false; 
		}
/*public static function getStateCountry($country_name,$db){
		 $select = new Zend_Db_Select($db);
		$cols = array("country_id","country_name","country_code");
		$select->from('countries', $cols)->where("country_name like ? ", "%" .$country_name);
		$stmt = $db->query($select);
		$result = $stmt->fetchAll();
		return $result;
}
*/
 public function getCountryCode($country_code){
		$select = $this->select();
		$cols = array("country_id","country_name","country_code");
		$select->from($this, $cols)->where("country_code like ? ", "%" .$country_code );
		$result = $this->fetchAll($select);
		if(is_object($result)){
				return $result;
		}else return false; 
	}	

 public function editCountry($country_id){
$select = $this->select();
$select->from($this)->where('country_id = ?', $country_id);
$result = $this->fetchRow($select);
return $result;
}



public function checkCountry($country_name){
$select = $this->select();
$select->from($this)->where('country_name = ?', $country_name);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}	


public function checkCountryCode($country_code){
$select = $this->select();
$select->from($this)->where('country_code = ?', $country_code);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}

	
public static function getState($db, $country_id){
	
		$select = new Zend_Db_Select($db);
		$cols = array("state_id","state_name");
		$select->from('states', $cols)
		->where("country_id = ?", $country_id)->where("is_blocked = ?", 0)
		->order("state_name ASC")->Group("state_id");
		//echo $select;die;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		
        //format result for showing into a listbox
		$output = '<option value="0">Select State</option>';	
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['state_id']."'>". $result['state_name']."</option>";
			}			
		}
		return $output;
	}
	//used for brining results in countriesController getState function
public static function getStateAjaxed($db, $country_id){
	
		$select = new Zend_Db_Select($db);
		$cols = array("state_id","state_name");
		$select->from('states', $cols)
		->where("country_id = ?", $country_id)->where("is_blocked = ?", 0)
		->order("state_name ASC")->Group("state_id");
		//echo $select;die;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//format result for showing into a listbox
		$output = "";
	$state_heading = 'Select State';

		$output = "<select name='state' id='state'  OnChange='getCities();'  class='dropdown form-control'>";
		$output .= "<option value= ''>". $state_heading ."</option>";
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['state_id']."'>". $result['state_name']."</option>";
			}
			$output .="</select>";
		}
		return $output;
	}	
	
public static function getStatesList($db, $country_id){
	
		$select = new Zend_Db_Select($db);
		$cols = array("state_id","state_name");
		$select->from('states', $cols)
		->where("country_id = ?", $country_id)->where("is_blocked = ?", 0)->order("state_name ASC")->Group("state_id");
		//echo $select;die;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results;
	}
	
	//for front end searching, select boxes 
public static function getCitiesList($db, $state_id){
	
		$select = new Zend_Db_Select($db);
		$cols = array("city_id","city_name");
		$select->from('cities', $cols)
		->where("state_id = ?", $state_id)->order("city_name ASC")->Group("city_id");
		//echo $select;die;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results;
	}

	
	
	
	public static function getCities($db, $country_id, $state_id){
               
		$select = new Zend_Db_Select($db);
		$cols = array("city_id","city_name");
		$select->from('cities', $cols)
		->where("country_id = ?", $country_id)->where("state_id = ?", $state_id)->order("city_name ASC");
		
        $stmt = $db->query($select);
		$results = $stmt->fetchAll();
		
        //format result for showing into a listbox
		$output = '<option value="0">Select City</option>';
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['city_id']."'>". $result['city_name']."</option>";
			}
		}
		return $output;
	}
	
	public static function getCity($country_id, $state_id){
               
		$select = $this->select();
		$cols = array("city_id","city_name");
		$select->from('cities', $cols)
		->where("country_id = ?", $country_id)->where("state_id = ?", $state_id)->order("city_name ASC");
//echo $select;die;		
		$results = $this->fetchAll($select);
		//format result for showing into a listbox
		$output = "";
		$output = "<select name='city_id' id='city'  OnChange= 'getPostcodes();' class='dropdown form-control'>";
		$output .= "<option value= ''> Select City</option>";
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['city_id']."'>". $result['city_name']."</option>";
			}
			$output .="</select>";
		}
		else{
			$output = "<select name='state' id='state' class='dropdown form-control'>";
			$output .= "<option value= ''> Select City</option>";
			$output .="</select>";
		}
		return $output;
	}
	
	
	
	public static function getStateCities($db, $country_id, $state_id, $baseUrl){
	$lang_id = Zend_Registry::get("lang_id");
	
		$select = new Zend_Db_Select($db);
		$cols = array("city_id","city_name");
		$select->from('cities', $cols)
		->where("country_id = ?", $country_id)->where("state_id = ?", $state_id)->order("city_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//format result for showing into a listbox
		$output = "";

		$output ='<div class="row-fluid">

                    <div class="span12">
						<div class="box gradient">

                                <div class="title">
                                    <h4>
                                        <span>List of Cities</span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
								 $output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Cities Name</th>
                                                <th>Edit</th>
												<th>Block</th>
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
		if (count($results) > 0){
                              
                                     foreach ($results as $key=>$val){
								;
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['city_name'].'</td>
                                                <td><a class="btn btn-sm btn-warning float-right" href="'.$baseUrl.'/admin/cities/edit/city_id/'.$val["city_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$baseUrl.'/admin/cities/confirm-block/city_id/'.$val["city_id"].'">Block</a></td>
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                                <td>' . $val['city_name'].'</td>
                                               <td><a class="btn btn-sm btn-warning float-right" href="'.$baseUrl.'/admin/cities/edit/city_id/'.$val["city_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$baseUrl.'/admin/cities/confirm-block/city_id/'.$val["city_id"].'">Block</a></td>
                                              
                                            </tr>';
											 }
											}//end foreach
											$output .= '<tr><td colspan="9">
															</td>
															</tr>';
              }
			  else{
			$output .= "<tr><td colspan='2'>No Cities</td></tr>"	;
				}
			$output .=" </tbody>
                                       
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		return $output;
	}
	
	
	public static function getStatePostcodes($db, $country_id, $state_id){
	$lang_id = Zend_Registry::get("lang_id");
	
		$select = new Zend_Db_Select($db);
		$cols = array("pc_id","postcode");
		$select->from('postcodes', $cols)
		->where("country_id = ?", $country_id)->where("state_id = ?", $state_id)->order("postcode ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
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
								 $output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>PostCodes Name</th>
                                                
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
		if (count($results) > 0){
                              
                                     foreach ($results as $key=>$val){
								;
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['postcode'].'</td>
                                                
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                                <td>' . $val['postcode'].'</td>
                                               
                                                
                                            </tr>';
											 }
											}//end foreach
              }
			  else{
			$output .= "<tr><td colspan='2'>No Postcode</td></tr>"	;
				}
			$output .=" </tbody>
                                       
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		return $output;
	}
	
	

	public static function getPostcodes($db, $country_id, $state_id,$city_id){
               
		$select = new Zend_Db_Select($db);
		$cols = array("pc_id","postcode");
		$select->from('postcodes', $cols)
		->where("country_id = ?", $country_id)->where("state_id = ?", $state_id)->where("city_id = ?", $city_id)->order("postcode ASC");

//echo $select;die;		
$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//print_r($results);
		//format result for showing into a listbox
		$output = "";

		$output = "<select name='pc_id' id='postcode'  class='form-control'>";
		$output .= "<option value= '0'>Select Postcode</option>";
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['pc_id']."'>". $result['postcode']."</option>";
			}
			$output .="</select>";
		}
		else{
			$output .="</select>";
		}
		return $output;
	}

	public static function getPostcodesByAreaName($db, $country_id, $state_id,$city_id){
               
		$select = new Zend_Db_Select($db);
		$cols = array("pc_id","postcode");
		$select->from('postcodes', $cols)
		->where("country_id = ?", $country_id)->where("state_id = ?", $state_id)->where("city_id = ?", $city_id)->order("postcode ASC");

        $stmt = $db->query($select);
		$results = $stmt->fetchAll();
		
        $output = '<option value="0">Select Postcode</option>';
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['pc_id']."'>". $result['postcode']."</option>";
			}
		}
		return $output;
	}
	
	/*public static function getAllCities($db, $user_id, $page = 1){
	$lang_id = Zend_Registry::get("lang_id");
	$per_page = 5;
	$start_point = ($page * $per_page) - $per_page;
		$select = new Zend_Db_Select($db);
		$cols = array("city_id","city_name","city_code");
		$select->from('cities', $cols)->where("user_id = ?", $user_id)->where("lang_id = ?", $lang_id)
				->order("city_name ASC")->limitPage($page,$per_page);
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results;
		}	*/


	public static function getAllCities($db,$baseUrl){
		$select = new Zend_Db_Select($db);
		$cols = array("city_id","city_name");
		$select->from('cities', $cols)->order("city_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
	
		
		//format result for showing into a listbox
		$output = "";

		$output = '<div class="row-fluid">

                    <div class="span12">
						<div class="box gradient">

                                <div class="title">
                                    <h4>
                                        <span>List of Cities</span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
		if (count($results) > 0){
                                $output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Cities Name</th>
                                                <th> Edit </th>
												<th> Block </th>
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
                                     foreach ($results as $key=>$val){
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['city_name'].'</td>
                                               <td><a class="btn btn-sm btn-warning float-right" href="'.$baseUrl.'/admin/cities/edit/city_id/'.$val["city_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$baseUrl.'/admin/cities/confirm-block/city_id/'.$val["city_id"].'">Block</a></td>
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                              <td>' . $val['city_name'].'</td>
                                              <td><a class="btn btn-sm btn-warning float-right" href="'.$baseUrl.'/admin/cities/edit/city_id/'.$val["city_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$baseUrl.'/admin/cities/confirm-block/city_id/'.$val["city_id"].'">Block</a></td>
                                              </tr>';
											 }
											}//end foreach
											$output .= '<tr><td colspan="9" id="pagination">
															</td>
															</tr>';
              }
			  else{
			$output = "No Cities"	;
				}
			$output .=" </tbody>
                                       
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		return $output;
	}
	
	
	
	public static function getCityName($db, $country_id, $state_id,$city_id){
	$lang_id = Zend_Registry::get("lang_id");
	
		$select = new Zend_Db_Select($db);
		$cols = array("city_name");
		$select->from('cities', $cols)
		->where("country_id = ?", $country_id)->where("state_id = ?", $state_id)->where("city_id = ?", $city_id)->where("lang_id = ?", $lang_id)->order("city_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetch();
		return $results;
	}
	
public function getCountry($country_id){
$lang_id = Zend_Registry::get('lang_id');
$select = $this->select();
$select->from($this)->where('country_id = ?', $country_id)->where('lang_id = ?', $lang_id);
	
$result = $this->fetchRow($select);	
return $result;	
} 


	public static function getCountriesCities($db, $country_id, $baseUrl){
		$select = new Zend_Db_Select($db);
		$cols = array("city_id","city_name");
		$select->from('cities', $cols)
		->where("country_id = ?", $country_id)->order("city_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();//->toArray();
		return $results;
		//return implode(" ",$results);
		//return;
		//format result for showing into a listbox
		$output = "";
		
		$output = '<div class="row-fluid">
                    <div class="">
						<div class="box gradient">
                                <div class="title">
                                    <h4>
                                        <span>List of Cities </span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
								$output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Cities Name</th>
												<th>Edit</th>
												<th>Block</th>
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
		if (count($results) > 0){
                                $output .= '<?php if (count($this->paginator)){ ?>';
                                     foreach ($results as $key=>$val){
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['city_name'].'</td>
												<td><a class="btn btn-sm btn-warning float-right" href="'.$baseUrl.'/admin/cities/edit/city_id/'.$val["city_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$baseUrl.'/admin/cities/confirm-block/city_id/'.$val["city_id"].'">Block</a></td>
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                                <td>' . $val['city_name'].'</td>
                                                <td><a class="btn btn-sm btn-warning float-right" href="'.$baseUrl.'/admin/cities/edit/city_id/'.$val["city_id"].'">Edit</a></td>
											   <td><a class="btn btn-sm btn-info float-right" href="'.$baseUrl.'/admin/cities/confirm-block/city_id/'.$val["city_id"].'">Block</a></td>
                                              
                                            </tr>';
											 }
											}//end foreach
											$output .= '<tr><td colspan="9">
															<?php echo $this->paginationControl($this->paginator, "Sliding", "my_pagination_control.phtml"); ?>
															</td>
															</tr><?php }else{ ?>
											
											
															<tr><td colspan="7"><h4>There are no cities created! Please create one</h4></td></tr>
															<?php } ?>';
              }
			  else{
			$output .= "<tr><td colspan='2'>No Cities</td></tr>"	;
				}
			$output .=" </tbody>
                                        
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		return $output;
	}
	public static function getCountriesPostcodes($db, $country_id){
		$select = new Zend_Db_Select($db);
		$cols = array("pc_id","postcode");
		$select->from('postcodes', $cols)
		->where("country_id = ?", $country_id)->order("postcode ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();//->toArray();
		//return implode(" ",$results);
		//return;
		//format result for showing into a listbox
		$output = "";
		
		$output = '<div class="row-fluid">
                    <div class="">
						<div class="box gradient">
                                <div class="title">
                                    <h4>
                                        <span>List of Postcodes </span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
								$output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Postcodes Name</th>
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
		if (count($results) > 0){
                                
                                     foreach ($results as $key=>$val){
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['postcode'].'</td>
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                                <td>' . $val['postcode'].'</td>
                                                
                                            </tr>';
											 }
											}//end foreach
              }
			  else{
			$output .= "<tr><td colspan='2'>No Postcodes</td></tr>"	;
				}
			$output .=" </tbody>
                                        
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		return $output;
	}
	
	public static function getAllStates($db, $user_id){
	$lang_id = Zend_Registry::get("lang_id");
	
		$select = new Zend_Db_Select($db);
		$cols = array("state_id","state_name");
		$select->from('states', $cols)->where("lang_id = ?", $lang_id)->order("state_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//format result for showing into a listbox
		$output = "";

		$output = '<div class="row-fluid">
                    <div class="span12">
						<div class="box gradient">
                                <div class="title">
                                    <h4>
                                        <span>List of States</span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
		if (count($results) > 0){
                                $output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>State Name</th>
												<th>Edit</th>
												
                                              </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
                                     foreach ($results as $key=>$val){
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['state_name'].'</td>
                                              <td><a href="#'. $val['state_id'].'">Edit</a></td>
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                                <td>' . $val['state_name'].'</td>
												
												
												
                                                <td><a href="#'. $val['state_id'].'">Edit</a></td>
                                            </tr>';
											 }
											}//end foreach
              }
			  else{
			$output = "No States found"	;
				}
			$output .=" </tbody>
                                       
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		return $output;
	}
	
	public static function getCountryStates($db, $country_id){
	$lang_id = Zend_Registry::get("lang_id");
	
		$select = new Zend_Db_Select($db);
		$cols = array("state_id","state_name");
		$select->from('states', $cols)
		->where("country_id = ?", $country_id)->where("lang_id = ?", $lang_id)->order("state_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//return $results;
		//format result for showing into a listbox
		$output = "";

		$output = '<div class="row-fluid">

                    <div class="span12">
						<div class="box gradient">

                                <div class="title">
                                    <h4>
                                        <span>List of States</span>
                                    </h4>
                                </div>
                                <div class="content noPad clearfix">';
								$output .= '<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                                <th>State Name</th>
                                                <th>Edit</th>
                                            </tr>
                                        </thead>';
										
					
							$output .= '<tbody>';
		if (count($results) > 0){
                                
                                     foreach ($results as $key=>$val){
											 if($key%2 != 0){
											$output .= '<tr class="odd gradeX">
                                                <td>'. $val['state_name'].'</td>
                                                <td><a href="#'. $val['state_id'].'">Edit</a></td>
                                              
                                            </tr>';
											 }else{
                                            $output .= '<tr class="even gradeC">
                                                <td>' . $val['state_name'].'</td>
                                                <td><a href="#'. $val['state_id'].'">Edit</a></td>
                                            </tr>';
											 }
											}//end foreach
              }
			  else{
			$output .= "<tr><td colspan='2'>No States are included in this country</td></tr>"	;
				}
			$output .=" </tbody>
                                        
                                    </table></div>
								
						</div><!-- End .box -->

                    </div><!-- End .span12 -->

                </div><!-- End .row-fluid -->";
		
		
		return $output;
	}
	
	
	
/* MUSAVIR CODE */
/* STARTED 5-9-2014 KUALA LUMPUR MALAYSIA*/
public function add($formData){
        $country_name = $formData['country_name'];
    	$country_code = $formData['country_code'];
    	$user_id = $formData['user_id'];
		
        $data = array("country_name" => $country_name, "country_code" => $country_code,"user_id" => $user_id,"lang_id" => Zend_Registry::get("lang_id"));
		$this->id = $this->insert($data);
		if($this->id){
    return  "<div class='alert alert-success'>".$country_name ." Added Successfully </div>" ;
	}  else {
    return "Ops something gone wrong!";
	}
}	


public function edit($formData){
        $country_name = $formData['country_name'];
    	$country_code = $formData['country_code'];
	   
    	//$user_id = $formData['user_id'];
		
        $data = array("country_name" => $country_name, "country_code" => $country_code);
		//update('table', $data, $where);
		
		$where = "country_id = " . $formData['country_id'];
		$this->id = $this->update($data, $where);
		if($this->id){
    return  "<div class='alert alert-success'>".$country_name ." Added Successfully </div>" ;
	}  else {
    return "Ops something gone wrong!";
	}
}	

public function getAllCountries(){
$select = $this->select();
$select->from($this);
$result = $this->fetchAll($select);
return $result;
}
	
	
	

} 