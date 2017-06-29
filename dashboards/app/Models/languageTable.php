<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class languageTable extends Model
{
	protected $table = 'languagetable';
	protected $primaryKey = 'id';
	
    protected $fillable = [
       'field','lang','table','refId',
    ];
}
