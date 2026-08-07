<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function editPassword()
    {
        return view('account.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:4', 'confirmed'],
        ]);

        $user = $request->user();

        if(! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Nieprawidłowe hasło.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Hasło zostało zmienione.');
    }
}
