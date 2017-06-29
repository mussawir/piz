<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attributes extends Model
{
	protected $table = 'attributes';
	protected $primaryKey = 'id';
	
    protected $fillable = [
       'id','attribute_name','parent_id','sortOrder','attribute_value','created_by',
    ];
}
