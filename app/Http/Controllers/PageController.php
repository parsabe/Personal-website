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



    public function BlackWall()
    {
        return view('BlackWall');
    }
    public function Mlmatrix()
    {
        return view('mlmatrix');
    }
    public function SCP ()
    {
        return view('SCP');
    }

    public function CeasarToolkit()
    {
        return view('CeasarToolkit');
    }

    public function parsai()
    {
        return view('Parsai');
    }

    public function netnexus()
    {
        return view('netnexus');
    }

    public function hounaartoolkit()
    {
        return view('hounaartoolkit');
    }
    public function sandika()
    {
        return view('sandika');
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
    public function legal_notice()
    {
        return view('legal-notice');
    }
    public function privacy_policy()
    {
        return view('privacy-policy');
    }

}
