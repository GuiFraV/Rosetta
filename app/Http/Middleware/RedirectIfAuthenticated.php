<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check() && Auth::user()->role_id == 1 && Auth::user()->active == 1) {
            return redirect()->route('admin.dashboard');
        } elseif(Auth::guard($guard)->check() && Auth::user()->role_id == 2 && Auth::user()->active == 1){
            return redirect()->route('user.dashboard');
        } elseif(Auth::guard($guard)->check() && Auth::user()->role_id == 3 && Auth::user()->active == 1 ){
            return redirect()->route('manager.dashboard');
        } else {
            return $next($request);
        }
    }
}
