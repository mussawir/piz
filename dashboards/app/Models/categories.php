<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
	protected $table = 'categories';
	protected $primaryKey = 'cat_id';
	
    protected $fillable = [
	   'cat_id', 'cat_name', 'parent_id', 
	   'cat_percentage', 'cat_desc', 'sortOrder',
    ];
}
