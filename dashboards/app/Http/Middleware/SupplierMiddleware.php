<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class SupplierMiddleware
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
		//return $next($request);
        return $next($request);
		/*   if (Auth::check()) {
            if(Auth::user()->role == '2'){
				Session::put('role','supplier');
                return $next($request);
            }else{
                return redirect('/');
            }
        }
		else{return $next($request);
		}*/
		
    }
}
