<?php

namespace App\Http\Controllers\dashboard\superadmin;

use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\superadmin\StockItemManagementRequest;
use App\Models\Outlet;
use App\Models\StockItem;
use App\Models\Unit;
use Illuminate\Http\Request;

class StockItemManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Outlet $outlet)
    {
        // $perPage = is_numeric($request->perPage) ? $request->perPage :  10;

        // if (!empty($request->search)) {
        //     $stockItems = StockItem::where('outlet_id', $outlet->id)
        //         ->filter()
        //         ->with(['outlet', 'unit'])
        //         ->orderBy('stock', 'asc')
        //         ->paginate($perPage);
        //     $stockItems->appends(['search' => $request->search]);
        // } else {
        //     $stockItems = StockItem::where('outlet_id', $outlet->id)
        //         ->with(['outlet', 'unit'])
        //         ->orderBy('stock', 'asc')
        //         ->paginate($perPage);
        // }
        // $stockItems->appends(['perPage' => $perPage]);

        $stockItems = StockItem::where('outlet_id', $outlet->id)
            ->with(['outlet', 'unit'])
            ->get()
            ->map(function ($stockItem) {
                $stockItem['alert_stock'] = $stockItem['stock'] < $stockItem['min_stock'] ? 1 : 0;
                return $stockItem;
            })
            ->sortBy(function ($stockItem) {
                return $stockItem['stock'] < $stockItem['min_stock'] ? 0 : 1;
            })
            ->values();
        $units = Unit::all();

        return view('dashboard.superadmin.stock-item-management.index', compact('stockItems', 'units', 'outlet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockItemManagementRequest $request, Outlet $outlet)
    {
        $validatedData = $request->validated();
        $validatedData['outlet_id'] = $outlet->id;

        $unit = Unit::findOrCreate($validatedData['unit_id']);
        $validatedData['unit_id'] = $unit->id;

        if ($request->hasFile('image')) {
            // //upload stroage 'php artisan storage:link'
            // $imagePath = $request->file('image')->store('images', 'public');
            // $gallery->image_path = $imagePath;

            //upload public
            $image = $validatedData['image'];
            $imageName = md5(time() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images');

            // Periksa apakah folder tujuan ada, jika tidak buat foldernya
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $validatedData['image_path'] = 'images/' . $imageName;
        }

        $stockItems = StockItem::create($validatedData);

        return redirect()
            ->route('outlet.stock-item.index', ['outlet' => $outlet->slug])
            ->with('success', 'Item ' . $stockItems->name . ' berhasil ditambahkan.');
    }

    public function create(Outlet $outlet)
    {
        $units = Unit::all();
        return view('dashboard.superadmin.stock-item-management.create', compact('units', 'outlet'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Outlet $outlet, string $id)
    {
        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');

        return view('dashboard.superadmin.stock-item-management.show', compact('stockItem', 'outlet'));
    }

    public function fetch(Outlet $outlet, string $id)
    {
        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $stockItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StockItemManagementRequest $request, Outlet $outlet, string $id)
    {
        $validatedData = $request->validated();
        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();

        $unit = Unit::findOrCreate($validatedData['unit_id']);
        $validatedData['unit_id'] = $unit->id;

        if ($request->hasFile('image')) {
            if ($stockItem->image_path) {
                $imagePath = public_path('uploads/' . $stockItem->image_path);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            $image = $validatedData['image'];
            $imageName = md5(time() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images');

            // Periksa apakah folder tujuan ada, jika tidak buat foldernya
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $validatedData['image_path'] = 'images/' . $imageName;
        } else {
            $validatedData['image_path'] = $stockItem->image_path;
        }

        $stockItem->update($validatedData);

        return redirect()
            ->route('outlet.stock-item.index', ['outlet' => $outlet->slug])
            ->with('success', 'Item ' . $stockItem->name . ' berhasil diubah.');
    }

    public function edit(Outlet $outlet, string $id)
    {
        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');
        $units = Unit::all();

        return view('dashboard.superadmin.stock-item-management.edit', compact('stockItem', 'units', 'outlet'));
    }

    public function restock(Request $request, Outlet $outlet, string $id)
    {
        $request->validate([
            'qty' => 'required|numeric',
        ]);

        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->stock += $request->qty;
        $stockItem->save();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Stok berhasil ditambahkan.',
            'data' => $stockItem,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outlet $outlet, string $id)
    {
        // Retrieve the specific service instance
        $stockItems = StockItem::findOrFail($id);
        if ($stockItems->image_path) {
            $imagePath = public_path('uploads/' . $stockItems->image_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $stockItems->delete();

        return redirect()
            ->back()
            ->with('success', 'Item ' . $stockItems->name . ' berhasil dihapus.');
    }
}
