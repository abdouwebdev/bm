<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginView()
    {
        return view('_auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $remember = !empty($request->remember);

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('home')->with('success', 'Welcome');
        } else {
            return redirect()->back()->with('error', 'wrong username or password');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
