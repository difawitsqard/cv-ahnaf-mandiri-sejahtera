<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Unit;
use App\Models\Outlet;
use App\Models\StockItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\File;
use App\Http\Requests\dashboard\superadmin\StockItemManagementRequest;

class StockItemManagementController extends Controller
{
    protected $imageUploadService;

    public function __construct(private readonly Outlet $outlet, ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
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

        return view('dashboard.stock-item-management.index', compact('stockItems', 'units', 'outlet'));
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
            $validatedData['image_path'] = $this->imageUploadService->uploadImage($validatedData['image'], "{$outlet->slug}/stock-items");
        }

        $stockItems = StockItem::create($validatedData);

        return redirect()
            ->to(roleBasedRoute('stock-item.index', ['outlet' => $outlet->slug]))
            ->with('success', 'Item ' . $stockItems->name . ' berhasil ditambahkan.');
    }

    public function create(Outlet $outlet)
    {
        $units = Unit::all();
        return view('dashboard.stock-item-management.create', compact('units', 'outlet'));
    }

    /**
     * Show the specified resource.
     */
    public function show($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');

        return view('dashboard.stock-item-management.show', compact('stockItem', 'outlet'));
    }

    public function fetch($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');

        if ($stockItem) {
            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $stockItem,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'code' => 404,
            ], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StockItemManagementRequest $request, $param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $validatedData = $request->validated();
        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();

        $unit = Unit::findOrCreate($validatedData['unit_id']);
        $validatedData['unit_id'] = $unit->id;

        if (!isset($validatedData['delete_image'])) {
            $validatedData['delete_image'] = [];
        }
        foreach ($validatedData['delete_image'] as $imageId) {
            $image = StockItem::where('id', $imageId)->firstOrFail();
            $this->imageUploadService->deleteImage($image->image_path);
            $image->image_path = null;
            $image->save();
        }

        if ($request->hasFile('image')) {
            if ($stockItem->image_path) {
                $this->imageUploadService->deleteImage($stockItem->image_path);
            }
            $validatedData['image_path'] = $this->imageUploadService->uploadImage($validatedData['image'], "{$outlet->slug}/stock-items");
        } else {
            $validatedData['image_path'] = $stockItem->image_path;
        }

        $stockItem->update($validatedData);

        return redirect()
            ->to(roleBasedRoute('stock-item.index', ['outlet' => $outlet->slug]))
            ->with('success', 'Item ' . $stockItem->name . ' berhasil diubah.');
    }

    public function edit($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');
        $units = Unit::all();

        return view('dashboard.stock-item-management.edit', compact('stockItem', 'units', 'outlet'));
    }

    public function restock(Request $request, $param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

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
    public function destroy($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

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
