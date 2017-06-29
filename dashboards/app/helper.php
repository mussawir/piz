<?php 
namespace App;
use DB;
class helper
{
		
	public static function isMultiArray($arr){
		foreach ($arr as $v) {
			if (is_array($v)) return true;
		}
		return false;
	}
	public static function PrintProductPage($data){
		$categoryId = '';
		$brand_id = '';
		$products_id='';
		$supplier_id = '';
		$returningArr = array();
		$SearchType = $data[0];
		$data = array_slice($data,1);
	if(!self::isMultiArray($data)){
	foreach($data as $items){
			if($items->rowTable=='categories'){
				$categoryId .= $categoryId=='' ? $items->id : ','.$items->id;
			}
			if($items->rowTable=='brands'){
				$brand_id .= $brand_id=='' ? $items->id : ','.$items->id;
			}
			if($items->rowTable=='products'){
				$products_id .= $products_id=='' ? $items->id : ','.$items->id;
			}
			if($items->rowTable=='users'){
				$supplier_id .= $supplier_id=='' ? $items->id : ','.$items->id;
			}
		}
	}else if(self::isMultiArray($data)){
		for($i=0;$i<count($data);$i++){
			foreach($data[$i] as $items){
			if($items->rowTable=='categories'){
				$categoryId .= $categoryId=='' ? $items->id : ','.$items->id;
			}
			if($items->rowTable=='brands'){
				$brand_id .= $brand_id=='' ? $items->id : ','.$items->id;
			}
			if($items->rowTable=='products'){
				$products_id .= $products_id=='' ? $items->id : ','.$items->id;
			}
			if($items->rowTable=='users'){
				$supplier_id .= $supplier_id=='' ? $items->id : ','.$items->id;
			}
			}
		}
	}
	array_push($returningArr,$categoryId);
	array_push($returningArr,$brand_id);
	array_push($returningArr,$products_id);
	array_push($returningArr,$supplier_id);
	array_push($returningArr,$SearchType);
	return $returningArr;
		//$return.='</table>';
		//return $return;
	}
		
}
?>