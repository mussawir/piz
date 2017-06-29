<?php
class Application_Model_PageCategories extends Zend_Db_Table
{ 
    protected $_name = 'page_categories'; 
    protected $_primary = 'category_id';
    private $id = null;
	private $result;


public function add($formData, $file_name){
        $category_name = $formData['category_name'];
    	$parent_id = $formData['parent_id'];
       	$is_main = $formData['is_main'];
		$is_featured = $formData['is_featured'];	
		
        $data = array("category_name" => $category_name,"code" => $formData['code'], "parent_id" => $parent_id,
            "category_image" => $file_name, "is_main" => $is_main, "is_featured" => $is_featured, 'date_added'=>date('Y-m-d H:i:s'));
$this->id = $this->insert($data);
if($this->id){
    return  "<div class='alert alert-success'>A New Listing Main Category <strong>". $category_name. "</strong> is Added</div>" ;
}  else {
    return "<div class='alert alret-danger'>Some error in saving record</div>";
}
}

public function getAllCategoriesAsc()
    {
        $select = $this->select();
        $select->from($this)->order('category_name ASC');
        $result = $this->fetchAll($select);
        return $result;
    }
	

public function checkMainCatName($data){
$select = $this->select();
$select->from($this)->where('category_name = ?', $data['category_name']);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}

public function checkSubCatName($data){
$select = $this->select();
$select->from($this)->where('category_name = ?', $data['category_name'])->where('parent_id = ?', $data['parent_id']);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}


public function checkCode($code){
$select = $this->select();
$select->from($this)->where('code = ?', $code);
$result = $this->fetchRow($select);
if(is_object($result)){
	return true;
	}else return false; 
}


public function updateCategory($formData, $category_image, $category_id){
        $category_name = $formData['category_name'];
		$code = $formData['code'];
       	$is_main = $formData['is_main'];
		$is_featured = $formData['is_featured'];		

	    $data = array("category_name" => $category_name,"code" => $code,"category_image" => $category_image,
            "is_main" => $is_main, "is_featured" => $is_featured);
        $where = "category_id = ". (int) $category_id;
$this->id = $this->update($data, $where);
if($this->id == 0){
    return  true;
}  else {
    false;
}
}


       public function getList(){
		$select = $this->select();
		$cols = array("category_id","category_name","category_image","status", "code","is_main","is_featured");
		$select->from($this, $cols);
		$result = $this->fetchAll($select);
		if(is_object($result)){
		return $result;
		}else return false; }


	public function getCategory($category_name){
		$select = $this->select();
		$cols = array("category_id","category_name","code","category_image","status","is_main","is_featured");
		$select->from($this, $cols)->Where("code like ? ", "%" .$category_name . "%")->orWhere("category_name like ? ", "%" .$category_name . "%");
		$result = $this->fetchAll($select);
		if(is_object($result)){
				return $result;
		}else return false; 
	}	




//update category image 
public function updateImage($category_id, $file_name){
        $data = array("category_image" => $file_name);
  //echo $site_name . $site_url. $description;
        $where = "category_id = ". (int) $category_id;
$this->id = $this->update($data, $where);
if($this->id == 1){
    return "Image has been updated: ";
}  else {
    return "Image has been updated" . $this->id;
}
}

//update category name
public function updateName($category_id, $category_name){
 $data = array("category_name" => $category_name);
 $where = "category_id = ". (int) $category_id;
$this->id = $this->update($data, $where);
if($this->id > 0 || $this->id == 0){
    return true;
}  
}


public function getAllCategories(){
$select = $this->select();
$select->from($this);
$result = $this->fetchAll($select);
return $result;
}


public function getAllCatImages(){
$select = $this->select();
$select->from($this, array('category_image'));
$result = $this->fetchAll($select);
return $result;
}

public function getCategoryByID($category_id){
$select = $this->select();
$select->from($this)->where('category_id = ?', $category_id);
$result = $this->fetchRow($select);
return $result;
}

public function getCategoryName($category_id){
$select = $this->select();
$select->from($this, array('category_name'))->where('category_id = ?', $category_id);
$result = $this->fetchRow($select);
if(is_object($result)){
return $result->category_name;
}else{
return "";
}
}




public function getCategoryImage($category_id){
$select = $this->select();
$select->from($this, array('category_image'))->where('category_id = ?', $category_id);
$result = $this->fetchRow($select);
return $result->category_image;
}

/* remove record from bdlist category start*/

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
	/* remove record from bdlist category end	*/
        
public function getParentID($category_id){
                    $result = $this->fetchRow($this->select(array("parent_id"))
->where("category_id = ?", (int) $category_id));
               if ($result) {
          
            $this->result = $result->parent_id;

        }else{
            $this->result = 0;

        }
        return $this->result;
    }
    
//this function we will use to pass category id and get it parent category name
// when we will put this into a chain function to reach to top parent category name    
public function getParentName($category_id){
$result = $this->fetchRow($this->select(array("parent_id"))
->where("category_id = ?", (int) $category_id));
               if ($result) {
$result2 = $this->fetchRow($this->select(array("category_name"))
->where("category_id = ?", (int) $result->parent_id));
if($result2)
$this->result = $result2->category_name;
            

            }else{
            $this->result = NULL;
        }
        return $this->result;
    }

    //update parent ID on deleting a category and if it has a parent id rather than 0
   public function updateParentID($parent_id, $category_id){
        $data = array("parent_id" => $parent_id);
  //echo $site_name . $site_url. $description;
        $where = "parent_id = ". (int) $category_id;
$this->id = $this->update($data, $where);
if($this->id == 0 || $this->id > 0){
     $this->result =  true;
}  else {
   $this->result =  true;
}
return $this->result; 
} 

	public static function getMaincategories($db, $size){
		$select = new Zend_Db_Select($db);
		$cols = array("category_id","category_name");
		$select->from('page_categories', $cols)
		->where("parent_id = ?", 0)
                        ->order("category_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//format result for showing into a listbox
		$output = "";
		$output = "<select name='main-category' id='main-category' size='".$size."'  OnChange='getSubCat1();' class='form-control' style='width: 235px;'>";
		$output .= "<option value= ''> </option>";
                foreach($results as $result){
			$output .= "<option value= '" . $result['category_id']."'>". $result['category_name']."</option>";
		}
                $output .="</select>";

		return $output;
	}


	public static function getSubs($db, $parent_id){
		$select = new Zend_Db_Select($db);
		$select->from('page_categories')
		->where("parent_id = ?", $parent_id)->order("category_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results;
	}


	public static function getSubCat1($db, $parent_id, $size){
		$select = new Zend_Db_Select($db);
		$cols = array("category_id","category_name");
		$select->from('page_categories', $cols)
		->where("parent_id = ?", $parent_id)->order("category_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//format result for showing into a listbox
		$output = "";

		$output = "<select name='sub-cat1' id='sub-cat1' size='".$size."'  OnChange='getSubCat2();'  class='form-control' class='from-control'>";
		$output .= "<option value= ''> </option>";
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['category_id']."'>". $result['category_name']."</option>";
			}
			$output .="</select>";
		}
		else{
			$output = "true"	;
		}
		return $output;
	}

	public static function getSubCat2($db, $parent_id, $size){
		$select = new Zend_Db_Select($db);
		$cols = array("category_id","category_name");
		$select->from('page_categories', $cols)
		->where("parent_id = ?", $parent_id)->order("category_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//format result for showing into a listbox
		$output = "";
		$output = "<select name='sub-cat2' id='sub-cat2' size='".$size."'  OnChange='getSubCat3();'   class='form-control'>";
		$output .= "<option value= ''> </option>";
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['category_id']."'>". $result['category_name']."</option>";
			}
			$output .="</select>";
		}
		else{
					$output = "true"	;
		}
		return $output;
	}

	public static function getSubCat3($db, $parent_id, $size){
		$select = new Zend_Db_Select($db);
		$cols = array("category_id","category_name");
		$select->from('page_categories', $cols)
		->where("parent_id = ?", $parent_id)->order("category_name ASC");
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		//format result for showing into a listbox
		$output = "";
		$output = "<select name='sub-cat3' id='sub-cat3' size='".$size."' OnChange='getSubCat4()'  class='form-control'>";
		$output .= "<option value= ''> </option>";
		if(count($results) > 0 ){
			foreach($results as $result){
				$output .= "<option value= '" . $result['category_id']."'>". $result['category_name']."</option>";
			}
			$output .="</select>";
		}
		else{
			$output = "true"	;
		}
		return $output;
	}


	//get chain result of category and parent category names
	public static function getChain($db, $last_id, $ids){
		$cnt = count($ids);
		$output = "";
		for($i= 0; $i < $cnt; $i++){
			$result = $db->fetchOne("SELECT category_name FROM category WHERE category_id = ". $ids[$i]. "");
			$output .= $result . " > ";

		}
		$output = substr($output,0, -2);
		return $output;

	}

        	public function isParent($db, $category_id){
		$select = new Zend_Db_Select($db);
		$cols = array("category_id","category_name");
		$select->from('list_categories', $cols)
		->where("parent_id = ?", $category_id);
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		$output = false;

		if(count($results) > 0 ){
			$output = true;
		}
		else{
			$output = false	;
		}
		return $output;
	}
public function BcrumbsCategories($category_id){
    $cat_ids = array();
    $cat_names = array();
    $cat_ids[0] = $category_id; 
    for($i = 1; $i < 4 ; $i++ ){
       $category_id = $this->getParentID($category_id); 
      if($category_id != 0){
       $cat_ids[$i] = $category_id;
      }else{
          break;
      }
    }
    $bread_crumb_ids = array_reverse($cat_ids);
    $counter = 0;
    foreach($bread_crumb_ids as $id){
       $cat_names[$counter]  =  array("id" => $id, "name" =>$this->getCategoryName($id));
       $counter++;
       }
       return $cat_names;
    }




	
	
 
//here view means the pthml page to that contents will send, it can be any phtml page 
public static function categoriesBox($db, $view){
        	$stmt = $db->query('SELECT * FROM category WHERE parent_id = 0');
		$rows = $stmt->fetchAll();
                $view->main_categories_cat_list = $rows; //page_name_variable
}

public static function subCategoryBox($db, $view, $id){
        	$stmt = $db->query('SELECT * FROM category WHERE parent_id = ' . $id);
		//$stmt = $db->query('SELECT * FROM category');
                $rows = $stmt->fetchAll();
                if(count($rows) > 0){
                $view->categories_list_cat_list = $rows; //page_name_variable
                return true;
                }else{
                return false;
                }
           }
           
        public static function clipsListBox($db, $view, $id){
        /* @var $stmt <type> */
		
			$stmt = $db->query('SELECT * FROM clips as p WHERE category_id = ' . $id );
			
		$rows = $stmt->fetchAll();
                $view->clips_list_clip_list = array();
               if(count($rows) > 0){
               $view->clips_list_clip_list = $rows; //page_name_variable
               return true;
                }else{
               return false;
                }
           
                return false;
           }

//Get all categories which are marked is_main = 1 
public function getMainCategoriesFe(){
$select = $this->select();
$select->from($this)->where("is_main = ?", 1)->where("parent_id = ?", 0)->order("category_id ASC");;
$result = $this->fetchAll($select);
if(is_object($result)){
return $result; //return result object
}else{
    return null;
}
}


//Get all categories which are marked is_featured = 1 
public function getFeaturedCategories(){
$select = $this->select();
$select->from($this)->where("is_featured = ?", 1)->order("category_id ASC");;
$result = $this->fetchAll($select);
if(is_object($result)){
return $result; //return result object
}else{
    return null;
}
}


public function getCategoryNameFe($category_id){
$select = $this->select();
$select->from($this, array('category_name'))->where("category_id = ?", $category_id);
$result = $this->fetchRow($select);
if(is_object($result)){
return $result->category_name; //return result object
}else{
    return null;
}
}

    public function getCategoriesByParent($parent_id)
    {
        $select = $this->select();
        $select->from($this)->where("parent_id = ?", $parent_id)->order('category_id ASC');
        $result = $this->fetchAll($select);
        return $result;
    }
    
    public function getCategoriesByAlphabate($char='a')
    {
        $select = $this->select();
        $select->from($this)->where("LOWER(category_name) LIKE ?", $char."%")->order('category_id ASC');
        //$sql = $select->__toString();echo "$sql\n";return;
        $result = $this->fetchAll($select);
        return $result;
    }

}
?>