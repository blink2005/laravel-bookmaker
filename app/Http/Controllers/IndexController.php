<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class IndexController extends Controller
{
    public function index()
    {
        return view('index.index', ['auth_status' => Auth::check()]);
    }
}
