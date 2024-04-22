<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register.register', ['auth_status' => Auth::check()]);
    }

    public function store(Request $request)
    {
        if($this->validateForm($request))
        {
            $user = $this->createUser($request);
            $this->authUser($user);
            return redirect()->route('welcome');
        }
        else
        {
            return view('register.passwords_error', ['auth_status' => Auth::check()]);
        }
    }

    public function validateForm($request)
    {
        $request->validate([
            'email' => ['required', 'email:rfc,dns', 'unique:App\Models\User,email'],
            'password' => ['required', 'max:32'],
            'retry_password' => ['required'],
        ]);

        if ($request->password === $request->retry_password)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function createUser($request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'number' => $request->number,
            'balance' => 0.0,
            'is_referal' => false,
            'status_block' => false,
            'status_admin' => false,
            'status_moderator' => false,
            'status_verify' => true
        ]);

        return $user;
    }

    public function authUser($user)
    {
        event(new Registered($user));
        Auth::login($user);
    }
}
