<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminToolsController extends Controller
{
    function index()
    {
        // if guest tries to access page, suggest they log in
        if (Auth::guest())
            return redirect()->route('login');
        // if non-admin tries to access page, redirect to home
        else if (!Auth::user()->isAdmin)
            return redirect()->route('home');

        return view('admin');
    }
}