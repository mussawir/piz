<?php

class Application_Model_Video extends Zend_Db_Table
{
    protected $_name = 'videos';
    protected $_primary = 'v_id';
    protected $result = null;


    public function getVideo($id)
    {
        $select = $this->select();
        $select->from($this)->where("v_id = ?", $id);
        $result = $this->fetchRow($select);
        return $result;
    }


    public function getMainVideo()
    {
        $select = $this->select();
        $select->from($this)->where("is_main =?", 1);
        $result = $this->fetchRow($select);
        return $result;
    }


    public function getAllVideos()
    {
        $select = $this->select();
        $select->from($this)->order("is_featured DESC");
        $result = $this->fetchAll($select);
        return $result;
    }

    public function getFeaturedVideos()
    {
        $select = $this->select();
        $select->from($this)->where("is_featured = ?", 1)->order("is_featured");
        $result = $this->fetchAll($select);
        return $result;
    }


    // add new video link
    public function addVideo($formData)
    {
        $data = array(
            'title' => $formData['title'],
            'url_video' => addslashes($formData['url_video']),
            'short_description' => $formData['short_description'],
            'is_featured' => $formData['is_featured'],
            //'is_main' => $formData['is_main'],
            'video_img' => $formData['video_img'],
            'video_data' => $formData['video_data']);

        $result = $this->insert($data);
        
        if (isset($result))
        {   
            $main_video = $this->getMainVideo();
            
            // if no main video make this as main video
            if($main_video['is_main']==0)
            {
                $this->makeMain($result);
            }
            else if($main_video['is_main']==1 && $formData['is_main']=="1")
            {
                $this->unsetMain($main_video['v_id']);
                $this->makeMain($result);
            }            
        }
        return $result;
    }

    public function removeVideo($db, $id)
    {

        //$rowset = $this->fetchAll();
        //$rowCount = count($rowset);
        //if ($rowCount < 2 || $rowCount == 1) return 3;
        
        $is_main = $this->getVideo($id);
        if($is_main['is_main']==1){
            return "<div class='alert alert-danger'>You can not delete main video.</div>";
        }
        
        $id = $this->delete($db->quoteInto("v_id = ?", $id));
        if ($id > 0)
        {
            return 1;
        } else
        {
            return 2;
        }
    }

    public function updateVideo($formData)
    {
        $data = array(
            'title' => $formData['title'],
            'short_description' => $formData['short_description'],
            'url_video' => $formData['url_video'],
            'video_img' => $formData['video_img'],
            'is_featured' => $formData['is_featured'],
            //'is_main' => $formData['is_main'],
            'video_data' => $formData['video_data']);
            
        $where = "v_id= " . $formData['v_id'];
        $result = $this->update($data, $where);
        // if is_main set clean main of other records 
if($formData['is_main'] == 1){
	$data = array("is_main" => 0);
	$this->update($data);
	
	$data = array("is_main" => 1);
	$result = $this->update($data, $where);
	
}
        
return result;		
		/*
		if ($result==1)
        {
            $main_video = $this->getMainVideo();
            // if no main video make this as main video
            if($main_video['is_main']==0)
            {
                $this->makeMain($formData['v_id']);
            }
            elseif($main_video['is_main']==1 && $formData['is_main']=="1")
            {
                $this->unsetMain($main_video['v_id']);
                $this->makeMain($formData['v_id']);
            }
        }
        
        return $result; 
		*/
    }

    public function makeMain($v_id)
    {       
        $data = array('is_main' => 1);
        $where = "v_id= " . $v_id;
        $result = $this->update($data, $where);
    }
    
    public function unsetMain($v_id)
    {       
        $data = array('is_main' => 0);
        $where = "v_id= " . $v_id;
        $result = $this->update($data, $where);
    }

}