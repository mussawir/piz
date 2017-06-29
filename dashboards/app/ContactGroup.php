<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactGroup extends Model
{
    // explicitly define table and primary key
    protected $table = 'contact_group';
    protected $primaryKey = 'cg_id';
	public $timestamps  = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array 
     */
    protected $fillable = [
        'member_id',
        'name',
        'description'
    ];
}
