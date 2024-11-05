<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function setPasswordForm()
    {
        $user = Auth::user();

        if ($user->is_password_set) {
            return redirect()->route('home');
        }

        return view('auth.passwords.set-password', ['user' => $user]);
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->is_password_set = true;
        $user->save();

        Auth::logout($user);

        return redirect()->route('login')->with('status', 'Password berhasil diatur.');
    }
}
