<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    // explicitly define table and primary key
    protected $table = 'subscribers';
    protected $primaryKey = 'sub_id';
	public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
		'phone'
    ];
}
