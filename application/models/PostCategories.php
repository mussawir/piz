<?php

class Application_Model_PostCategories extends Zend_Db_Table
{
    protected $_name = 'categories_post';
    protected $_primary = 'category_id';
    
    public function getAllCategory()
    {
        $select = $this->select();
        $select->from($this);
        $result = $this->fetchAll($select);
        return $result;
    }
	
	
    
    public function getCategoriesByParent($parent_id)
    {
        $select = $this->select();
        $select->from($this)->where("parent_id = ?", $parent_id)->order('category_id ASC');
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getCategoryById($id)
    {
        $select = $this->select();
        $select->from($this)->where("category_id = ?", $id);
        $result = $this->fetchRow($select);
        return $result;
    }
    
    public function getChildCategories($id)
    {
        $select = $this->select();
        $select->from($this)->where("parent_id = ?", $id);
        $result = $this->fetchAll($select);
        return $result;
    }
    
    public function addCategory($formData)
    {
        $data = array('name'=>$formData['name'],'parent_id'=>$formData['parent_id']);
        $result = $this->insert($data);
        return $result;
    }
    
    public function updateCategory($formData)
    {
        $data = array('name'=>$formData['name'],'parent_id'=>$formData['parent_id']);
        $where = "category_id = " . (int)$formData["category_id"];
        $this->id = $this->update($data, $where);
        if($this->id)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function updateChildCategory($category_id,$data)
    {
        $where = "category_id = " . (int)$category_id;
        $this->update($data, $where);        
    }
    
    public function deleteCategory($category_id){
        $category = $this->getCategoryById($category_id);
        $childs = $this->getChildCategories($category_id);
        
        if(count($childs)>0){
            foreach($childs as $child){
                $data = array('parent_id'=>$category['parent_id']);
                $this->updateChildCategory($child['category_id'],$data);
            }
        }
        
        $where = "category_id = " . (int) $category_id;
        $id = $this->delete($where);
        if($id > 0){
            return true;
        }else{
            return false;
        }
    }

} // class end