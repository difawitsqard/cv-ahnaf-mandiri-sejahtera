<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()?->hasRole(['superadmin'])) {
            return $this->dashboardSuperadmin();
        } else if (auth()->user()?->hasRole(['admin'])) {
            return $this->dashboardAdmin();
        }
        return abort(403);
    }

    private function dashboardSuperadmin()
    {
        return redirect()->route('outlet.index');
    }

    private function dashboardAdmin()
    {
        return redirect()->route('admin.dashboard');
    }

    public function root($any)
    {
        if (view()->exists('demo.' . $any)) {
            return view('demo.' . $any);
        } else {
            return abort(404);
        }
    }
}
