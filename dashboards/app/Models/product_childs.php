<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product_childs extends Model
{
	protected $table = 'product_childs';
	protected $primaryKey = 'id';
	
    protected $fillable = [
       'product_id','custom_name','price','qty','type','SKU'
	   ,'cost','saleDate','endDate','manageStock','variationSelected',
    ];
}
