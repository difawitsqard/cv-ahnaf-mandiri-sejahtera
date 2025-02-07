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
use App\Notifications\CustomRegistrationNotification;
use App\Http\Requests\dashboard\UserManagementRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Notifications\CustomEmailVerificationNotification;

class UserManagementController extends Controller
{

    public function __construct()
    {
        // $this->middleware('throttle:1,1')->only('resend');
    }

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
                ->orWhere('outlet_id', null)
                ->orWhereHas('roles', function ($query) {
                    $query->where('name', 'superadmin');
                });
        } else {
            $users = User::where('outlet_id', $outlet->id);
        }

        $users->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->orderBy(function ($query) {
                $query->select('id')
                    ->from('roles')
                    ->whereColumn('roles.id', 'model_has_roles.role_id')
                    ->limit(1);
            });

        $users = $users->get();

        $roles = [];
        $outlets = [];
        if ($currentUser->hasRole('superadmin')) {
            $roles = Role::all();
            $outlets = Outlet::all();
        } else if ($currentUser->hasRole('admin')) {
            $roles = Role::where('name', '=', 'staff')->get();
        }

        return view('dashboard.user-management.index', compact('users', 'roles', 'outlet', 'outlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserManagementRequest $request, Outlet $outlet)
    {
        $validatedData = $request->validated();

        $role = Role::findOrFail($validatedData['role']);

        if (!auth()->user()->hasRole('superadmin') && $role->name != 'staff') {
            return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk membuat pengguna dengan role ' . $role->name]);
        }

        // Jika role superadmin, maka outlet_id harus null
        if ($role->name == 'superadmin') $validatedData['outlet_id'] = null;

        // Jika bukan superadmin, maka outlet_id harus sesuai dengan outlet yang sedang login
        if (!auth()->user()->hasRole('superadmin')) $validatedData['outlet_id'] = $outlet->id;


        DB::beginTransaction();

        try {
            $randomPassword = Str::random(16);
            $validatedData['password'] = Hash::make($randomPassword);
            $user = User::create($validatedData);
            $user->load('outlet');
            $user->syncRoles($role);
            $user->notify(new CustomRegistrationNotification());

            // Kirim email setelah pengguna didaftarkan
            // Mail::to($user->email)->send(new UserRegistered($user, $randomPassword));

            DB::commit();

            return redirect()->back()->with('success', "Pengguna {$user->name} berhasil dibuat, kami telah mengirimkan email verifikasi ke {$user->email}. Periksa spam pada email, jika tidak ada di kotak masuk.");
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' =>  $e->getMessage()]);
        }
    }

    public function fetch($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        try {
            $user = User::findOrFail($id);
            $user->load('roles', 'outlet');

            if (!$user->can_be_edited_or_deleted) {
                throw new Exception('User cannot be edited or deleted');
            }

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $user,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'Category not found',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
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
    public function update(UserManagementRequest $request, $param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $validatedData = $request->validated();

        $user = User::findOrFail($id);
        $oldEmail = $user->email;

        if (!$user->can_be_edited_or_deleted) {
            return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengedit pengguna ' . $user->name]);
        }

        $role = Role::findOrFail($validatedData['role']);
        if (!auth()->user()->hasRole('superadmin') && $role->name != 'staff') {
            return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk membuat pengguna dengan role ' . $role->name]);
        }

        if ($role->name == 'superadmin') {
            $validatedData['outlet_id'] = null;
        }

        if ($user->email_verified_at && $user->email != $validatedData['email'])
            return redirect()->back()->withErrors(['error' => 'Email tidak dapat diubah setelah diverifikasi']);

        if (!auth()->user()->hasRole('superadmin')) $validatedData['outlet_id'] = $outlet->id;

        DB::beginTransaction();
        try {
            $user->update($validatedData);
            $user->syncRoles($role);
            $user->load('outlet');

            if (!$user->email_verified_at && ($oldEmail  != $validatedData['email'])) {
                $user->notify(new CustomRegistrationNotification());
                $msg = "Pengguna {$user->name} berhasil diperbarui, kami telah mengirimkan email verifikasi ke {$user->email}. Periksa spam pada email, jika tidak ada di kotak masuk.";
            } else {
                $msg = "Pengguna {$user->name} berhasil diperbarui.";
            }

            DB::commit();

            return redirect()->back()->with('success', $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' =>  $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $user = User::findOrFail($id);

        if (!$user->can_be_edited_or_deleted) {
            return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus pengguna ' . $user->name]);
        }

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return redirect()->back()->with('success', "Pengguna {$user->name} berhasil dihapus");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => "Pengguna {$user->name} terkait dengan data lain, coba nonaktifkan akun pengguna ini."]);
        }
    }

    public function disabledOrEnable($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $user = User::findOrFail($id);

        if (!$user->can_be_edited_or_deleted) {
            return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menonaktifkan pengguna ' . $user->name]);
        }

        $disabledOrEnabled = $user->disabled_account ? 0 : 1;
        $user->disabled_account = $disabledOrEnabled;
        $user->save();

        return redirect()->back()->with('success', "Pengguna {$user->name} berhasil " . ($user->disabled_account ? 'dinonaktifkan' : 'diaktifkan'));
    }

    public function resend($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $user = User::findOrFail($id);
        $user->notify(new CustomEmailVerificationNotification());

        return redirect()->back()->with('success', "Email verifikasi berhasil dikirim ulang ke {$user->email}");
    }
}
