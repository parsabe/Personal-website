<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }
    public function contact()
    {
        return view('contact');
    }
    public function publications()
    {
        return view('publications');
    }
    public function projects()
    {
        return view('projects');
    }
    public function teaching()
    {
        return view('teaching');
    }
    public function myplaylist()
    {
        return view('myplaylist');
    }

}
