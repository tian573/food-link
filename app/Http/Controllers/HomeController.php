<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function my_home()
    {
        return view('home.index');
    }
    
    public function map()
    {
        return view('home.map');
    }
    
    public function form()
    {
        return view('home.form');
    }
    
    public function index()
    {
        if(Auth::id()) 
        {
            $usertype = Auth()->user()->usertype;

            if($usertype=='user')
            {
                return redirect('/');
            }
            else if($usertype=='admin')
            {
                // Redirect to the admin dashboard route instead of returning a view
                return redirect()->route('admin.dashboard');
            }
        }
    }
}