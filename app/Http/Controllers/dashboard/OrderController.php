<?php

namespace App\Http\Controllers\dashboard;

use Exception;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\StockItem;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Facades\LogBatch;
use Illuminate\Support\Facades\Validator;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;

class OrderController extends Controller
{
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
        $menus->load('menuImages', 'stockItems');

        $orders = Order::where('outlet_id', $outlet->id)->get();
        $orders->load('items.menu', 'user');

        return view('dashboard.order.index',  compact('menus', 'orders', 'outlet'));
    }

    public function create(Outlet $outlet)
    {
        $menus = Menu::where('outlet_id', $outlet->id)->get();
        $menus->load('menuImages', 'stockItems');
        // $menus = $menus->sortBy('max_order_quantity', SORT_REGULAR, true);

        return view('dashboard.order.create', compact('menus', 'outlet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Outlet $outlet)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'print_receipt' => 'nullable|boolean',
            'name' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,transfer_bank,credit_card,other',
            'paid' => 'required|integer|min:0',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|integer|exists:menus,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.note' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

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
                'note' => $item['note'] ?? null,
                'subtotal' => $subtotal,
            ];
        }

        $subtotal = $total;

        // Menghitung diskon
        $discount = $subtotal * $outlet->discount / 100;

        // Menghitung harga setelah diskon
        $total = $subtotal - $discount;

        // Menghitung pajak berdasarkan subtotal atau harga setelah diskon
        $tax = $total * $outlet->tax / 100;

        // Menghitung total akhir
        $total = $total + $tax;

        $batchUuid = LogBatch::getUuid();

        // Buat pesanan
        $order = Order::create([
            'name' => $validated['name'],
            'outlet_id' => $outlet->id,
            'sub_total' => $subtotal,
            'payment_method' => $validated['payment_method'],
            'paid' => $validated['paid'],
            'change' => $validated['paid'] - $total,
            'status' => 'completed',
            'discount' => $outlet->discount,
            'tax' => $outlet->tax,
            'total' => $total,
            'user_id' => auth()->id(),
            'batch_uuid' => $batchUuid,
        ]);

        LogBatch::endBatch();

        // Simpan item pesanan
        $order->items()->createMany($orderItems);

        // Cetak struk jika diminta
        if ($validated['print_receipt']) {
            session()->flash('print_receipt', $order->id);
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => [
                'order_id' => $order->id,
                'total' => $order->total,
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $order = Order::findOrFail($id);
        $order->load('items.menu');

        return view('dashboard.order.show', compact('order', 'outlet'));
    }

    public function printThermal($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $order = Order::findOrFail($id);
        $order->load('items.menu', 'user');

        try {
            $connector = new RawbtPrintConnector();
            // $connector = new NetworkPrintConnector("192.168.1.3", 1234);

            // Menggunakan printer
            $printer = new Printer($connector);


            // Fungsi untuk rata kiri-kanan
            function textLeftRight($left, $right, $width = 32)
            {
                $leftWidth = mb_strlen($left);
                $rightWidth = mb_strlen($right);
                $space = $width - $leftWidth - $rightWidth;
                return $left . str_repeat(' ', max(0, $space)) . $right; // Pastikan tidak ada spasi negatif
            }

            // Fungsi untuk membungkus teks
            function wrapText($text, $maxWidth)
            {
                $words = explode(' ', $text);
                $lines = [];
                $currentLine = '';

                foreach ($words as $word) {
                    // Jika menambahkan kata baru melebihi maxWidth, simpan baris saat ini dan mulai baris baru
                    if (mb_strlen($currentLine) + mb_strlen($word) + 1 > $maxWidth) {
                        $lines[] = trim($currentLine);
                        $currentLine = '';
                    }
                    $currentLine .= ' ' . $word;
                }
                // Menambahkan baris terakhir jika ada
                if ($currentLine) {
                    $lines[] = trim($currentLine);
                }

                return $lines;
            }

            function fitText($text, $length = 4, $position = 'behind')
            {
                $textLength = mb_strlen($text);  // Menghitung panjang teks

                if ($textLength < $length) {
                    // Tambahkan spasi di belakang atau di depan sesuai dengan opsi
                    $space = $length - $textLength;
                    if ($position === 'behind') {
                        return $text . str_repeat(' ', $space); // Tambahkan spasi di belakang
                    } else {
                        return str_repeat(' ', $space) . $text; // Tambahkan spasi di depan
                    }
                } elseif ($textLength > $length) {
                    // Potong teks jika panjangnya lebih panjang dari yang diinginkan
                    return mb_substr($text, 0, $length);
                } else {
                    // Jika panjang teks sudah sesuai dengan panjang yang diinginkan
                    return $text;
                }
            }

            // Header dengan rata tengah
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text($outlet->name . "\n");
            $printer->setEmphasis(false);
            $printer->text($outlet->address . ($outlet->phone_number ? ", $outlet->phone_number" : '') . "\n");
            $printer->feed();

            // Info Transaksi rata kiri
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text(str_repeat("=", 32) . "\n");
            $printer->text(textLeftRight("No Tagihan", "#" . $order->id) . "\n");
            $printer->text(textLeftRight("Kasir", fitText($order->user->name, 10, "start")) . "\n");
            // Customer Name (if exists)
            if ($order->name) {
                $printer->text(textLeftRight("Nama Order", $order->name) . "\n");
            }
            $printer->text(textLeftRight($order->created_at->format('d M Y'), $order->created_at->format('H:i')) . "\n");

            $printer->text(str_repeat("-", 32) . "\n");

            // Item Belanja
            foreach ($order->items as $item) {
                $itemDescription = fitText($item->quantity, 4) . "" . $item->menu->name;
                $wrappedItemDescription = wrapText($itemDescription, 20); // Batasi lebar deskripsi item
                $subtotal = $item->subtotal;

                // Cetak setiap baris hasil pembungkusan
                foreach ($wrappedItemDescription as $index => $line) {
                    if ($index === 0) {
                        $printer->text(textLeftRight($line, number_format($subtotal, 0, ',', '.')) . "\n");
                    } else {
                        $printer->text(fitText("", 4) . $line . "\n");
                    }
                }
            }
            $printer->text(str_repeat("-", 32) . "\n");

            $sub_total = $order->items->sum('subtotal');
            $total = $sub_total;

            $discount = ($total * $order->discount) / 100;
            $total = $total - $discount;

            $tax = ($total * $order->tax) / 100;
            $total = $sub_total - $discount + $tax;

            // Total Information
            $printer->text(textLeftRight("Subtotal " . $order->items->sum('quantity') . " Produk", fitText(formatRupiah($sub_total), 10, "front")) . "\n");
            if ($discount > 0) {
                $printer->text(textLeftRight("Diskon", fitText("-" . formatRupiah($discount), 10, "front")) . "\n");
            }

            if ($tax > 0) {
                $printer->text(textLeftRight("Pajak", fitText(formatRupiah($tax), 10, "front")) . "\n");
            }
            $printer->setEmphasis(true);
            $printer->text(textLeftRight("TOTAL", "Rp" . fitText(formatRupiah($order->total), 10, "front")) . "\n");
            $printer->setEmphasis(false);

            // Pembayaran
            $printer->text(str_repeat("-", 32) . "\n");

            switch ($order->payment_method) {
                case 'cash':
                    $paymentMethod = 'Tunai';
                    break;
                case 'credit_card':
                    $paymentMethod = 'Kartu Kredit';
                    break;
                case 'bank_transfer':
                    $paymentMethod = 'Transfer Bank';
                    break;
                case 'other':
                    $paymentMethod = 'Lainnya';
                    break;
                default:
                    $paymentMethod = $order->payment_method;
            }
            $printer->text(textLeftRight($paymentMethod, fitText(formatRupiah($order->total), 10, "front")) . "\n");
            $printer->text(textLeftRight("Total Bayar", fitText(formatRupiah($order->paid), 10, "front")) . "\n");
            $printer->text(textLeftRight("Kembalian", fitText(formatRupiah($order->change), 10, "front")) . "\n");

            $printer->text(str_repeat("=", 32) . "\n");

            // $printer->barcode($order->id, Printer::BARCODE_CODE39);

            // Footer rata tengah
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Silahkan datang kembali\n");
            $printer->text("Terima kasih\n");
            $printer->cut();
            $printer->close();

            // return response()->json(['message' => 'Struk berhasil dicetak.']);
        } catch (Exception $e) {
            // return response()->json(['error' => 'Gagal mencetak: ' . $e->getMessage()], 500);
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
