<?php
class Application_Model_LikedFeedbacks extends Zend_Db_Table
{ 
    protected $_name = 'liked_feedback'; 
    protected $_primary = 'l_page_id';
    
    public function add($page_id, $member_id,$feed_id,$status) 
    {
        $result = $this->getLikeByPage($page_id, $member_id,$feed_id,$status);
        if($result){
            return;
        } else {
            $l_page_id = $this->insert(array('page_id'=>$page_id, 'member_id'=>$member_id, 'date_created'=>date('Y-m-d H:i:s'),'feed_id'=>$feed_id,'status'=>$status)); 
    		 if($l_page_id){
    			return true;
    		}  else {
    			return false;
    		}
        }
   }
   
   public function updateLike($page_id,$member_id,$feed_id,$status)
   {
        $data = array("status" => $status);
    	$where[] = "page_id = ". $page_id;
		$where[] = "member_id = ". $member_id;
		$where[] = "feed_id = ". $feed_id;
        $member_id = $this->update($data,$where);
        if($member_id){ 
            return true;
        }  else {
            return false;
        }
   }
   
    public function deleteMasterPage($member_id)
    {     
        $where = "mpm_id = " .(int) $member_id;
     	$id = $this->delete($where);
     	if ($id) {
		    return true;
        } else {
		    return false;
       	}
    }
   
   public function getAllLikedPages($page_id,$feed_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('feed_id = ?',$feed_id)->where('status = ?','0');
        $result = $this->fetchAll($select);
        return $result;
   }
	public function getAllDisLikedPages($page_id,$feed_id){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('feed_id = ?',$feed_id)->where('status = ?','1');
        $result = $this->fetchAll($select);
        return $result;
   }
    public function getLikesByPage($db, $page_id){
        $select = new Zend_Db_Select($db);
        $select->from(array('p' => 'pages'), 'p.title')
        ->join(array('lp' => 'liked_pages'),'lp.page_id = p.page_id',array('lp.page_id', 'lp.member_id', 'lp.date_created'))
        ->join(array('m' => 'members'),'m.member_id = lp.member_id',array('m.first_name', 'm.last_name'))
        ->where('p.page_id = ?',$page_id); //$sql = $select->__toString();echo "$sql\n";return;
        $result = $db->query($select)->fetchAll();
        return $result;
   }
   
   public function getLikeByPage($page_id, $member_id,$feed_id,$status){
        $select = $this->select();
        $select->from($this)->where('page_id = ?',$page_id)->where('feed_id = ?',$feed_id)->where('member_id = ?',$member_id)->where('status = ?',$status);
        $result = $this->fetchRow($select);
        return $result;
   }
} // class end