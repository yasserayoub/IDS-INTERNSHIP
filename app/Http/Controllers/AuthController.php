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
    ]);

    $user = User::where('Email', $request->Email)->first();

    if (!$user || !Hash::check($request->Password, $user->Password)) {
        return back()->withErrors([
            'Email' => 'Invalid email or password.',
        ])->onlyInput('Email');
    }

    if (!$user->IsActive) {
        return back()->withErrors([
            'Email' => 'Your account is inactive. Please contact IT Support.',
        ])->onlyInput('Email');
    }

    Auth::login($user);

    $request->session()->regenerate();

    // Redirect user according to their role
    switch ($user->role->Name) {

        case 'Administrator':
            return redirect()->route('admin.dashboard');

        case 'IT Manager':
            return redirect()->route('manager.dashboard');

        case 'Employee':
            return redirect()->route('employee.dashboard');

        case 'IT Support':
            
            return redirect('/support/tickets');

        default:
            Auth::logout();

            return redirect('/')->withErrors([
                'Email' => 'Your account does not have a valid role.',
            ]);
    }
}
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');


}
}
