<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class CustomersMiddleware
{
    public function handle($request, Closure $next)
    {
		return $next($request);
    }
}
