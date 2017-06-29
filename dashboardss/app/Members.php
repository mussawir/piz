<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Members extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'members';
    public $timestamp = false;
    protected $primary_key = 'member_id';
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
