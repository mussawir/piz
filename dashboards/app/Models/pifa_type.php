<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pifa_type extends Model
{
	protected $table = 'pifa_type';
	protected $primaryKey = 'type_id';
	
    protected $fillable = [
       'type_id','type_text','sortOrder','have_parent','parent_id',
    ];
}
