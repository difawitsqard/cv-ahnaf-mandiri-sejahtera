<?php

namespace App\Http\Controllers\dashboard\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\StockItem;
use Illuminate\Http\Request;

class StockItemManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Outlet $outlet)
    {
        $perPage = is_numeric($request->perPage) ? $request->perPage :  10;

        if (!empty($request->search)) {
            $stockItems = StockItem::where('outlet_id', $outlet->id)
                ->filter()
                ->orderBy('id', 'desc')
                ->paginate($perPage);
            $stockItems->appends(['search' => $request->search]);
        } else {
            $stockItems = StockItem::where('outlet_id', $outlet->id)
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        }
        $stockItems->appends(['perPage' => $perPage]);

        return view('dashboard.superadmin.stock-item-management.index', compact('stockItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
