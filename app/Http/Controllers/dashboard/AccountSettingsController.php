<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Outlet;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSettingsController extends Controller
{
    public function index(Outlet $outlet)
    {
        $user = Auth::user();
        $companyInfo = null;

        if ($user->hasRole('superadmin')) {
            $companyInfo = CompanyInfo::first();
        }

        return view('dashboard.account-settings.index', compact('outlet', 'companyInfo', 'user'));
    }

    public function accountUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'mobile_phone_number' => 'nullable',
            'address' => 'nullable',
        ]);

        $user = Auth::user();
        $oldEmail = $user->email;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_phone_number = $request->mobile_phone_number;
        $user->address = $request->address;

        if ($oldEmail !== $request->email) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();

        session()->flash('form-name', 'account-info');
        return redirect()->back()->with('success', 'Account details updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Kata sandi lama yang Anda masukkan salah']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Auth::logout();
        return redirect()->route('login')->with('info', 'Kata sandi berhasil diubah. relogin dibutuhkan setelah mengubah kata sandi');
    }
}
