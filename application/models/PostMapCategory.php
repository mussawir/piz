<?php

class Application_Model_PostMapCategory extends Zend_Db_Table
{
    protected $_name = 'post_map_category';
    protected $_primary = 'pmc_id';
    
    public function getAllPostCategory($db){
        
        $select = new Zend_Db_Select($db);
        $select->from(array('pmc' => 'post_map_category', 'pmc.*'))
                ->join(array('pcat' => 'categories_post'),'pcat.category_id = pmc.category_id',array('pcat.name'));
                
        $result = $db->query($select)->fetchAll();
        return $result;
    }
    
    public function getCategoryIdByPostId($post_id){
        $select = $this->select();
        $select->from($this)->where("post_id = ?", $post_id);
        $result = $this->fetchAll($select);
        return $result;
    }
    
    public function addPostCategory($post_id, $cat_id)
    {
        $data = array('post_id'=>$post_id,'category_id'=>$cat_id);
        $result = $this->insert($data);
        return $result;
    }
    
    public function deletePostCategory($id)
    {
        $where = "post_id = " . (int)$id;
        $id = $this->delete($where);
    }
    
    public function getPostsByCategory($cat_id){
        $select = $this->select();
        $select->from($this)->where("category_id = ?", $cat_id);
        $result = $this->fetchAll($select);
        return $result;
    }
    
    // update post category to uncategorized when delete any category
    public function updatePostCategory($cat_id)
    {
        $result = $this->getPostsByCategory($cat_id);
        if(count($result)>0){
            foreach($result as $r){
                $data = array('category_id'=>1);
                $where = "post_id = " . (int)$r['post_id'];
                $this->update($data, $where);
            }    
        }                
    }
           
}