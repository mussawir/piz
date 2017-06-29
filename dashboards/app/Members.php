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
    protected $primaryKey  = 'member_id';
    protected $fillable = [
        'email', 'pwd',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pwd', 'remember_token'
    ];
	public function getAuthPassword()
    {

        return md5($this->password);
    }
}
