<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * Retorna la vista principal
     *
     * @param [type] $any
     * @return laravel view
     */
    public function index()
    {
        return view('Main');
    }
}