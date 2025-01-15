<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
       return view('portfolio');
    }
}
