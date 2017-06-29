<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
	protected $table = 'products';
	protected $primaryKey = 'products_id';
	
    protected $fillable = [
	   'products_id', 'products_name', 'short_description', 
	   'productDescription', 'supplier_id', 
	   'createdBy', 'cat_id', 'price', 'discountPer', 
	   'discountAmout', 'taxPer', 'taxAmount', 'quantity', 
	   'status', 'featured', 'tags',
	   'brand_id', 'map', 'profitP','model','mainColor','cost','SKU','HTU','videoLink',
	   'productMeasurement','productWeight','pkg_height','pkg_width','pkg_length','pkg_weight',
	   'WINTB','buy_limit',
    ];
	
}
