
<?php
 
class Application_Model_CustomizePopup extends Zend_Db_Table
{ 
    protected $_name = 'customize_popup';
    protected $_primary = 'cp_id';
    protected $result = null;
  
 
  public function getCustomizationByID($member_id,$page_id){
	 $select = $this->select();
	 $select->from($this)->where("member_id = ?", $member_id)->where("page_id = ?", $page_id);
	 $result = $this->fetchRow($select);
	 return $result;
  }
  
  // add new page
  public function addCustomization($formData) {
  // $data = array(
				 // 'banner' => $formData['banner'],
				// 'video' => $formData['video'],
				// 'f_type' => $formData['f_type'],
				// 'page_id' => $formData['page_id'],
				// 'date_created' => date('Y-m-d | h:i:sa')
				// );
				 
 $result = $this->insert($formData); 
	return $result;
   }
   // for update post
	public function updateCustomization($formData) {
  $data = array(
				 'cta_heading' => $formData['cta_heading'],
				'cta_paragraph' => $formData['cta_paragraph'],
				'cta_button_color' => $formData['cta_button_color'],'cta_button_text' => $formData['cta_button_text'],
				'cta_background' => $formData['cta_background'],'cta_heading_color' => $formData['cta_heading_color'],'cta_paragraph_color' => $formData['cta_paragraph_color'],
				);
				$where[] = "cp_id = ". $formData['cp_id'];
				$where[] = "member_id = ". $formData['member_id'];
				$where[] = "page_id = ". $formData['page_id'];
 $result = $this->update($data,$where); 
	return $result;
   }
   public function updateCustomizationWithOutImage($formData) {
  $data = array(
				 'cta_heading' => $formData['cta_heading'],
				'cta_paragraph' => $formData['cta_paragraph'],
				'cta_button_color' => $formData['cta_button_color'],'cta_button_text' => $formData['cta_button_text'],'cta_heading_color' => $formData['cta_heading_color'],'cta_paragraph_color' => $formData['cta_paragraph_color'],
				);
				$where[] = "cp_id = ". $formData['cp_id'];
				$where[] = "member_id = ". $formData['member_id'];
				$where[] = "page_id = ". $formData['page_id'];
 $result = $this->update($data,$where); 
	return $result;
   }
	   //for delete page
  public function deleteCustomization($id){
        $where[] = "cp_id = ". $formData['cp_id'];
		$where[] = "member_id = ". $formData['member_id'];
		$where[] = "page_id = ". $formData['page_id'];
    $id = $this->delete($where);
    if($id > 0){
        return true;
    }else{
        return false;
    }
 }
	
}
?>