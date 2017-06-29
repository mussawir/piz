<?php
class Application_Model_PageProducts extends Zend_Db_Table
{
    protected $_name = 'page_products'; 
    protected $_primary = 'pp_id';
    
    public function add($formDate) 
    {
        $formDate['date_created'] = date('Y-m-d H:i:s');
        $pp_id = $this->insert($formDate); 
        if($pp_id){
 			return true;
  		}  else {
 			return false;
  		}
   }
   
   public function updateRecord($formDate, $pp_id)
   {
        $where = "pp_id = ". $pp_id;
    	$is_saved = $this->update($formDate, $where);
        if($is_saved){ 
            return true;
        }  else {
            return false;
        }
   }
   
    public function deleteRecord($pp_id)
    {     
        $where = "pp_id = ". $pp_id;
     	$id = $this->delete($where);
     	if ($id) {
		    return true;
        } else {
		    return false;
       	}
    }
   
   public function getPageProducts($page_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->order('date_created desc');
        $result = $this->fetchAll($select);
        return $result;
   }
   public function getPageProductsbySorting($page_id,$post_type){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)
		->order(new Zend_Db_Expr("FIELD(post_type, $post_type) DESC") )->order('date_created desc');
        $result = $this->fetchAll($select);
        return $result;
   }
   public function getPagexProductx($page_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('post_type = ?','1')->order('date_created desc');
        $result = $this->fetchAll($select);
        return $result;
   }
   public function getPagePosts($page_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('post_type = ?','2')->order('date_created desc');
        $result = $this->fetchAll($select);
        return $result;
   }
   public function getVideoPosts($page_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('post_type = ?','4')->order('date_created desc');
        $result = $this->fetchAll($select);
        return $result;
   }
   public function getServicePosts($page_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('post_type = ?','3')->order('date_created desc');
        $result = $this->fetchAll($select);
        return $result;
   }
   public function getProducts($page_id,$name)
    {
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('post_type = ?','1')
		->where("name like ? ", "%" . $name . "%");
        $result = $this->fetchAll($select);
        return $result;
    }
	public function getServices($page_id,$name)
    {
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('post_type = ?','3')
		->where("name like ? ", "%" . $name . "%");
        $result = $this->fetchAll($select);
        return $result;
    }
   public function getPageProduct($pp_id){
        $select = $this->select();
        $select->from($this)->where('pp_id = ?',$pp_id);
        $result = $this->fetchRow($select);
        return $result;
   }
}