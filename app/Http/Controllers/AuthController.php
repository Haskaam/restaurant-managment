<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

public function view()
{
    return view('auth.login');
}

public function login(Request $request)
{
   $dane = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if(Auth::attempt($dane)) {
        $request->session()->regenerate();
        if(!Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'To konto jest niekatywne',
            ])->onlyInput('email');
        }
        return redirect()->route('dashboard');

    }


    return back()->withErrors([
        'email' => 'Nieprawidłowy e-mail lub hasło.',
    ])->onlyInput('email');
}

public function logout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}

}
