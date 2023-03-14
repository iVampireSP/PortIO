<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {
        if ($request->user()) {
            return view('spa');
        }

        return view('index');
    }
}
