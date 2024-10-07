<?php

namespace App\Http\Controllers\dashboard\superadmin;

use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\superadmin\OutletManagementRequest;

class OutletManagementController extends Controller
{
    public function __construct(private readonly Outlet $outlet) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = is_numeric($request->perPage) ? $request->perPage :  9;

        if (!empty($request->search)) {
            $Outlets = Outlet::filter()->orderBy('id', 'desc')->paginate($perPage);
            $Outlets->appends(['search' => $request->search]);
        } else {
            $Outlets = Outlet::orderBy('id', 'desc')->paginate($perPage);
        }
        $Outlets->appends(['perPage' => $perPage]);

        return view('dashboard.superadmin.outlet-management.index', compact('Outlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OutletManagementRequest $request)
    {
        $this->outlet->create($request->validated());

        return redirect()->route('outlet.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $outlet = Outlet::findOrFail($id);
        return response()->json($outlet);
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
    public function destroy(string $id)
    {
        //
    }
}
