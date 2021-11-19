<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = Auth::user();
        if(Auth::check() && Auth::user()->role_id == 1){
            return redirect()->route('admin.dashboard')->with('data',$data);
        } elseif(Auth::check() && Auth::user()->role_id == 2){
            return redirect()->route('user.dashboard')->with('data',$data);
        }elseif(Auth::check() && Auth::user()->role_id == 3){
            return redirect()->route('manager.dashboard')->with('data',$data);
        }else{
            return redirect()->route('home');
        }
        
        
    }
}
