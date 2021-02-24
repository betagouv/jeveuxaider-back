<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function spa()
    {
        return view('layouts.app');
    }
}
