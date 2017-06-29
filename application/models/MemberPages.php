<?php
class Application_Model_MemberPages extends Zend_Db_Table
{ 
    protected $_name = 'member_pages'; 
    protected $_primary = 'bd_list_id';
	private $result;


public function add($formData){

$expiry_date = date('Y/m/d', strtotime('+1 year'));
$expiry_date = date('Y/m/d', strtotime("+1 months", strtotime($expiry_date)));
// $business_name = isset($formData['business_name']) ? $formData['business_name'] : null;
 // 'promocode' => $formData['promocode']
$data 	= array('member_id' => $formData['member_id'], 'email' => $formData['email'], 'package'=> $formData['package'],
                 "date_created" =>  date("Y/m/d"), 'master_p_id'=>$formData['master_p_id']); 

	
$bd_list_id = $this->insert($data);
if($bd_list_id){ 
    return  $bd_list_id  ; 
}  else {
    return false;
}
}

    public function updatePackage($formData){
    	$expiry_date =  date('Y/m/d', strtotime('+1 year'));
    	$data = array("package" => $formData['package'],"promocode" => $formData['promocode'],
    	"expiry_date" => $expiry_date);
    	
        $where = "bd_list_id = " . $formData['bd_list_id'];	
        $this->id = $this->update($data, $where);
        if($this->id == 0 || $this->id > 0 ){
        return  "<div class='alert alert-success'><strong>Package is Updated</strong> </div>";}  
    	else 
    	{
        return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";}
	
	}

public function getMembers(){
$select = $this->select();
$select->from($this, array('member_id','first_name','last_name','contact_number'));
$result = $this->fetchAll($select);
return $result;
 
 }
/* Get Member lists */

public function getMemberBdlist($member_id){
$select = $this->select();
$select->from($this)->where('member_id = ?', $member_id);
$result = $this->fetchAll($select);
return $result;
}


public function getDetails($bdlist_id){
$select = $this->select();
$select->from($this)->where('bd_list_id = ?', $bdlist_id);
$result = $this->fetchRow($select);
return $result;
}

//FOR ADMIN/categories CONTROLLER
public function deleteRecord($bdlist_id){
     
 $where = "bd_list_id = " .(int) $bdlist_id;
     	$id = $this->delete($where);
     	if ($id) {
		    $this->result = true;
       }else{
		    $this->result = false;
       	}
        return $id;
    }

    public function getFreelist($member_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?', $member_id)->where('package = ?', 0);
        //$select->from($this)->where('member_id = ?', $member_id)->where('no_of_pages = ?', 1);
        $result = $this->fetchAll($select)->toArray();
        if(count($result) > 0){
    	return true;
    	}else{
    		return false;
   		}
    }


public function findMemberAds($member_id, $business_name){
$select = $this->select();
$select->from($this)->where('member_id = ?', $member_id)->where('business_name LIKE ?', $business_name ."%");
$result = $this->fetchAll($select);
return $result;
}


public function getMemberAd($ad_id){
$select = $this->select();
$select->from($this)->where('bd_list_id = ?', $ad_id);
$result = $this->fetchRow($select);
return $result;
}

//FOR ADMIN/LISTING CONTROLLER
public function getBdlist(){
$select = $this->select();
$select->from($this);
$result = $this->fetchAll($select);
return $result;
}

//FOR ADMIN/LISTING CONTROLLER
public function getAdPackage($ad_id, $member_id){
$select = $this->select();
$select->from($this)->where('bd_list_id = ?', $ad_id)->where('member_id = ?', $member_id);
$result = $this->fetchRow($select);

if($result){
return $result->package;
}else{
return 0;	
}

}



//FOR ADMIN/LISTING CONTROLLER
public function getFeaturedBdlist(){
$select = $this->select();
$select->from($this)->where('is_featured = ?', 1);
$result = $this->fetchAll($select);
return $result;
}


// FOR ADMIN/LISTING CONTROLLER
public function findAds($business_name){
$select = $this->select();
$select->from($this)->where('business_name LIKE ?', $business_name ."%");
$result = $this->fetchAll($select);
return $result;
}


// FOR ADMIN/LISITNG CONTROLLER 
public function updateFeatured($bd_list_id, $status){

$data = array("is_featured" => $status);
$where = "bd_list_id = " . $bd_list_id;
$this->id = $this->update($data, $where);

if($this->id == 0 || $this->id > 0 ){
    return  "<div class='alert alert-success'><strong>Updated</strong> </div>";}  
	else 
	{
    return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";
	}
	
	}  



public function getAd($ad_id){
$select = $this->select();
$select->from($this)->where('bd_list_id = ?', $ad_id);
$result = $this->fetchRow($select);
return $result;
}


 
    public function updateBasic($formData)
    {
    	$data = array("business_name" => $formData['business_name'],
    	"email" => $formData['email'],
    	"telephone" => $formData['telephone'],
    	"mobile" => $formData['mobile'],
    	"website" => $formData['website'],
    	"biz_size" => $formData['biz_size'],
    	"area_serviced" => $formData['area_serviced']);
    	
        $where = "bd_list_id = " . $formData['bd_list_id'];
        $this->id = $this->update($data, $where);
        
        if($this->id == 0 || $this->id > 0 ){
            return  "<div class='alert alert-success'><strong>Record updated successfully!</strong> </div>";}  
        	else 
        	{
        return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";}
    	
	}

    public function updateAddress($formData){
    	$data = array(
    	"street_address" => $formData['street_address'],
    	"country_id" => $formData['country'],
    	"state_id" => $formData['state'],
    	"city_id" => $formData['city_id'],
    	"pc_id" => $formData['pc_id'],
    	"area" => $formData['area']	); 
    
        $where = "bd_list_id = " . $formData['bd_list_id'];	
        $this->id = $this->update($data, $where);
        
        if($this->id == 0 || $this->id > 0){
        return  "<div class='alert alert-success'><strong>Record updated successfully!</strong> </div>";}  
    	else 
    	{
        return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";}
	
	}
 
public function updateBiz($formData){
	$data = array("monday" => $formData['monday'],"tuesday" => $formData['tuesday'],
	"wednesday" => $formData['wednesday'],
	"thursday" => $formData['thursday'],
	"friday" => $formData['friday'],
	"saturday" => $formData['saturday'],
	"sunday" => $formData['sunday'],
	"short_description" => $formData['short_description'],
	"detailed_description" => $formData['detailed_description'],
	"logo" => $formData['logo']);
	
$where = "bd_list_id = " . $formData['bd_list_id'];	
$this->id = $this->update($data, $where);

if($this->id == 0 || $this->id > 0 ){
    return  "<div class='alert alert-success'><strong>Business Info and Logo is  Updated</strong> </div>";}  
	else 
	{
    return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";}
	
	} 
 
 /** Update Image functions **/
 public function updateImage1($image, $ad_id){
$data = array("image1" => $image);
$where = "bd_list_id = " . $ad_id;
$this->id = $this->update($data, $where);
if($this->id == 0 || $this->id > 0 ){return  true; }else{  return  false;}
 }
 
  public function updateImage2($image, $ad_id){
$data = array("image2" => $image);
$where = "bd_list_id = " . $ad_id;
$this->id = $this->update($data, $where);
if($this->id == 0 || $this->id > 0 ){return  true; }else{  return  false;}
 }
 
  public function updateImage3($image, $ad_id){
$data = array("image3" => $image);
$where = "bd_list_id = " . $ad_id;
$this->id = $this->update($data, $where);
if($this->id == 0 || $this->id > 0 ){return  true; }else{  return  false;}
 }

  public function updateImage4($image, $ad_id){
$data = array("image4" => $image);
$where = "bd_list_id = " . $ad_id;
$this->id = $this->update($data, $where);
if($this->id == 0 || $this->id > 0 ){return  true; }else{  return  false;}
 }

  public function updateImage5($image, $ad_id){
$data = array("image5" => $image);
$where = "bd_list_id = " . $ad_id;
$this->id = $this->update($data, $where);
if($this->id == 0 || $this->id > 0 ){return  true; }else{  return  false;}
 }

  public function updateImage6($image, $ad_id){
$data = array("image6" => $image);
$where = "bd_list_id = " . $ad_id;
$this->id = $this->update($data, $where);
if($this->id == 0 || $this->id > 0 ){return  true; }else{  return  false;}
 }
 
 
public function updatePv($formData){

$data = array("video_link" => $formData['video_link'],"special_offer" => $formData['special_offer'],"promotion_image" => $formData['promotion_image']);
$where = "bd_list_id = " . $formData['bd_list_id'];	
$this->id = $this->update($data, $where);

if($this->id == 0 || $this->id > 0 ){
    return  "<div class='alert alert-success'><strong>Promo & Video link Info is  Updated</strong> </div>";}  
	else 
	{
    return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";}
	
	}  
 
public function updateCategories($member_id, $bd_list_id, $categories){

$data = array("categories" => $categories);
$where = "bd_list_id = " . $bd_list_id;
$this->id = $this->update($data, $where);

if($this->id == 0 || $this->id > 0 ){
    return  "<div class='alert alert-success'><strong>Updated</strong> </div>";}  
	else 
	{
    return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";}
	
	}  
 
 
/* Not is use */ 
public function updateImages($formData){
	$data = array(
	"image1" => $formData['image1'],
	"image2" => $formData['image2'],
	"image3" => $formData['image3'],
	"image4" => $formData['image4'],
	"image5" => $formData['image5'],
	"image6" => $formData['image6'],
	);
	
$where = "bd_list_id = " . $formData['bd_list_id'];	
$this->id = $this->update($data, $where);

if($this->id == 0 || $this->id > 0 ){
    return  "<div class='alert alert-success'><strong>Business Info and Logo is  Updated</strong> </div>";}  
	else 
	{
    return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";}
	
	}  
 
 //this function is used for checking Login ID
public function checkEmail($email){
$select = $this->select();
$select->from($this, array('email'))->where("email = ?", $email);
//echo $select; die;
$result = $this->fetchRow($select);
if(is_object($result)) return true;
else return false;
 }

public function getListings1($db,$country_id, $state_id, $city_id, $business_name, $area){
		$select = new Zend_Db_Select($db);
		$cols = array('bd_list_id', 'package', 'business_name', 'street_address','short_description', 'logo','area_serviced','country_id', 'state_id', 'city_id', 'pc_id', 'categories');
		$select->from(array('bl' =>'bd_list'), $cols)
		->joinLeft(array('c' => 'countries'),'bl.country_id = c.country_id',array('country_id', 'country_name') )
		->joinLeft(array('st' => 'states'),'bl.state_id = st.state_id',array('state_id', 'state_name') )
		->joinLeft(array('ct' => 'cities'),'bl.city_id = ct.city_id',array('city_id', 'city_name') )
	//	->joinLeft(array('mlc' => 'member_list_cats'),'bl.bd_list_id = mlc.bd_list_id',array('category_id') )
		->where("bl.is_blocked = ?", 0)->order("package DESC")
		->where("business_name like ?", "%". $business_name ."%")
		->where("area like ?", "%". $area ."%")
		->where("c.country_id = ?",  $country_id)
		->where("st.state_id = ?",  $state_id)
		->where("ct.city_id = ?",  $city_id)	;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results; 
}
 
public function getListings2($db,$country_id, $state_id, $city_id, $business_name){
		$select = new Zend_Db_Select($db);
		$cols = array('bd_list_id', 'package', 'business_name', 'street_address','short_description', 'logo','area_serviced','country_id', 'state_id', 'city_id', 'pc_id', 'categories');
		$select->from(array('bl' =>'bd_list'), $cols)
		->joinLeft(array('c' => 'countries'),'bl.country_id = c.country_id',array('country_id', 'country_name') )
		->joinLeft(array('st' => 'states'),'bl.state_id = st.state_id',array('state_id', 'state_name') )
		->joinLeft(array('ct' => 'cities'),'bl.city_id = ct.city_id',array('city_id', 'city_name') )
	//	->joinLeft(array('mlc' => 'member_list_cats'),'bl.bd_list_id = mlc.bd_list_id',array('category_id') )
		->where("bl.is_blocked = ?", 0)->order("package DESC")
		->where("business_name like ?", "%". $business_name ."%")
		->where("c.country_id = ?",  $country_id)
		->where("st.state_id = ?",  $state_id)
		->where("ct.city_id = ?",  $city_id)	;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results; 
}
 
 public function getListings3($db,$country_id, $state_id,$business_name){
		$select = new Zend_Db_Select($db);
		$cols = array('bd_list_id', 'package', 'business_name', 'street_address','short_description', 'logo','area_serviced','country_id', 'state_id', 'city_id', 'pc_id', 'categories');
		$select->from(array('bl' =>'bd_list'), $cols)
		->joinLeft(array('c' => 'countries'),'bl.country_id = c.country_id',array('country_id', 'country_name') )
		->joinLeft(array('st' => 'states'),'bl.state_id = st.state_id',array('state_id', 'state_name') )
		->joinLeft(array('ct' => 'cities'),'bl.city_id = ct.city_id',array('city_id', 'city_name') )
	//	->joinLeft(array('mlc' => 'member_list_cats'),'bl.bd_list_id = mlc.bd_list_id',array('category_id') )
		->where("bl.is_blocked = ?", 0)->order("package DESC")
		->where("business_name like ?", "%". $business_name ."%")
		->where("c.country_id = ?",  $country_id)
		->where("st.state_id = ?",  $state_id);
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results; 
}

 public function getListings4($db,$country_id,$business_name){
		$select = new Zend_Db_Select($db);
		$cols = array('bd_list_id', 'package', 'business_name', 'street_address','short_description', 'logo','area_serviced','country_id', 'state_id', 'city_id', 'pc_id', 'categories');
		$select->from(array('bl' =>'bd_list'), $cols)
		->joinLeft(array('c' => 'countries'),'bl.country_id = c.country_id',array('country_id', 'country_name') )
		->joinLeft(array('st' => 'states'),'bl.state_id = st.state_id',array('state_id', 'state_name') )
		->joinLeft(array('ct' => 'cities'),'bl.city_id = ct.city_id',array('city_id', 'city_name') )
	//	->joinLeft(array('mlc' => 'member_list_cats'),'bl.bd_list_id = mlc.bd_list_id',array('category_id') )
		->where("bl.is_blocked = ?", 0)->order("package DESC")
		->where("business_name like ?", "%". $business_name ."%")
		->where("bl.country_id = ?",  $country_id);
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results; 
}
  
public function getListings5($db, $business_name){
		$select = new Zend_Db_Select($db);
		$cols = array('bd_list_id', 'package', 'business_name', 'street_address','short_description', 'logo','area_serviced','country_id', 'state_id', 'city_id', 'categories');
		$select->from(array('bl' =>'bd_list'), $cols)
		->joinLeft(array('c' => 'countries'),'bl.country_id = c.country_id',array('country_id', 'country_name') )
		->joinLeft(array('st' => 'states'),'bl.state_id = st.state_id',array('state_id', 'state_name') )
		->joinLeft(array('ct' => 'cities'),'bl.city_id = ct.city_id',array('city_id', 'city_name') )
		->where("bl.is_blocked = ?", 0)->order("package DESC")
		->where("business_name like ?", "%". $business_name ."%");
		;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results; 
}

public function getListingByCategory($db, $category_name){
		$select = new Zend_Db_Select($db);
		$select->from(array('bl' =>'bd_list'))
		->joinLeft(array('c' => 'countries'),'bl.country_id = c.country_id',array('country_id', 'country_name') )
		->joinLeft(array('st' => 'states'),'bl.state_id = st.state_id',array('state_id', 'state_name') )
		->joinLeft(array('ct' => 'cities'),'bl.city_id = ct.city_id',array('city_id', 'city_name') )
		->where("bl.is_blocked = ?", 0)->order("package DESC")
		->where("categories like ?",  $category_name ."%")
		->orWhere("categories like ?", "%". $category_name )
		->orWhere("categories like ?", "%". $category_name ."%")
		->orWhere("categories = ?",  $category_name );
		;
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results; 
}
    
    public function getPagesByMasterId($master_id)
    {
        $select = $this->select();
        $select->from($this)->where('master_p_id = ?', $master_id)->where('is_blocked = ?', 0);
        $result = $this->fetchAll($select);
        return $result;
    }
    
    public function addPages($formData)
    {
        $bd_list_id = $this->insert($formData);
        if($bd_list_id){ 
            return  $bd_list_id  ; 
        }  else {
            return false;
        }
    }
    
    public function addExpireDate($bd_list_id)
    {
        $expiry_date =  date('Y/m/d', strtotime('+1 year'));
        
        $data = array("expiry_date" => $expiry_date);
        $where = "bd_list_id = " . $bd_list_id;
        $this->id = $this->update($data, $where);
        
        if($this->id == 0 || $this->id > 0 ){
            return true;  
        }  
        else 
       	{
            return false;
       	}	
	}
    
    public function updateLogo($bd_list_id, $logo_img)
    {
        $data = array("logo" => $logo_img);
        $where = "bd_list_id = " . $bd_list_id;
        $this->id = $this->update($data, $where);
        
        if($this->id == 0 || $this->id > 0 ){
            return true;  
        } else {
            return false;
       	}	
	}
    
    public function updateBanner($bd_list_id, $banner_img)
    {
        $data = array("image1" => $banner_img);
        $where = "bd_list_id = " . $bd_list_id;
        $this->id = $this->update($data, $where);
        
        if($this->id == 0 || $this->id > 0 ){
            return true;  
        } else {
            return false;
       	}	
	}
}