<?php

class Application_Model_Photos extends Zend_Db_Table
{ 
    protected $_name = 'photos';
    protected $_primary = 'photo_id';
    protected $result = null;
	

	public function getPhotoByID($id){
	 $select = $this->select();
	 $select->from($this)->where("photo_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }
 
 // add new banner
public function addPhoto($formData) {
	
 $data = array('photo_name' => $formData['photo_name'],'caption' => $formData['caption']
 	,'description' => $formData['description'],'link' => $formData['link'],
 	'pg_cat_id' => $formData['category']);
 
 $result = $this->insert($data); 
		 if($result){
			return  "<div class='alert alert-success'>New Photo Added Successfully </div>" ;
		}  else {
			return "Some error in saving record";
		}
   }
 
 
     // for get all Photos by decending order
    public function getAllPhotos(){
    	$select = $this->getAdapter()->select();
    	$select->from(array('ph' => 'photos'), array('*'))
        ->joinLeft(array('cpg' => 'categories_pg'),'ph.pg_cat_id = cpg.pg_cat_id',array('cpg.category_name'))
        ->order("ph.photo_id DESC");
    	$result = $this->getAdapter()->fetchAll($select);
        return $result;
    }

	  // for get all Photos
   public function getAllGalleryPhotos(){
	$select = $this->select();
	$select->from($this);
	$result = $this->fetchAll($select);
	return $result;
	 }
  
  public function editPhoto($formData)
  {	  
	  $data = array('photo_name' => $formData['photo_name'],'caption' => $formData['caption'],
 	'description' => $formData['description'],'link' => $formData['link'],'pg_cat_id' => $formData['category']);
     $where = $this->getAdapter()->quoteInto('photo_id = ?',$formData['photo_id']);
	 $result = $this->update($data,$where);
	 if($result){
			return  "<div class='alert alert-success'>Gallery Photo Updated Successfully </div>" ;
		}  else {
			return "<div class='alert alert-danger'>Some error in updating record</div>";
		}
	 return $result;
  }
  
     //for delete photo
  public function removePhoto($id){
        $where = "photo_id = " . (int) $id;
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;}
	}
	
    public function getPhotoByCategoryId($cat_id){
    	 $select = $this->select();
    	 $select->from($this)->where("pg_cat_id = ?", $cat_id);
    	 $result = $this->fetchAll($select);
    	 return $result;
    }
    
    public function getGallerySearch($cat_id, $photo_name)
    {
        $select = $this->getAdapter()->select();
    	$select->from(array('ph' => 'photos'), array('*'))
        ->joinLeft(array('cpg' => 'categories_pg'),'ph.pg_cat_id = cpg.pg_cat_id',array('cpg.category_name'))
        ->where("ph.photo_name like ? ", "%".trim($photo_name)."%")
        ->where('ph.pg_cat_id = ?', $cat_id)        
        ->order("ph.photo_name asc"); //$sql = $select->__toString();echo "$sql\n";return;
    	$result = $this->getAdapter()->fetchAll($select);
        return $result;
    }
    
    public function getPhotosByIds($db, $photo_ids){
    	 $select = $this->select();
    	 $select->from($this)->where("photo_id IN(?)",$photo_ids);
    	 $result = $this->fetchAll($select);
    	 return $result;
    }
}