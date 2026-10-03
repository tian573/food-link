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
    
    public function index()
    {
        if (Auth::check()) {
            $usertype = Auth::user()->usertype;

            if ($usertype === 'user') {
                return redirect('/');
            } elseif ($usertype === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }

        return redirect('/');
    }
}