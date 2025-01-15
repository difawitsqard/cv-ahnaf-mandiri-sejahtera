<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Http\Request;
use App\Models\StockItemCategory;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\dashboard\StockItemCategoryManagementRequest;
use Exception;

class StockItemCategoryManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockItemCategories = StockItemCategory::orderBy('is_static', 'desc')->orderBy('id', 'asc')->get();
        $stockItemCategories->load('stockItems', 'stockItems.outlet');

        return view('dashboard.stock-item-category-management.index', compact('stockItemCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockItemCategoryManagementRequest $request)
    {
        $validatedData = $request->validated();
        $stockItemCategory = StockItemCategory::create($validatedData);

        return redirect()
            ->back()
            ->with('success', 'Kategori ' . $stockItemCategory->name . ' berhasil ditambahkan.');
    }

    public function fetch(string $id)
    {
        try {
            $stockItemCategory = StockItemCategory::findOrFail($id);

            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $stockItemCategory,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'code' => 404,
                'message' => 'Category not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StockItemCategoryManagementRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            $stockItemCategory = StockItemCategory::findOrFail($id);
            $stockItemCategory->update($validatedData);

            return redirect()
                ->back()
                ->with('success', 'Kategori ' . $stockItemCategory->name . ' berhasil diperbarui.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->withErrors(['Kategori tidak ditemukan.']);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $stockItemCategory = StockItemCategory::findOrFail($id);
            $stockItemCategory->load('stockItems');

            if ($stockItemCategory->stockItems->count() > 0) {
                return redirect()
                    ->back()
                    ->withErrors(['Kategori ' . $stockItemCategory->name . ' tidak dapat dihapus karena terkait dengan item stok.']);
            }

            $stockItemCategory->delete();

            return redirect()
                ->back()
                ->with('success', 'Kategori ' . $stockItemCategory->name . ' berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->back()
                ->withErrors(['Kategori tidak ditemukan.']);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withErrors([$e->getMessage()]);
        }
    }
}
