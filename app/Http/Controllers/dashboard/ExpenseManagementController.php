<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Outlet;
use App\Models\Expense;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\ExpensesExport;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Facades\LogBatch;
use App\Traits\ExpenseAuthorizationTrait;
use App\Http\Requests\dashboard\ExpenseManagementRequest;

class ExpenseManagementController extends Controller
{
    use ExpenseAuthorizationTrait;

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
            $expenses = Expense::where('outlet_id', $outlet->id)
                ->where('user_id', auth()->id())
                ->with('items')
                ->latest()
                ->get();
        } else {
            $expenses = Expense::where('outlet_id', $outlet->id)
                ->with('items')
                ->latest()
                ->get();
        }

        return view('dashboard.expense-management.index', compact('outlet', 'expenses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseManagementRequest $request, Outlet $outlet)
    {
        $validatedData = $request->validated();
        $validatedData['outlet_id'] = $outlet->id;

        $stockItemIds = collect($validatedData['items'])->pluck('stock_item_id')->filter()->toArray();
        $stockItems = StockItem::whereIn('id', $stockItemIds)->get();

        DB::beginTransaction();
        LogBatch::startBatch();

        try {

            foreach ($validatedData['items'] as $index => $item) {
                $stockItem = $stockItems->firstWhere('id', $item['stock_item_id']);
                if ($stockItem) {
                    $validatedData['items'][$index]['name'] = $stockItem->name;
                    $validatedData['items'][$index]['price'] = $stockItem->price;
                    $validatedData['items'][$index]['subtotal'] = $stockItem->price * $item['quantity'];

                    StockItem::deductStock($stockItem->id, $outlet->id, $item['quantity']);
                } else {
                    $validatedData['items'][$index]['price'] = (int) $item['price'];
                }

                if (isset($item['image'])) {
                    $imagePath = ImageUploadService::uploadImage($item['image'], "{$outlet->slug}/expenses");
                    $validatedData['items'][$index]['image_path'] = $imagePath;
                }

                $validatedData['items'][$index]['subtotal'] = $item['price'] * $item['quantity'];
            }

            $batchUuid = LogBatch::getUuid();

            $validatedData['batch_uuid'] = $batchUuid;

            // calculate total
            $validatedData['total'] = array_sum(array_column($validatedData['items'], 'subtotal'));
            $validatedData['user_id'] = auth()->id();

            $validatedData['date_out'] = date('Y-m-d H:i:s', strtotime($validatedData['date_out']));

            //dd($validatedData);

            $expense = Expense::create($validatedData);
            $expense->items()->createMany($validatedData['items']);

            DB::commit();

            session()->flash('success', $expense['name'] . ' berhasil ditambahkan.');

            return response()->json([
                'status' => true,
                'code' => 201,
                'data' => $expense,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }

        LogBatch::endBatch();
    }

    public function create(Outlet $outlet)
    {
        $stockItems = StockItem::where('outlet_id', $outlet->id)
            ->with(['outlet', 'unit', 'category'])
            ->orderBy('stock', 'asc')
            ->get();

        return view('dashboard.expense-management.create', compact('outlet', 'stockItems'));
    }

    /**
     * Display the specified resource.
     */
    public function show($param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        if (auth()->user()->hasRole('staff')) {
            $Expense = Expense::where('id', $id)
                ->where('outlet_id', $outlet->id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
        } else {
            $Expense = Expense::where('id', $id)
                ->where('outlet_id', $outlet->id)
                ->firstOrFail();
        }
        $Expense->load('items', 'user');

        return view('dashboard.expense-management.show', compact('Expense', 'outlet'));
    }

    public function fetch($param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        if (auth()->user()->hasRole('staff')) {
            $Expense = Expense::where('id', $id)
                ->where('outlet_id', $outlet->id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
        } else {
            $Expense = Expense::where('id', $id)
                ->where('outlet_id', $outlet->id)
                ->firstOrFail();
        }

        $Expense->load('items', 'user');

        if ($Expense) {
            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $Expense,
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

    public function edit($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $expense = Expense::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->with('items', 'user')
            ->firstOrFail();

        $this->canUpdateExpense($expense);

        $stockItems = StockItem::where('outlet_id', $outlet->id)
            ->with(['outlet', 'unit', 'category'])
            ->orderBy('stock', 'asc')
            ->get();

        return view('dashboard.expense-management.edit', compact('expense', 'stockItems', 'outlet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseManagementRequest $request, $param1, $param2 = null)
    {
        // ExpenseManagementRequest
        //dd($request->all());

        list($outlet, $id) = $this->processParameters($param1, $param2);
        $expense = Expense::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();

        $validatedData = $request->validated();
        $validatedData['outlet_id'] = $outlet->id;

        $stockItemIds = collect($validatedData['items'])->pluck('stock_item_id')->filter()->toArray();
        $stockItems = StockItem::whereIn('id', $stockItemIds)->get();

        DB::beginTransaction();
        LogBatch::startBatch();

        try {

            foreach ($validatedData['items'] as $index => $item) {

                $itemExists = $expense->items()->where('id', $item['id'])->first();
                $stockItem = $stockItems->firstWhere('id', $item['stock_item_id']);

                if ($itemExists) {
                    if (isset($item['remove']) && $item['remove']) {
                        if ($stockItem) StockItem::restock($itemExists->stock_item_id, $outlet->id, $itemExists->quantity);
                        if ($itemExists->image_path) {
                            ImageUploadService::deleteImage($itemExists->image_path);
                        }

                        $itemExists->delete();
                        unset($validatedData['items'][$index]);
                        continue;
                    } else {
                        if ($stockItem) {
                            if ($item['stock_item_id'] == $itemExists->stock_item_id) {
                                if ($item['quantity'] < $itemExists->quantity) {
                                    // tambah stock jika quantity sekarang lebih kecil dari sebelumnya
                                    StockItem::restock($itemExists->stock_item_id, $outlet->id, $itemExists->quantity - $item['quantity']);
                                    // dd($itemExists->quantity - $item['quantity']);
                                } elseif ($item['quantity'] > $itemExists->quantity) {
                                    // kurangi stock jika quantity sekarang lebih besar dari sebelumnya
                                    StockItem::deductStock($itemExists->stock_item_id, $outlet->id, $item['quantity'] - $itemExists->quantity);
                                    // dd($item['quantity'] - $itemExists->quantity);
                                }
                            } else {
                                StockItem::restock($itemExists->stock_item_id, $outlet->id, $itemExists->quantity);
                                StockItem::deductStock($item['stock_item_id'], $outlet->id, $item['quantity']);
                            }

                            $validatedData['items'][$index]['id'] = $stockItem->id;
                            $validatedData['items'][$index]['name'] = $stockItem->name;
                            $validatedData['items'][$index]['price'] = $stockItem->price;
                        }
                    }

                    if (isset($item['image'])) {
                        if ($itemExists->image_path) {
                            ImageUploadService::deleteImage($itemExists->image_path);
                        }

                        $imagePath = ImageUploadService::uploadImage($item['image'], "{$outlet->slug}/expenses");
                        $validatedData['items'][$index]['image_path'] = $imagePath;
                    }

                    $validatedData['items'][$index]['subtotal'] = $item['price'] * $item['quantity'];

                    $itemExists->update($validatedData['items'][$index]);
                } else {
                    if ($stockItem) {
                        $validatedData['items'][$index]['name'] = $stockItem->name;
                        $validatedData['items'][$index]['price'] = $stockItem->price;

                        StockItem::deductStock($stockItem->id, $outlet->id, $item['quantity']);
                    } else {
                        $validatedData['items'][$index]['price'] = (int) $item['price'];
                    }

                    if (isset($item['image'])) {
                        $imagePath = ImageUploadService::uploadImage($item['image'], "{$outlet->slug}/expenses");
                        $validatedData['items'][$index]['image_path'] = $imagePath;
                    }

                    $validatedData['items'][$index]['subtotal'] = $item['price'] * $item['quantity'];
                    $expense->items()->create($validatedData['items'][$index]);
                }
            }

            $batchUuid = LogBatch::getUuid();
            $validatedData['batch_uuid'] = $batchUuid;

            // calculate total
            $validatedData['total'] = array_sum(array_column($validatedData['items'], 'subtotal'));
            $validatedData['user_id'] = auth()->id();
            $validatedData['date_out'] = date('Y-m-d H:i:s', strtotime($validatedData['date_out']));

            $expense->update($validatedData);

            DB::commit();

            session()->flash('success', $expense['name'] . ' berhasil diubah.');

            return response()->json([
                'status' => true,
                'code' => 201,
                'data' => $expense,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }

        LogBatch::endBatch();
    }

    public function cancel($param1, $param2 = null)
    {
        list($outlet, $id) = $this->processParameters($param1, $param2);

        $expense = Expense::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();

        $this->canUpdateExpense($expense);

        DB::beginTransaction();
        LogBatch::startBatch();

        try {
            foreach ($expense->items as $item) {
                if ($item->stock_item_id) {
                    StockItem::restock($item->stock_item_id, $outlet->id, $item->quantity);
                }
            }

            $expense->update(['status' => 'canceled']);

            DB::commit();

            return back()->with('success', $expense['name'] . ' berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors($e->getMessage());
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

            $nameFile = 'expenses-' . $outlet->slug . '-' . $startDate->format('dmY') . '-' . $endDate->format('dmY') . '-' . now()->format('YmdHis');

            if (auth()->user()->hasRole('staff')) {
                $expenses = Expense::where('outlet_id', $outlet->id)
                    ->where('user_id', auth()->id())
                    ->where('status', 'submitted')
                    ->whereBetween('date_out', [
                        date('Y-m-d 00:00:00', strtotime($validatedData['start_date'])),
                        date('Y-m-d 23:59:59', strtotime($validatedData['end_date'])),
                    ])
                    ->with('items')
                    ->get();
            } else {
                $expenses = Expense::where('outlet_id', $outlet->id)
                    ->where('status', 'submitted')
                    ->whereBetween('date_out', [
                        date('Y-m-d 00:00:00', strtotime($validatedData['start_date'])),
                        date('Y-m-d 23:59:59', strtotime($validatedData['end_date'])),
                    ])
                    ->with('items')
                    ->get();
            }

            if ($expenses->isEmpty()) {
                throw new \Exception('Tidak ada data yang ditemukan untuk periode yang dipilih.');
            }

            if ($validatedData['export_as'] == 'excel') {
                return Excel::download(new ExpensesExport($expenses, $outlet, $validatedData['start_date'], $validatedData['end_date']),  $nameFile . '.xlsx');
            } else if ($validatedData['export_as'] == 'pdf') {
                $pdf = PDF::loadView('dashboard.expense-management.export-pdf', compact('outlet', 'expenses', 'validatedData'));
                return $pdf->download($nameFile  . '.pdf');
            } else {
                throw new \Exception('Format ekspor tidak valid.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('report-error', $e->getMessage());
        }
    }

    // public function previewPdf(Outlet $outlet)
    // {
    //     $expenses = Expense::where('outlet_id', $outlet->id)
    //         ->with('items')
    //         ->get();

    //     return view('dashboard.expense-management.export-pdf', compact('outlet', 'expenses'));
    // }
}
