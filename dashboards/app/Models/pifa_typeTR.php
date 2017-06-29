<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pifa_typeTR extends Model
{
	protected $table = 'pifa_typeTR';
	protected $primaryKey = 'id';
	
    protected $fillable = [
       'id','type_value','sortOrder','refId',
    ];
}
