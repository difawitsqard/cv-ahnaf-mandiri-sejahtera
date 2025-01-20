<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Unit;
use App\Models\Outlet;
use App\Models\StockItem;
use Illuminate\Http\Request;
use App\Models\StockItemCategory;
use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Models\Activity;
use App\Http\Requests\dashboard\StockItemManagementRequest;

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

        if (auth()->user()?->hasRole('staff')) {
            return $this->indexStaff($outlet);
        } else if (auth()->user()?->hasRole(['admin', 'superadmin'])) {
            return $this->indexAdminSuperadmin($outlet);
        } else {
            return abort(403);
        }
    }

    private function indexStaff(Outlet $outlet)
    {
        $stockItems = StockItem::where('outlet_id', $outlet->id)
            ->where('category_id', 1)
            ->with(['outlet', 'unit', 'category'])
            ->get()
            ->map(function ($stockItem) {
                $stockItem['alert_stock'] = $stockItem['stock'] < $stockItem['min_stock'] ? 1 : 0;
                return $stockItem;
            })
            ->sortBy(function ($stockItem) {
                return $stockItem['stock'] < $stockItem['min_stock'] ? 0 : 1;
            })
            ->values();

        return view('dashboard.etalase-management.index', compact('stockItems', 'outlet'));
    }

    private function indexAdminSuperadmin(Outlet $outlet)
    {
        $stockItems = StockItem::where('outlet_id', $outlet->id)
            ->with(['outlet', 'unit', 'category'])
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
        $validatedData['total_stock'] = $validatedData['stock'];

        $category = StockItemCategory::findOrCreate($validatedData['category_id']);
        $validatedData['category_id'] = $category->id;

        $unit = Unit::findOrCreate($validatedData['unit_id']);
        $validatedData['unit_id'] = $unit->id;

        if ($request->hasFile('image')) {
            $validatedData['image_path'] = $this->imageUploadService->uploadImage($validatedData['image'], "{$outlet->slug}/stock-items");
        }

        // set price to 0 if the category etalase
        $validatedData['price'] = $category->id == 1 ? $validatedData['price'] : 0;

        $stockItems = StockItem::create($validatedData);

        return redirect()
            ->to(roleBasedRoute('stock-item.index', ['outlet' => $outlet->slug]))
            ->with('success', 'Item ' . $stockItems->name . ' berhasil ditambahkan.');
    }

    public function create(Outlet $outlet)
    {
        $stockItems = StockItem::where('category_id', 1)->get();
        return view('dashboard.stock-item-management.create', compact('outlet', 'stockItems'));
    }

    /**
     * Show the specified resource.
     */
    public function show($param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');

        return view('dashboard.stock-item-management.show', compact('stockItem', 'outlet'));
    }

    public function fetch($param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');

        $lastRestock = Activity::inLog('stock_item_log')
            ->where('subject_type', StockItem::class)
            ->where('subject_id', $stockItem->id)
            ->whereIn('event', ['restocked', 'created'])
            ->orderBy('created_at', 'desc')
            ->first();

        $stockItem['last_restock'] = $lastRestock ? $lastRestock->created_at->format('d M Y H:i') : null;

        if ($stockItem) {
            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $stockItem,
            ]);
        } else {
            return response()->json(
                [
                    'status' => false,
                    'code' => 404,
                ],
                404,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StockItemManagementRequest $request, $param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        $validatedData = $request->validated();
        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();

        $category = StockItemCategory::findOrCreate($validatedData['category_id']);
        $validatedData['category_id'] = $category->id;

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
        [$outlet, $id] = $this->processParameters($param1, $param2);

        $stockItem = StockItem::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $stockItem->load('unit', 'outlet');
        $units = Unit::all();

        return view('dashboard.stock-item-management.edit', compact('stockItem', 'units', 'outlet'));
    }

    public function restock(Request $request, $param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        $request->validate([
            'qty' => 'required|numeric',
        ]);

        $stockItem = StockItem::restock($id, $outlet->id, $request->qty);

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
        [$outlet, $id] = $this->processParameters($param1, $param2);

        // Retrieve the specific service instance
        $stockItems = StockItem::findOrFail($id);

        try {
            $stockItems->delete();

            if ($stockItems->image_path) {
                $imagePath = public_path('uploads/' . $stockItems->image_path);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            return redirect()
                ->back()
                ->with('success', 'Item ' . $stockItems->name . ' berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()
                    ->back()
                    ->withErrors(['error' => 'Item ' . $stockItems->name . ' tidak dapat dihapus karena masih terkait dengan data lain.']);
            }

            throw $e;
        }
    }
}
