<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function confidentiality()
    {
        return view('pages.confidentiality');
    }

    public function help()
    {
        return view('pages.help');
    }

    public function spa()
    {
        return view('layouts.app');
    }
}







