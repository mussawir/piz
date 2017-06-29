<?php
class Application_Model_Members extends Zend_Db_Table
{ 
    protected $_name = 'members'; 
    protected $_primary = 'member_id';
	private $result;

 
public function addMember($formData){
	unset($formData['pwd_confirm']);
	unset($formData['submit']);
	//unset($formData['package']);
	unset($formData['privacy']);
	unset($formData['subtotal']);
	unset($formData['brochure_limit']);
	
	try{
	$this->member_id = $this->insert($formData);
	}catch(exception $e){
	return false;
	}

	if($this->member_id){ 
    return  $this->member_id  ; 
	}  else {
    return false;
	}
} 

public function addCustomer($formData) {
 $data = array('first_name' => $formData['first_name'],
				'last_name' => $formData['last_name'],
				'email' => $formData['email'],
				'role_id' => 1,
				'pwd' => md5 ($formData['password'])
				);
 
 $result = $this->insert($data); 
		 if($result){
			return  true;
		}  else {
			return false;
		}
   }

public function getMembers(){
$select = $this->select();
$select->from($this, array('member_id','first_name','last_name','contact_number'));
$result = $this->fetchAll($select);
return $result;
 } 
 
//select fields of members 
public function getMembersList(){
    $select = $this->select();
    $select->from($this);
    $result = $this->fetchAll($select);
    return $result;
 } 
public function getMarketerList(){
    $select = $this->select();
    $select->from($this)->where('role_id = ?',3);
    $result = $this->fetchAll($select);
    return $result;
 } 

public function getDetails($member_id){
	$select = $this->select();
	$select->from($this)->where('member_id = ?',$member_id);
	$result = $this->fetchRow($select);
	return $result;
} 



public function updatMember($formData){
//print_r($formData);die;
         $data = array(
		"member_id" => $formData['member_id'],
		);
	$where = "member_id = ". $formData['member_id'];
		
$this->member_id = $this->update($data,$where);
if($this->member_id){ 
    return  $this->member_id  ;
}  else {
    return "Some error in saving record";
}
}

 public function updatePass($formData){
 $where = "member_id=".$formData['member_id'];
 $email = $formData['email'];
 $password = $formData['pwd'];
 $data = array('email' => $email, 'pwd' => $password);
 $result = $this->update($data,$where);
 return $result;
 }


/* Specially for member dashboard change password */
 public function passUpdate($member_id, $password){
 $where = "member_id= ".$member_id;
 $data = array('pwd' => md5($password));
 $result = $this->update($data,$where);
 if ($result > 0) {
	 	 return true; 
 }else{
 	 return false; 
	 }
	  }
	  
	  
	  public function updateBasic($formData)
{
$data = array( 
              "first_name" => $formData['first_name'],
				"last_name" => $formData['last_name'], 
				
				'office_contact_number' => $formData['office_contact_number'], 
				'contact_number' => $formData['contact_number']);
$where = "member_id = " . $formData['member_id'];
$this->id = $this->update($data, $where);
if($this->id == 0 || $this->id > 0 ){return  true; }else{  return  false;}
}

	  
	  
public function updateAddress($formData)
{
$data = array(
	"street_address" => $formData['street_address'],
	"country_id" => $formData['country'],
	"state_id" => $formData['state'],
	"city_id" => $formData['city_id'],
	"pc_id" => $formData['pc_id']); 

$where = "member_id = " . $formData['member_id'];	
$this->id = $this->update($data, $where);

if($this->id == 0 || $this->id > 0){
    return  "<div class='alert alert-success'><strong>Address Info Updated</strong> </div>";}  
	else 
	{
    return  "<div class='alert alert-danter'><strong>Try again! Error during saving record</strong> </div>";}
	
}

/* public function deleteRecord($member_id){
     
  $where = "member_id = " .(int) $member_id;
     	$id = $this->delete($where);
     	if ($id) {
		    $this->result = true;
       }else{
		    $this->result = false;
       	}
        return $id;
    }*/


 //this function is used for checking Login ID
public function checkEmail($email){
$select = $this->select();
$select->from($this, array('email'))->where("email = ?", $email);
//echo $select; die;
$result = $this->fetchRow($select);
if(is_object($result)) return true;
else return false;
 }
 

    // add marketer
    public function add($formData) 
    {
        $member_id = $this->insert($formData); 
		 if($member_id){
			return $member_id;
		}  else {
			return null;
		}
   }
   
   public function getMembersByUser($user_id){
        $select = $this->select();
        $select->from($this)->where('user_id = ?',$user_id);
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getMembersByParent($parent_id){
        $select = $this->select();
        $select->from($this)->where('parent_id = ?',$parent_id);
        $result = $this->fetchAll($select);
        return $result;
   }
   
    public function getPaidMembers(){
        $select = $this->select();
        $select->from($this)->where('role_id = ?',3);
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getAllMembers(){
        $select = $this->select();
        $select->from($this);
        $result = $this->fetchAll($select);
        return $result;
    }
    
    public function getRootId($member_id)
    {
        $select = $this->select();
        $select->from($this, array('root_id', 'member_id'))->where('member_id = ?',$member_id);
        $result = $this->fetchRow($select);
        return $result;
    }
    public function loginMember($member_id)
    {
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id);
        $result = $this->fetchRow($select);
        return $result;
    }
    public function updateRole($member_id, $role_id){
         $where = "member_id= ".$member_id;
         $data = array('role_id' => $role_id);
         $result = $this->update($data,$where);
         if ($result > 0) {
            return true; 
         }else{
            return false; 
         }
    }
    
    public function getMemberByCode($verification_code){
        $select = $this->select();
        $select->from($this)->where('verification_code = ?',$verification_code);
        $result = $this->fetchRow($select);
        return $result;
    }
    
    public function updateVerification($member_id, $dir_name, $password)
    {
         $where = "member_id= ".$member_id;         
         $result = $this->update(array('is_verified' => 1, 'dir_name'=>$dir_name, 'pwd'=>$password), $where);
         if ($result > 0) {
            return true; 
         }else{
            return false; 
         }
    }
    
    public function getMemberDirectory($member_id){
        $select = $this->select();
        $select->from($this, array('member_id','dir_name'))->where('member_id = ?',$member_id);
        $result = $this->fetchRow($select);
        return $result;
    }
    
    public function getMemberAdsLimit($member_id)
    {
        $select = $this->select();
        $select->from($this, array('ad_boxes_limit'))->where('member_id = ?',$member_id);
        $result = $this->fetchRow($select);
        return $result;
    }
    
    public function getBrochureLimit($member_id){
        $select = $this->select();
        $select->from($this, array('member_id','brochure_limit'))->where('member_id = ?',$member_id);
        $result = $this->fetchRow($select);
        return $result;
    }
}