<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login()
    {
        return view('authentication.login');
    }

    public function loginData(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|current_password'
        ]);

        $remember_me = !empty($request->remember_me) ?: false;

        if (Auth::validate($credentials)) {
            if (Auth::attempt($credentials, $remember_me)) {
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'remember_me'));
    }

    public function register()
    {
        return view('authentication.register');
    }

    public function registerData(Request $request)
    {
        $request->validate([
            'name' => 'required|max:55',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|password',
            'password_confirmation' => 'required|confirmed'
        ]);

        $user = User::create($request->all());
        Auth::login($user);
        return redirect()->intended('/dashboard')->with(['success' => 'Your have successfully sign up.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
