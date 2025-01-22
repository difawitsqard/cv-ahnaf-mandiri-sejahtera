<?php

namespace App\Http\Controllers\dashboard;

use Exception;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\StockItem;
use Mike42\Escpos\Printer;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\OrderAuthorizationTrait;
use Spatie\Activitylog\Models\Activity;
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
        if (auth()->user()->hasRole('staff')) {
            $orders = Order::where('outlet_id', $outlet->id)
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $orders = Order::where('outlet_id', $outlet->id)->get();
        }

        $orders->load('items.menu', 'user');

        return view('dashboard.order.index', compact('orders', 'outlet'));
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
            return response()->json(
                [
                    'status' => false,
                    'code' => 422,
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        $validated = $validator->validated();

        // Ambil data menu langsung dari database
        $menuIds = collect($validated['cart'])->pluck('id');
        $menus = Menu::whereIn('id', $menuIds)->with('stockItems')->get()->keyBy('id');

        // Grouping berdsasrkan stock item yang sama and totaling pivot_quantity to check if the stock is sufficient
        $stockItems = [];

        foreach ($validated['cart'] as $item) {
            $menu = $menus->get($item['id']);
            $menu->stockItems->each(function ($stockItem) use ($item, &$stockItems) {
                $quantity = $stockItem->pivot->quantity * $item['quantity'];
                if (!isset($stockItems[$stockItem->id])) {
                    $stockItems[$stockItem->id] = 0;
                }
                $stockItems[$stockItem->id] += $quantity;
            });
        }

        // Check if the stock is sufficient
        foreach ($stockItems as $stockItemId => $quantity) {
            $stockItem = StockItem::find($stockItemId);
            if ($stockItem->stock < $quantity) {
                return response()->json([
                    'status' => false,
                    'code' => 422,
                    'message' => 'Dibutuhkan ' . $quantity . ' ' . $stockItem->unit->name . ' ' . $stockItem->name . ' untuk memproses pesanan ini, stok yang tersedia hanya ' . $stockItem->stock . ' ' . $stockItem->unit->name . '.',
                ], 422);
            }
        }

        // Hitung total harga dan persiapkan data untuk order items
        $total = 0;
        $orderItems = [];

        LogBatch::startBatch();

        try {
            foreach ($validated['cart'] as $item) {
                $menu = $menus->get($item['id']);
                if (!$menu) {
                    return response()->json([
                        'status' => false,
                        'code' => 404,
                        'message' => 'Menu not found.',
                    ], 404);
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
            $discount = ($subtotal * $outlet->discount) / 100;

            // Menghitung harga setelah diskon
            $total = $subtotal - $discount;

            // Menghitung pajak berdasarkan subtotal atau harga setelah diskon
            $tax = ($total * $outlet->tax) / 100;

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
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }

        LogBatch::endBatch();
    }

    /**
     * Display the specified resource.
     */
    public function show($param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        if (auth()->user()->hasRole('staff')) {
            $order = Order::where('id', $id)
                ->where('outlet_id', $outlet->id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
        } else {
            $order = Order::where('id', $id)
                ->where('outlet_id', $outlet->id)
                ->firstOrFail();
        }
        $order->load('items.menu');

        return view('dashboard.order.show', compact('order', 'outlet'));
    }

    public function printThermal($param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

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
                $textLength = mb_strlen($text); // Menghitung panjang teks

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
            $printer->text(str_repeat('=', 32) . "\n");
            $printer->text(textLeftRight('No Tagihan', '#' . $order->id) . "\n");
            $printer->text(textLeftRight('Kasir', fitText($order->user->name, 10, 'start')) . "\n");
            // Customer Name (if exists)
            if ($order->name) {
                $printer->text(textLeftRight('Nama Order', $order->name) . "\n");
            }
            $printer->text(textLeftRight($order->created_at->format('d M Y'), $order->created_at->format('H:i')) . "\n");

            $printer->text(str_repeat('-', 32) . "\n");

            // Item Belanja
            foreach ($order->items as $item) {
                $itemDescription = fitText($item->quantity, 4) . '' . $item->menu->name;
                $wrappedItemDescription = wrapText($itemDescription, 20); // Batasi lebar deskripsi item
                $subtotal = $item->subtotal;

                // Cetak setiap baris hasil pembungkusan
                foreach ($wrappedItemDescription as $index => $line) {
                    if ($index === 0) {
                        $printer->text(textLeftRight($line, number_format($subtotal, 0, ',', '.')) . "\n");
                    } else {
                        $printer->text(fitText('', 4) . $line . "\n");
                    }
                }
            }
            $printer->text(str_repeat('-', 32) . "\n");

            $sub_total = $order->items->sum('subtotal');
            $total = $sub_total;

            $discount = ($total * $order->discount) / 100;
            $total = $total - $discount;

            $tax = ($total * $order->tax) / 100;
            $total = $sub_total - $discount + $tax;

            // Total Information
            $printer->text(textLeftRight('Subtotal ' . $order->items->sum('quantity') . ' Produk', fitText(formatRupiah($sub_total), 10, 'front')) . "\n");
            if ($discount > 0) {
                $printer->text(textLeftRight('Diskon', fitText('-' . formatRupiah($discount), 10, 'front')) . "\n");
            }

            if ($tax > 0) {
                $printer->text(textLeftRight('Pajak', fitText(formatRupiah($tax), 10, 'front')) . "\n");
            }
            $printer->setEmphasis(true);
            $printer->text(textLeftRight('TOTAL', 'Rp' . fitText(formatRupiah($order->total), 10, 'front')) . "\n");
            $printer->setEmphasis(false);

            // Pembayaran
            $printer->text(str_repeat('-', 32) . "\n");

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
            $printer->text(textLeftRight($paymentMethod, fitText(formatRupiah($order->total), 10, 'front')) . "\n");
            $printer->text(textLeftRight('Total Bayar', fitText(formatRupiah($order->paid), 10, 'front')) . "\n");
            $printer->text(textLeftRight('Kembalian', fitText(formatRupiah($order->change), 10, 'front')) . "\n");

            $printer->text(str_repeat('=', 32) . "\n");

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

    public function cancel($param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        $order = Order::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();

        if (!$order->can_be_canceled) {
            return back()->withErrors(["Pesanan #{$order->id} tidak dapat dibatalkan."]);
        }

        DB::beginTransaction();
        LogBatch::startBatch();

        try {
            //get activity log by uuid
            $activity = Activity::where('batch_uuid', $order->batch_uuid)
                ->where('event', 'deducted')
                ->get();

            // kemablikan stock item
            foreach ($activity as $act) {
                try {
                    $quantity = abs($act->properties['qty']);
                    StockItem::restock($act->subject_id, $outlet->id, $quantity);
                } catch (Exception $e) {
                    // Abaikan jika terjadi error saat restock
                }
            }

            $order->update(['status' => 'canceled']);

            DB::commit();
            return back()->with('success', "Pesanan #{$order->id} berhasil dibatalkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([$e->getMessage()]);
        }

        LogBatch::endBatch();
    }

    public function export(Request $request, Outlet $outlet)
    {
        try {
            $validatedData = $request->validate([
                'start_date' => 'required|date_format:d M Y',
                'end_date' => 'required|date_format:d M Y|after_or_equal:start_date',
                'export_as' => 'required|in:pdf,excel',
            ]);
            $startDate = Carbon::createFromFormat('d M Y', $validatedData['start_date']);
            $endDate = Carbon::createFromFormat('d M Y', $validatedData['end_date']);

            $nameFile = 'revenue-' . $outlet->slug . '-' . $startDate->format('dmY') . '-' . $endDate->format('dmY') . '-' . now()->format('YmdHis');

            if (auth()->user()->hasRole('staff')) {
                $orders = Order::where('outlet_id', $outlet->id)
                    ->where('status', 'completed')
                    ->where('user_id', auth()->id())
                    ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($validatedData['start_date'])), date('Y-m-d 23:59:59', strtotime($validatedData['end_date']))])
                    ->with('items')
                    ->get();
            } else {
                $orders = Order::where('outlet_id', $outlet->id)
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($validatedData['start_date'])), date('Y-m-d 23:59:59', strtotime($validatedData['end_date']))])
                    ->with('items')
                    ->get();
            }

            if ($orders->isEmpty()) {
                throw new \Exception('Tidak ada data yang ditemukan untuk periode yang dipilih.');
            }

            if ($validatedData['export_as'] == 'excel') {
                return Excel::download(new OrderExport($orders, $outlet, $validatedData['start_date'], $validatedData['end_date']), $nameFile . '.xlsx');
            } elseif ($validatedData['export_as'] == 'pdf') {
                $pdf = PDF::loadView('dashboard.order.export-pdf', compact('outlet', 'orders', 'validatedData'));
                return $pdf->download($nameFile . '.pdf');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('report-error', $e->getMessage());
        }
    }
}
