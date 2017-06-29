<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
	protected $table = 'orders';
	protected $primaryKey = 'orders_id';
	
    protected $fillable = [
       'orders_id','order_tracking_number','docDate','email','first_name','last_name',
	   'address,city,state',
	   'zip_code',
	   'phone',
	   'card_id',
	   'card_number',
	   'card_cvv',
	   'card_expiration_m',
	   'card_expiration_y',
	   'country',
    ];
}
