<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class AuthController extends Controller

{
    public function showLogin()
    {
        return view('auth.login');
    }
    public function login(Request $request)
{
    $request->validate([
        'Email' => 'required|email',
        'Password' => 'required',
    ]
    );

     $user = User::where('Email', $request->Email)->first();

    if (!$user || !Hash::check($request->Password, $user->Password)) {
        return back()->withErrors([
            'Email' => 'Invalid email or password.',
        ])->onlyInput('Email');
    }
    Auth::login($user);
      $request->session()->regenerate();

    return redirect('/dashboard');
}
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');


}
}
