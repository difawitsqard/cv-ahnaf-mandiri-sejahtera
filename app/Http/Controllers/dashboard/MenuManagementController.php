<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Menu;
use App\Models\Outlet;
use App\Models\StockItem;
use App\Models\MenuStockItem;
use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use App\Http\Requests\dashboard\MenuManagementRequest;

class MenuManagementController extends Controller
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
        $menus = Menu::where('outlet_id', $outlet->id)->get();
        $menus->load('menuImages');

        return view('dashboard.menu-management.index', compact('menus', 'outlet'));
    }

    public function create(Outlet $outlet)
    {
        $stockItems = StockItem::where('outlet_id', $outlet->id)->get();
        $stockItems->load('unit');
        return view('dashboard.menu-management.create', compact('stockItems', 'outlet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuManagementRequest $request, Outlet $outlet)
    {
        $validatedData = $request->validated();
        $validatedData['outlet_id'] = $outlet->id;

        $menu = Menu::create($validatedData);

        foreach (range(1, 4) as $index) {
            if (isset($validatedData["menu_pict_{$index}"])) {
                $validatedData["image_path_{$index}"] = $this->imageUploadService->uploadImage($validatedData["menu_pict_{$index}"], "{$outlet->slug}/menus");
                $menu->menuImages()->create(['image_path' => $validatedData["image_path_{$index}"]]);
            }
        }

        if (!isset($validatedData['stock_item_id'])) {
            $validatedData['stock_item_id'] = [];
            $validatedData['quantity'] = [];
        }
        foreach ($validatedData['stock_item_id'] as $index => $stockItemId) {
            MenuStockItem::updateOrCreate(
                [
                    'menu_id' => $menu->id,
                    'stock_item_id' => $stockItemId,
                ],
                [
                    'quantity' => $validatedData['quantity'][$index]
                ]
            );
        }

        return redirect()
            ->to(roleBasedRoute('menu.index', ['outlet' => $outlet->slug]))
            ->with('success', 'Menu ' . $menu->name . ' berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $menu = Menu::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $menu->load('menuImages', 'stockItems');

        $stockItems = StockItem::where('outlet_id', $outlet->id)->get();
        $stockItems->load('unit');

        $checkedItems = [];
        $uncheckedItems = [];

        foreach ($stockItems as $stockItem) {
            $menuStockItem = $menu->stockItems->firstWhere('id', $stockItem->id);
            $isChecked = !is_null($menuStockItem);

            if ($isChecked) {
                $checkedItems[] = ['stockItem' => $stockItem, 'menuStockItem' => $menuStockItem];
            } else {
                $uncheckedItems[] = ['stockItem' => $stockItem, 'menuStockItem' => null];
            }
        }

        $sortedItems = array_merge($checkedItems, $uncheckedItems);

        return view('dashboard.menu-management.edit', compact('menu', 'sortedItems', 'outlet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuManagementRequest $request, $param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $menu = Menu::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();

        $validatedData = $request->validated();
        $menu->update($validatedData);

        $existingImages = $menu->menuImages;

        if (!isset($validatedData['delete_image'])) {
            $validatedData['delete_image'] = [];
        }
        foreach ($validatedData['delete_image'] as $imageId) {
            $image = $existingImages->where('id', $imageId)->first();
            $this->imageUploadService->deleteImage($image->image_path);
            $image->delete();
        }

        if (!isset($validatedData['stock_item_id'])) {
            $validatedData['stock_item_id'] = [];
            $validatedData['quantity'] = [];
        }

        // Delete existing stock items that are not in the request
        $menu->stockItems()->whereNotIn('stock_item_id', $validatedData['stock_item_id'])->detach();

        foreach ($validatedData['stock_item_id'] as $index => $stockItemId) {
            MenuStockItem::updateOrCreate(
                [
                    'menu_id' => $menu->id,
                    'stock_item_id' => $stockItemId,
                ],
                [
                    'quantity' => $validatedData['quantity'][$index]
                ]
            );
        }

        // Update gambar yang ada atau tambahkan gambar baru
        foreach (range(1, 4) as $index) {
            $imageKey = "menu_pict_{$index}";

            if ($request->hasFile($imageKey)) {
                // Jika sudah ada gambar untuk index ini, hapus gambar lama
                if (isset($existingImages[$index - 1])) {
                    $this->imageUploadService->deleteImage($existingImages[$index - 1]->image_path);
                    $existingImages[$index - 1]->update([
                        'image_path' => $this->imageUploadService->uploadImage($request->file($imageKey), "{$outlet->slug}/menus")
                    ]);
                } else {
                    // Jika belum ada gambar di slot ini, buat gambar baru
                    $menu->menuImages()->create([
                        'image_path' => $this->imageUploadService->uploadImage($request->file($imageKey), "{$outlet->slug}/menus")
                    ]);
                }
            }
        }

        return redirect()
            ->to(roleBasedRoute('menu.index', ['outlet' => $outlet->slug]))
            ->with('success', 'Menu updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $menu = Menu::findOrFail($id)->load('menuImages');
        $menu->menuImages->each(function ($image) {
            $this->imageUploadService->deleteImage($image->image_path);
            $image->delete();
        });
        $menu->delete();

        return redirect()
            ->back()
            ->with('success', 'Menu ' . $menu->name . ' berhasil dihapus.');
    }
}
