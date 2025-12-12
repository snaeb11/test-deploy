<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // If user is authenticated and has admin permissions, redirect to admin dashboard
        if (Auth::check() && Auth::user()->hasPermission('view-dashboard')) {
            return redirect()->route('admin.dashboard');
        }

        return view('layouts.landing.index', [
            'page' => 'home',
        ]);
    }
}
