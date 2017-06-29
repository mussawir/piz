<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactInGroup extends Model
{
    // explicitly define table and primary key
    protected $table = 'contact_in_group';
    protected $primaryKey = 'cig_id';
	public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array 
     */
    protected $fillable = [
        'sub_id',
        'cg_id',
    ];
}
