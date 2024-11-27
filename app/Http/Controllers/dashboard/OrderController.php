<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\StockItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Facades\LogBatch;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Outlet $outlet)
    {
        $menus = Menu::where('outlet_id', $outlet->id)->get();
        $menus->load('menuImages', 'stockItems');
        //$menus = $menus->sortBy('max_order_quantity', SORT_REGULAR, true);

        return view('dashboard.order.index', compact('menus', 'outlet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Outlet $outlet)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|integer|exists:menus,id', // Validasi bahwa menu ID ada
            'cart.*.quantity' => 'required|integer|min:1', // Kuantitas harus valid
        ]);

        // Ambil data menu langsung dari database
        $menuIds = collect($validated['cart'])->pluck('id');
        $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

        // Hitung total harga dan persiapkan data untuk order items
        $total = 0;
        $orderItems = [];

        foreach ($validated['cart'] as $item) {
            $menu = $menus->get($item['id']);
            if (!$menu) {
                abort(404, "Menu with ID {$item['id']} not found.");
            }

            $subtotal = $menu->price * $item['quantity'];
            $total += $subtotal;

            LogBatch::startBatch();
            $menu->stockItems->each(function ($stockItem) use ($item, $outlet) {
                $quantity = $stockItem->pivot->quantity * $item['quantity'];
                StockItem::deductStock($stockItem->id, $outlet->id, $quantity);
            });
            LogBatch::endBatch();

            $orderItems[] = [
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'price' => $menu->price,
                'subtotal' => $subtotal,
            ];
        }

        // Buat pesanan
        $order = Order::create([
            'name' => $validated['name'],
            'outlet_id' => $outlet->id,
            'total' => $total,
        ]);

        // Simpan item pesanan
        $order->items()->createMany($orderItems);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'order_id' => $order->id,
                'total' => $order->total,
            ],
        ]);

        // dd(request()->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Outlet $outlet)
    {
        $order = Order::findOrFail($id);
        $order->load('items.menu');

        return view('dashboard.order.show', compact('order', 'outlet'));
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
