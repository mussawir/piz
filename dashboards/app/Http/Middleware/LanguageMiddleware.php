<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Config;
use App;
use Auth;

class LanguageMiddleware
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
		$rolePrefix = '';
		if (Auth::check()) {
		//FOR ROLE PREFIX AND RESTRICTIONS 
		//START
		$roleInURL = $request->segment(2);
		$roles = Array("supplier","admin","customers");
		$roleCheck = false;
		if(Auth::user()->role == '1'){
			Session::put('role','admin');
			$rolePrefix = 'admin';
        }
		if(Auth::user()->role == '2'){
			Session::put('role','supplier');
			$rolePrefix = 'supplier';
        }
		if(Auth::user()->role == '3'){
			Session::put('role','customers');
			$rolePrefix = 'customers';
        }
		if (in_array($roleInURL, $roles)) {
			$roleCheck = true;
		}
		if($roleCheck)
		{
			if($roleInURL!=$rolePrefix)
			{
				$url = str_replace('/'.$roleInURL.'/', '/'.$rolePrefix.'/', $request->url());
				return redirect($url);
			}
		}
		//END
		if(Session::get('lang_sel')=="")
		{
			Session::put('lang_sel','en');
		}
				$langArr = Config::get('app.locales');
		$keys = array_keys($langArr);
		$prefixURL = $request->segment(1);
		if (in_array($prefixURL, $keys)) {
		$prefixURL = $request->segment(1);
		$dupRequest = $request->duplicate();
		$segment = $dupRequest->segment(1);
		if (in_array($prefixURL, $keys)) {
			App::setLocale($prefixURL);
		} else {
			$prefixURL = null;
		}
		if(Session::get('lang_sel')!="")
		{
			if(Session::get('lang_sel')!=$prefixURL){
				$url=$request->url();
				if (str_contains($request->getRequestUri(), '/'.$prefixURL.'/')) {
					$url = str_replace('/'.$prefixURL.'/', '/'.Session::get('lang_sel').'/', $url);
				}
				if($roleInURL!=$rolePrefix)
				{
					$url = str_replace('/'.$roleInURL.'/', '/'.$rolePrefix.'/', $url);
				}
				return redirect($url);
			}
		}
		}
		return $next($request);
		}
		else{
			return $next($request);
            }
    }
}
