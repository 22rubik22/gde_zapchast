<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function index()
    {
        return view('oferta');
    }
}

