<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{

    public function showForgotForm()
    {
        return view('auth.ForgotPass');
    }

    public function sendResetLink(Request $request)
    {
        // Validate the email
        $request->validate([
            'Email' => 'required|email',
        ]);


           $user = User::where('Email', $request->Email)->first();

      if (!$user) {
            return back()->withErrors([
                'Email' => 'No account was found with that email address.',
            ]);
        }


        $token = Str::random(64); //bye5la2 token
        DB::table('PasswordResetTokens') //bema7e iza kane mawgood token 2abl
            ->where('Email', $request->Email)
            ->delete();


        DB::table('PasswordResetTokens')->insert([ //save the new token
            'Email' => $request->Email,
            'Token' => $token,
            'CreatedAt' => now(),
        ]);

        // Send the reset email
        Mail::to($request->Email) // byeb3at email w token ala class
            ->send(new ResetPasswordMail($token));

 return back()->with(
            'success',
            'A password reset link has been sent to your email.'
        );
    }
    public function showResetForm($token)
{
    // Check if the token exists
    $resetRequest = DB::table('PasswordResetTokens')
        ->where('Token', $token)
        ->first();

    if (!$resetRequest) {
        return redirect()
            ->route('passwordForgetpage')
            ->withErrors([
                'Email' => 'This password reset link is invalid or has expired.'
            ]);
    }


    return view('auth.ResetPass', [
        'token' => $token
    ]);
}
public function resetPassword(Request $request)
{
    // Validate the form
    $request->validate([
        'token' => 'required',
        'Password' => 'required|min:8|confirmed',
    ]);

    // Check if the token exists
    $resetRequest = DB::table('PasswordResetTokens')
        ->where('Token', $request->token)
        ->first();

    if (!$resetRequest) {
        return back()->withErrors([
            'token' => 'Invalid or expired reset token.'
        ]);
    }

    // Find the user
    $user = User::where('Email', $resetRequest->Email)->first();

    if (!$user) {
        return back()->withErrors([
            'Email' => 'User not found.'
        ]);
    }

    // Update the password
    $user->Password = bcrypt($request->Password);
    $user->save();

    // Delete the used token
    DB::table('PasswordResetTokens')
        ->where('Email', $resetRequest->Email)
        ->delete();

    // Redirect to login
    return redirect('/')
        ->with('success', 'Your password has been reset successfully. Please log in.');
}
}
