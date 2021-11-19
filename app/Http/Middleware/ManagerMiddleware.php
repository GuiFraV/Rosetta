<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth::check() && Auth::user()->role_id == 3 ){
            if (Auth::user()->active == 1){
                return $next($request);
            }else{
                return redirect()->route('login')->with('active','Account not Actived');
            }
            
         }
         else {
            return redirect()->route('login');
         }
    }
}
