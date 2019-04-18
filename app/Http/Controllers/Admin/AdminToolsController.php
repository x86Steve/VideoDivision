<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminToolsController extends Controller
{
    function index()
    {
        return view('admin');
    }
}