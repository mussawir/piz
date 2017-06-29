<?php
class Application_Model_NearByPostcodes extends Zend_Db_Table
{ 
    protected $_name = 'postcodes_nearby'; 
    protected $_primary = 'nb_pc_id';
    private $id = null;
	private $result;


public function add($data){


$areas = $data['areas'];
$n_postcodes =	explode(',', $areas);
foreach($n_postcodes as $nb_postcode){
$this->id = $this->insert(array('pc_id' => $data['pc_id'],'nb_postcode' => $nb_postcode));
	}
   
return "<div class='alert alert-success'>Near By Postcodes are saved</div>";
}

public function getPcNb($pc_nb_id)
{
$select = $this->select();
$select->from($this)->where('pc_id = ?', $pc_nb_id);
$result = $this->fetchRow($select);
return $result;
}

public function getAllPostCodes($db)
{

		$select = new Zend_Db_Select($db);
		$cols = array("nb_pc_id","nb_postcode");
		$select->from('postcodes_nearby', $cols);
		$stmt = $db->query($select);
		$results = $stmt->fetchAll();
		return $results;
}

public function edit($formData)
{
$areas = $formData['areas'];

        $data = array("nb_postcode" => $areas);
		//update('table', $data, $where);
		
		$where = "nb_pc_id = " . $formData['pc_nb_id'];
		$this->id = $this->update($data, $where);
		if($this->id){
    return  "<div class='alert alert-success'>".$areas ." Added Successfully </div>" ;
	}  else {
    return "Ops something gone wrong!";
	}
}


} 

