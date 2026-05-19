<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('home', ['title' => 'Home']);
    }

    public function about()
    {
        return view('about', ['title' => 'About']);
    }

    public function portfolio()
    {
        return view('portfolio', ['title' => 'Portfolio']);
    }

    public function contact()
    {
        return view('contact', ['title' => 'Contact']);
    }
}