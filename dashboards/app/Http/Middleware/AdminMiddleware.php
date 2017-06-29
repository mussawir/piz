<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		return $next($request);
        /*if (Auth::check()) {
            if(Auth::user()->role == '1'){
				Session::put('role','admin');
                return $next($request);
            }else{
                return redirect('/');
            }
        }
		else{return $next($request);
		}*/
    }
}
