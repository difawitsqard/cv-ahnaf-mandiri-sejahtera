<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Outlet;
use App\Models\StockItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function create(Outlet $outlet)
    {
        $stockItems = StockItem::where('outlet_id', $outlet->id)
            ->with(['outlet', 'unit', 'category'])
            ->orderBy('stock', 'asc')
            ->get();

        return view('dashboard.expense-management.create', compact('outlet', 'stockItems'));
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
