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
        return view('pages.about');
    }
    public function contact()
    {
        return view('pages.contact');
    }

    public function projects()
    {
        return view('pages.projects');
    }
    public function BlackWall()
    {
        return view('BlackWall');
    }
    public function Mlmatrix()
    {
        return view('mlmatrix');
    }
    public function SCP()
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

    public function publications()
    {
        return view('pages.publications');
    }

    public function blackwall_paper()
    {
        return view('blackwall_paper');
    }

    public function moodium()
    {
        return view('moodium');
    }

    public function scm()
    {
        return view('scm');
    }

    public function captcha()
    {
        return view('captcha');
    }
    public function ai_blockchain()
    {
        return view('ai-blockchain');
    }
    public function synergy_blockchain()
    {
        return view('synergy-blockchain');
    }
    public function php_vuls()
    {
        return view('php-vuls');
    }
    public function crm()
    {
        return view('crm');
    }
    public function qca()
    {
        return view('qca');
    }


    public function myplaylist()
    {
        return view('pages.myplaylist');
    }

    public function search()
    {
        return view('pages.search');
    }

    public function VPN_server()
    {
        return view('pages.vpn');
    }


    public function fun()
    {
        return view('pages.club');
    }

    public function support()
    {
        return view('pages.support');
    }





}
