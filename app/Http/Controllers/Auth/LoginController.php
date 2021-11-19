<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(Auth::check() && Auth::user()->role_id == 1 && Auth::user()->active == 1){
            return redirect()->route('admin.dashboard')
                        ->with('success','City created successfully1.');
        } elseif(Auth::check() && Auth::user()->role_id == 2 && Auth::user()->active == 1){
            return redirect()->route('user.dashboard')
                        ->with('success','City created successfully2.');
        }elseif(Auth::check() && Auth::user()->role_id == 3 && Auth::user()->active == 1&& Auth::user()->active == 1){
            return redirect()->route('manager.dashboard')
                        ->with('success','City created successfully3.');
        }
       
        $this->middleware('guest')->except('logout');
    }
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
