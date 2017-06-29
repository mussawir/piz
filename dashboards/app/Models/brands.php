<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class brands extends Model
{
	protected $table = 'brands';
	protected $primaryKey = 'brand_id';
	
    protected $fillable = [
       'brand_id','brand_name','brandDescription','supplier_id',
    ];
}
