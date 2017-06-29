<?php
class Application_Model_HeatmapClicks extends Zend_Db_Table
{ 
    protected $_name = 'heatmap_clicks';
    protected $_primary = 'hm_id';
    
    public function add($data){
        $result = $this->insert($data); 
        if($result)
        {
            return true;
		}  else {
			return false;
		}
    }
    
    public function getPagesByDate(){
	 $select = $this->select()->distinct();
	 $select->from($this, array('location'));
	 $result = $this->fetchAll($select);
	 return $result;
    }
    
    public function getClickInfo($location){
	 $select = $this->select();
	 $select->from($this, array('x','y'))->where('location=?', $location);	 
     $result = $this->fetchAll($select);
	 return $result;
    }
    
    public function deleteClicks($click_page_id, $click_page_type){
        $where = array("click_page_id = ?"=>(int)$click_page_id,"click_page_type = ?"=>$click_page_type);
        $id = $this->delete($where);
        if($id > 0){
            return true;
        }else{
            return false;
        }
    }
    
}