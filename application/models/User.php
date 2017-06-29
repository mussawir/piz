<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_User extends Zend_Db_Table
{ 
    protected $_name = 'users';
    protected $_primary = 'user_id';
    protected $result = null;


public function getUser($id){
$select = $this->select();
$select->from($this)->where("user_id = ?", $id);
$result = $this->fetchRow($select);
return $result;
 }
 
 /* Specially for user change password */
 public function passUpdate($id, $password){
 $where = "user_id= ".$id;
 $data = array('pwd' => md5($password));
 $result = $this->update($data,$where);
 if ($result > 0) {
	 	 return true; 
 }else{
 	 return false; 
	 }
	  }
  
 // for get all users
 	public function allUsers(){
	$select = $this->select();
	$select->from($this);
	$this->result = $this->fetchAll($select);
     return count($this->result);
	}
 
 
 //this function is used for finding user
 public function findUser($name){
$select = $this->select();
$select->from($this)->where("email like ? ", "%" .$name . "%")->orWhere("user_name like ? ", "%" .$name . "%");
$result = $this->fetchAll($select);
return $result;
 }
 
 
 //this function is used for checking email
public function checkEmail($email){
$select = $this->select();
$select->from($this, array('email'))->where("email = ?", $email);
//echo $select; die;
$result = $this->fetchRow($select);
if(is_object($result)) return true;
else return false;
 }
 

 
public function getAllUsers(){
$select = $this->select();
$select->from($this);
$result = $this->fetchAll($select);
return $result;
 } 
 
 
public function updateUser($formData){
$data = array('email' => $formData['email'],
			'user_name' =>  $formData['user_name'],
			'role_id' => $formData['role']);
$where = "user_id= ". $formData['user_id'];
$result = $this->update($data, $where);
if($result){
			return  "<div class='alert alert-success'>User Updated Successfully </div>" ;
		}  else {
			return "<div class='alert alert-danger'>Some error in saving record.</div>";
		}
  }

public function activeUser($db, $id){ 
	$data = array('is_active'=> 1);
	$result = $this->update($data,$db->quoteInto("user_id = ?", $id));
	if($result)
		return true;
	else
		return false;
}

	public function blockUser($db, $id){ 
	$data = array('is_active'=> 0);
	$result = $this->update($data,$db->quoteInto("user_id = ?", $id));
	if($result)
		return true;
	else
		return false;
}  

public function updatePassword($formData){
$password = md5($formData['password']);
$data = array('password' => $password);
$result = $this->update($data,null);
return $result;
  }
  
 public function updatePass($formData){
 $where = "user_id=".$formData['user_id'];
 $email = $formData['email'];
 $password = $formData['pwd'];
 $data = array('email' => $email, 'pwd' => $password);
 $result = $this->update($data,$where);
 return $result;
 }
  
public function block($formData){
$data = array('block' => $formData['block']);
$result = $this->update($data,null);
return $result;
  }


//this function is called when a password recover page runs
public function recoverPassword($data){
$where = "email= '". $data['email']."'";
$result = $this->update($data, $where);
if($result){
    return true;
};
  }
  
  
  //for add user
public function addUser($formData) {
 $data = array('email' => $formData['email'],
				'role_id' => $formData['role'],
				'date_registered' => $formData['date_registered'],
				'pwd' => md5($formData['password']),
				'user_name' => $formData['user_name']);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>User ". $formData['user_name'] ." Added Successfully </div>" ;
		}  else {
			return "<div class='alert alert-danger'>Some error in saving record.</div>";
		}
   }
  
    //for delete user
  public function deleteUsers($user_id){
        $where = "user_id = " . (int) $user_id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }
 }
}