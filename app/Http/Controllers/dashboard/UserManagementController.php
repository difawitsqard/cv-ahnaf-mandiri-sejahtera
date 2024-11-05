<?php

namespace App\Http\Controllers\dashboard;

use Exception;
use App\Models\User;
use App\Models\Outlet;
use Illuminate\Support\Str;
use App\Mail\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\dashboard\UserManagementRequest;

class UserManagementController extends Controller
{
    /**
     * Process parameters to determine the order.
     */
    protected function processParameters($param1, $param2 = null)
    {
        if ($param1 instanceof Outlet) {
            return [$param1, $param2];
        }

        return [$param2, $param1];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Outlet $outlet)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->hasRole('superadmin')) {
            $users = User::where('outlet_id', $outlet->id)
                ->orWhereHas('roles', function ($query) {
                    $query->where('name', 'superadmin');
                });
        } else {
            $users = User::where('outlet_id', $outlet->id);
        }

        $users = $users->where('id', '!=', $currentUser->id)->get();

        $roles = [];
        if ($currentUser->hasRole('superadmin')) {
            $roles = Role::all();
        } elseif ($currentUser->hasRole('admin')) {
            $roles = Role::where('name', '!=', 'superadmin')->get();
        }

        return view('dashboard.user-management.index', compact('users', 'roles', 'outlet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserManagementRequest $request, Outlet $outlet)
    {

        DB::beginTransaction();

        try {
            $validatedData = $request->validated();
            $validatedData['outlet_id'] = $outlet->id;

            $randomPassword = Str::random(16);
            $validatedData['password'] = Hash::make($randomPassword);

            $user = User::create($validatedData);
            $user->load('outlet');

            $role = Role::findOrFail($validatedData['role']);
            $user->syncRoles($role);

            // $randomPassword = Password::createToken($user);

            $user->sendEmailVerificationNotification();

            // Kirim email setelah pengguna didaftarkan
            // Mail::to($user->email)->send(new UserRegistered($user, $randomPassword));

            DB::commit();

            return redirect()->back()->with('success', 'User has been created successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Failed to create user: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', "Pengguna {$user->name} berhasil dihapus");
    }
}
