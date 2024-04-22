<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('login.login', ['auth_status' => Auth::check()]);
    }

    public function store(Request $request)
    {
        $credentials = $this->validateLogin($request);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('welcome');
        }

        return view('login.error_login', ['auth_status' => Auth::check()]);
    }

    public function validateLogin($request)
    {
        return $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);
    }
}
