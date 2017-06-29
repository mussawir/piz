<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
	protected $table = 'settings';
	protected $primaryKey = 'id';
	
    protected $fillable = [
       'message',
    ];
}
