<?php
class Application_Model_MemberBrochures extends Zend_Db_Table
{
    protected $_name = 'member_brochures'; 
    protected $_primary = 'm_brochuer_id';
    
    public function add($formDate) 
    {
        $formDate['date_created'] = date('Y-m-d H:i:s');
        $m_brochuer_id = $this->insert($formDate); 
        if($m_brochuer_id){
 			return true;
  		}  else {
 			return false;
  		}
   }
   
   public function updateBrochure($m_brochuer_id, $formDate)
   {
        $where = "m_brochuer_id = ". $m_brochuer_id;
    	$is_saved = $this->update($formDate,$where);
        if($is_saved){ 
            return true;
        }  else {
            return false;
        }
   }
   
    public function deleteBrochure($m_brochuer_id)
    {     
        $where = array("m_brochuer_id = ?" => $m_brochuer_id);
     	$id = $this->delete($where);
     	if ($id) {
		    return true;
        } else {
		    return false;
       	}
    }
   
   public function getMemberBrochures($member_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id)->order('date_created desc');
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getMemberPageBrochures($member_id, $page_id){
        $select = $this->select();
        $select->from($this)
        ->where('member_id = ?',$member_id)
        ->where('page_id = ?',$page_id)
        ->order('date_created desc');
        $result = $this->fetchAll($select);
        return $result;
   }
   
   public function getBrochure($member_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id);
        $result = $this->fetchRow($select);
        return $result;
   }
   
   public function getBrochureByPage($member_id, $page_id){
        $select = $this->select();
        $select->from($this)->where('member_id = ?',$member_id)->where('page_id = ?',$page_id);
        $result = $this->fetchRow($select);
        return $result;
   }
   
   public function getBrochureById($m_brochuer_id){
        $select = $this->select();
        $select->from($this)->where('m_brochuer_id = ?',$m_brochuer_id);
        $result = $this->fetchRow($select);
        return $result;
   }
   
   public function getLastBrochure($member_id, $page_id){
        $select = $this->select();
        $select->from($this)
        ->where('member_id = ?',$member_id)
        ->where('page_id = ?',$page_id)
        ->order('date_created desc');
        $result = $this->fetchRow($select);
        return $result;
   }
}