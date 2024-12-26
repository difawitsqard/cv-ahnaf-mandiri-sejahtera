<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Outlet;
use App\Models\Expense;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Spatie\Activitylog\Facades\LogBatch;
use App\Http\Requests\dashboard\ExpenseManagementRequest;

class ExpenseManagementController extends Controller
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
        $expenses = Expense::where('outlet_id', $outlet->id)
            ->with('items')
            ->latest()
            ->get();

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
    public function show(string $id)
    {
        //
    }

    public function fetch($param1, $param2 = null)
    {
        [$outlet, $id] = $this->processParameters($param1, $param2);

        $Expenses = Expense::where('id', $id)
            ->where('outlet_id', $outlet->id)
            ->firstOrFail();
        $Expenses->load('outlet', 'items.stockItem.unit', 'items.stockItem.category');

        if ($Expenses) {
            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $Expenses,
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

    public function fetchAll(Outlet $outlet)
    {
        $Expenses = Expense::where('outlet_id', $outlet->id)
            ->with('items.stockItem.unit', 'items.stockItem.category')
            ->get();

        if ($Expenses) {
            return response()->json([
                'status' => true,
                'code' => 200,
                'data' => $Expenses,
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
            ->firstOrFail();

        $expense->load('items', 'user');

        return view('dashboard.expense-management.edit', compact('expense', 'outlet'));
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
