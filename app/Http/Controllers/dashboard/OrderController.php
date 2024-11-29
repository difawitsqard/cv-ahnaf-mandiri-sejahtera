<?php

namespace App\Http\Controllers\dashboard;

use Exception;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\StockItem;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Facades\LogBatch;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;

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

        LogBatch::startBatch();

        foreach ($validated['cart'] as $item) {
            $menu = $menus->get($item['id']);
            if (!$menu) {
                abort(404, "Menu with ID {$item['id']} not found.");
            }

            $subtotal = $menu->price * $item['quantity'];
            $total += $subtotal;

            $menu->stockItems->each(function ($stockItem) use ($item, $outlet) {
                $quantity = $stockItem->pivot->quantity * $item['quantity'];
                StockItem::deductStock($stockItem->id, $outlet->id, $quantity);
            });

            $orderItems[] = [
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'price' => $menu->price,
                'subtotal' => $subtotal,
            ];
        }

        $batchUuid = LogBatch::getUuid();

        // Buat pesanan
        $order = Order::create([
            'name' => $validated['name'],
            'outlet_id' => $outlet->id,
            'total' => $total,
            'batch_uuid' => $batchUuid,
        ]);

        LogBatch::endBatch();

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
        // $order = Order::findOrFail($id);
        // $order->load('items.menu');

        // return view('dashboard.order.show', compact('order', 'outlet'));

        try {
            // Koneksi printer (gunakan connector yang sesuai)


            $connector = new RawbtPrintConnector();


            // Menggunakan printer
            $printer = new Printer($connector);

            // Menampilkan nama toko
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Toko Contoh\n");
            $printer->feed();

            // Menampilkan item
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            // Menampilkan total
            $printer->setEmphasis(true);
            $printer->text("Total: Rp 100.000\n");
            $printer->setEmphasis(false);
            $printer->feed();

            // Menampilkan pesan terima kasih
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima kasih sudah belanja!\n");

            // Barcode (jika dibutuhkan)
            $printer->barcode("ABC123", Printer::BARCODE_CODE39);

            // Memotong struk
            $printer->cut();

            // Menutup koneksi printer
            $printer->close();

            return response()->json(['message' => 'Struk berhasil dicetak.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Gagal mencetak: ' . $e->getMessage()], 500);
        }
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
