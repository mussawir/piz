<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Auth;
use App\Members;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
	public function authenticate()
    {
		 $user = Members::where('email',$_POST['email'])
                ->where('pwd',md5($_POST['pwd']))
                ->first();

		if ($user) {
			Auth::login($user);
			if(Auth::check()){
				echo 'success';
			}
			else{
				echo 'error';
			}
		}
    }
}
