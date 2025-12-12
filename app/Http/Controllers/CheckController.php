<?php

namespace App\Http\Controllers;

use App\Models\Program;

class CheckController extends Controller
{
    public function index()
    {
        return view('layouts.admin-layout.admin-dashboard');
    }

    public function button()
    {
        return view('buttonCheck');
    }

    public function user()
    {
        return view('user');
    }

    public function showRegistrationForm()
    {
        $undergraduate = Program::undergraduate()->orderBy('name')->get();
        $graduate = Program::graduate()->orderBy('name')->get();

        return view('layouts.admin-layout.admin-dashboard', [
            'undergraduate' => $undergraduate,
            'graduate' => $graduate,
        ]);
    }
}
