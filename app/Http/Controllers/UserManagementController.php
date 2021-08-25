<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function adminUserVerification($verificationToken)
    {
        if(!empty(User::where('verification_token',$verificationToken)->first()))
        {
            return view('admin.authentication.user_verify', compact('verificationToken'));
        }
        return redirect('/login')->back()->withErrors([
            'error' => 'You account has already verified.'
        ]);
    }

    public function adminUserVerificationData()
    {
        //
    }
}
