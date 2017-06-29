<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_detail extends Model
{
	protected $table = 'user_detail';
	protected $primaryKey = 'id';
	
    protected $fillable = [
       'refId','first_name','last_name','phone','address','buisness_address',
    ];
}
