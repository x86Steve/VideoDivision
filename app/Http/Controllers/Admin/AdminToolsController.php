<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;

class AdminToolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        // if non-admin tries to access page, redirect to home
     if (!Auth::user()->isAdmin)
        return redirect()->route('home');

        return view('admin');
    }
}