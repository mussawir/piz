<?php
 class Application_Model_ImageBlocks extends Zend_Db_Table
{
    protected $_name = 'image_blocks';
    protected $_primary = 'ib_id';

 public function getImageBlockByID($id){
	 $select = $this->select();
	 $select->from($this)->where("ib_id = ?", $id);
	 $result = $this->fetchRow($select);
	 return $result;
 }


 // For get all Image Block
 public function getAllImageBlocks(){
$select = $this->select();
$select->from($this)->order('ib_id');
$result = $this->fetchAll($select);
return $result;
 }

     public function getAllImageBlocksByStatus($status)
     {
        $select = $this->select();
        $select->from($this)->where("is_active = ?", $status)->order('ib_id');
        $result = $this->fetchAll($select);
        return $result;
     }

 public function editBlockImage($formData)
  {
	$data = array('block' => $formData['block'],
	'link' => $formData['link'],
	'caption' => $formData['caption'],
		'disable_link' => $formData['disable_link']
		);
      $where = $this->getAdapter()->quoteInto('ib_id = ?',$formData['ib_id']);
	 $result = $this->update($data,$where);
	 if($result){
			return  "<div class='alert alert-success'>Image Block Updated Successfully </div>" ;
		}  else {
			return "<div class='alert alert-danger'>Some error in updating record</div>";
		}
	 return $result;
  }
    
    public function activateDeactivateBlock($id)
      {
            $data = array();
            $block = $this->getImageBlockByID($id);
            if($block['is_active']==1){
                $data['is_active'] = 0;
            } else {
                $data['is_active'] = 1;
            }
                    
            $where = "ib_id = " . (int)$id;
            $this->id = $this->update($data, $where);
    
            if ($this->id)
            {
                return true;
            } else
            {
                return null;
            }
      }
}