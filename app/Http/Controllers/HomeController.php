<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman beranda.
     */
    public function index()
    {
        return view('home');
    }
}
