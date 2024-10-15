<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\UnitManagementRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnitManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::orderBy('id', 'desc')->get();
        return view('dashboard.unit-management.index', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitManagementRequest $request)
    {
        $validatedData = $request->validated();
        $unit = Unit::create($validatedData);

        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit ' . $unit->name . ' berhasil ditambahkan.');
    }

    public function fetch(string $id)
    {
        try {
            $unit = Unit::findOrFail($id);

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $unit,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'Unit not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitManagementRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $unit = Unit::findOrFail($id);
        $unit->update($validatedData);

        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit ' . $unit->name . ' berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit ' . $unit->name . ' berhasil dihapus.');
    }
}
