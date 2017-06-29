<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class imagetable extends Model
{
    protected $table = 'imagetable';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'id','image_path', 'refId', 'table','type'
    ];

}
