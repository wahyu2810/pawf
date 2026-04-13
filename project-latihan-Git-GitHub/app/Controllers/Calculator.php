<?php

namespace App\Controllers;

class Calculator extends BaseController
{
    public function index()
    {
        return view('calculator');
    }
}