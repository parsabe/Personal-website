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
        return view('pages.projects.blackwall');
    }
    public function Mlmatrix()
    {
        return view('pages.projects.mlmatrix');
    }
    public function SCP()
    {
        return view('pages.projects.scp');
    }

    public function CeasarToolkit()
    {
        return view('pages.projects.ceasartoolkit');
    }

    public function parsai()
    {
        return view('pages.projects.parsai');
    }

    public function netnexus()
    {
        return view('pages.projects.netnexus');
    }

    public function hounaartoolkit()
    {
        return view('pages.projects.hounaartoolkit');
    }
    public function proj_sandika()
    {
        return view('pages.projects.sandika');
    }

    public function funroot()
    {
        return view('pages.projects.funroot');
    }







    public function publications()
    {
        return view('pages.publications');
    }

    public function blackwall_paper()
    {
        return view('pages.publications.blackwall_paper');
    }

    public function moodium()
    {
        return view('pages.publications.moodium');
    }

    public function scm()
    {
        return view('pages.publications.scm');
    }

    public function captcha()
    {
        return view('pages.publications.captcha');
    }
    public function ai_blockchain()
    {
        return view('<pages class="publications"></pages>ai-blockchain');
    }
    public function synergy_blockchain()
    {
        return view('pages.publications.synergy-blockchain');
    }
    public function php_vuls()
    {
        return view('pages.publications.php-vuls');
    }
    public function crm()
    {
        return view('pages.publications.crm');
    }
    public function qca()
    {
        return view('pages.publications.qca');
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

    public function nigma()
    {
        return view('pages.nigma.nigma');
    }
    public function chat()
    {
        return view('pages.chat.chat');
    }
    public function abie()
    {
        return view('pages.abie-motlagh.abie');
    }
    public function sandika()
    {
        return view('pages.sandika.sandika');
    }
}
