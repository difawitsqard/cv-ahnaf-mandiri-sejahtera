<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Order;
use App\Models\Outlet;
use App\Models\Expense;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Outlet $outlet)
    {
        if (auth()->user()?->hasRole(['admin', 'superadmin'])) {
            return $this->dashboardAdminSuperadmin($outlet);
        }
        return abort(403);
    }

    private function dashboardAdminSuperadmin(Outlet $outlet)
    {
        $startOfPeriod = now()->subMonths(12)->startOfMonth(); // 12 bulan ke belakang + bulan ini = 13 bulan
        $endOfPeriod = now()->endOfMonth();

        // Ambil data pendapatan dan pengeluaran dalam satu query masing-masing
        $orderData = Order::where('outlet_id', $outlet->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total_orders, SUM(total) as total_revenue')
            ->groupBy('year', 'month')
            ->get()
            ->mapWithKeys(function ($order) {
                $key = $order->year . '-' . str_pad($order->month, 2, '0', STR_PAD_LEFT);
                return [
                    $key => [
                        'total_orders' => $order->total_orders,
                        'total_revenue' => $order->total_revenue,
                    ],
                ];
            });

        $expenseData = Expense::where('outlet_id', $outlet->id)
            ->where('status', 'submitted')
            ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
            ->with('items')
            ->get()
            ->groupBy(function ($expense) {
                return $expense->created_at->format('Y-m');
            })
            ->mapWithKeys(function ($expenses, $key) {
                $totalExpenseAmount = $expenses->flatMap->items->sum(function ($item) {
                    return $item->quantity * $item->price;
                });

                return [
                    $key => [
                        'total_expenses' => $expenses->count(),
                        'total_expense_amount' => $totalExpenseAmount,
                    ],
                ];
            });

        // Gabungkan data pendapatan dan pengeluaran
        $revenueAndExpenseData = $orderData->mergeRecursive($expenseData);

        // Buat data default untuk 13 bulan terakhir
        $revenueAndExpenseLast13M = collect(range(0, 12))
            ->mapWithKeys(function ($i) use ($revenueAndExpenseData) {
                $currentDate = now()->subMonths($i);
                $monthKey = $currentDate->format('Y-m');
                $data = $revenueAndExpenseData[$monthKey] ?? [
                    'total_orders' => 0,
                    'total_revenue' => 0,
                    'total_expenses' => 0,
                    'total_expense_amount' => 0,
                ];

                return [
                    $monthKey => array_merge(['month_name' => $currentDate->format('M Y')], $data),
                ];
            })
            ->reverse()
            ->values()
            ->toArray();

        // Data harian bulan ini
        $dailyData = Order::where('outlet_id', $outlet->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total_orders, SUM(total) as total_revenue')
            ->groupBy('date')
            ->get()
            ->mapWithKeys(function ($order) {
                return [
                    $order->date => [
                        'total_orders' => $order->total_orders,
                        'total_revenue' => $order->total_revenue,
                    ],
                ];
            });

        $expenseDaily = Expense::where('outlet_id', $outlet->id)
            ->where('status', 'submitted')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->with('items')
            ->get()
            ->groupBy(function ($expense) {
                return $expense->created_at->toDateString();
            })
            ->mapWithKeys(function ($expenses, $key) {
                $totalExpenseAmount = $expenses->flatMap->items->sum(function ($item) {
                    return $item->quantity * $item->price;
                });

                return [
                    $key => [
                        'total_expenses' => $expenses->count(),
                        'total_expense_amount' => $totalExpenseAmount,
                    ],
                ];
            });

        // Data default harian untuk bulan ini
        $daysInMonth = now()->daysInMonth;
        $orderDailyCurrentMonth = collect(range(1, $daysInMonth))->mapWithKeys(function ($day) use ($dailyData) {
            $date = now()
                ->startOfMonth()
                ->addDays($day - 1)
                ->toDateString();
            return [$date => $dailyData[$date] ?? ['total_orders' => 0, 'total_revenue' => 0]];
        });

        $expenseDailyCurrentMonth = collect(range(1, $daysInMonth))->mapWithKeys(function ($day) use ($expenseDaily) {
            $date = now()
                ->startOfMonth()
                ->addDays($day - 1)
                ->toDateString();
            return [$date => $expenseDaily[$date] ?? ['total_expenses' => 0, 'total_expense_amount' => 0]];
        });

        // Data untuk hari ini
        $todayRevenue = $dailyData[now()->toDateString()]['total_revenue'] ?? 0;
        $todayExpense = $expenseDaily[now()->toDateString()]['total_expense_amount'] ?? 0;

        return view('dashboard.index', compact('revenueAndExpenseLast13M', 'expenseDailyCurrentMonth', 'orderDailyCurrentMonth', 'todayRevenue', 'todayExpense', 'outlet'));
    }

    // public function index(Outlet $outlet)
    // {

    //     // Ambil rentang tanggal 13 bulan terakhir
    //     $startOfPeriod = now()->subMonths(12)->startOfMonth(); // 12 bulan ke belakang + bulan ini = 13 bulan
    //     $endOfPeriod = now()->endOfMonth();

    //     // Ambil data pendapatan (Revenue)
    //     $revenueData = Order::where('outlet_id', $outlet->id)
    //         ->where('status', 'completed')
    //         ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
    //         ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total_orders, SUM(total) as total_revenue')
    //         ->groupBy('year', 'month')
    //         ->orderBy('year')
    //         ->orderBy('month')
    //         ->get()
    //         ->mapWithKeys(function ($order) {
    //             return [$order->year . '-' . str_pad($order->month, 2, '0', STR_PAD_LEFT) => [
    //                 'total_orders' => $order->total_orders,
    //                 'total_revenue' => $order->total_revenue,
    //             ]];
    //         })
    //         ->toArray();

    //     // Ambil data pengeluaran (Expense)
    //     $expenseData = Expense::where('outlet_id', $outlet->id)
    //         ->where('status', 'submitted')
    //         ->whereBetween('created_at', [$startOfPeriod, $endOfPeriod])
    //         ->with('items')
    //         ->get()
    //         ->groupBy(function ($expense) {
    //             return $expense->created_at->format('Y-m'); // Gabungan tahun dan bulan
    //         })
    //         ->mapWithKeys(function ($expenses, $key) {
    //             $totalExpenses = $expenses->count();
    //             $totalExpenseAmount = $expenses->flatMap->items->sum(function ($item) {
    //                 return $item->quantity * $item->price;
    //             });

    //             return [
    //                 $key => [
    //                     'total_expenses' => $totalExpenses,
    //                     'total_expense_amount' => $totalExpenseAmount,
    //                 ],
    //             ];
    //         })
    //         ->toArray();

    //     // Gabungkan data pendapatan (Revenue) dan pengeluaran (Expense)
    //     $revenueAndExpenseData = collect($revenueData)->mergeRecursive($expenseData)->toArray();

    //     // Buat data default untuk 13 bulan terakhir dengan nama bulan jika ada data
    //     $revenueAndExpenseLast13M = collect(range(0, 12))->mapWithKeys(function ($i) use ($revenueData, $expenseData) {
    //         $currentDate = now()->subMonths($i);
    //         $monthKey = $currentDate->format('Y-m');
    //         $revenueSummary = $revenueData[$monthKey] ?? null;
    //         $expenseSummary = $expenseData[$monthKey] ?? null;

    //         if ($revenueSummary || $expenseSummary) {
    //             return [
    //                 $monthKey => [
    //                     'month_name' => $currentDate->format('M Y'),
    //                     'total_orders' => $revenueSummary['total_orders'] ?? 0,
    //                     'total_revenue' => $revenueSummary['total_revenue'] ?? 0,
    //                     'total_expenses' => $expenseSummary['total_expenses'] ?? 0,
    //                     'total_expense_amount' => $expenseSummary['total_expense_amount'] ?? 0,
    //                 ],
    //             ];
    //         }

    //         return [];
    //     })->reverse()->values()->toArray();

    //     // Tambahkan bulan berikutnya dengan data 0 jika array kurang dari 13 bulan
    //     if (count($revenueAndExpenseLast13M) < 13) {
    //         $missingMonths = 13 - count($revenueAndExpenseLast13M);
    //         $lastMonth = now()->startOfMonth()->subMonths(count($revenueAndExpenseLast13M) - 1);
    //         if (!empty($revenueAndExpenseLast13M)) {
    //             $lastMonth = Carbon::createFromFormat('M Y', end($revenueAndExpenseLast13M)['month_name'])->startOfMonth();
    //         }
    //         for ($i = 0; $i < $missingMonths; $i++) {
    //             $lastMonth = $lastMonth->addMonth();
    //             $revenueAndExpenseLast13M[] = [
    //                 'month_name' => $lastMonth->format('M Y'),
    //                 'total_orders' => 0,
    //                 'total_revenue' => 0,
    //                 'total_expenses' => 0,
    //                 'total_expense_amount' => 0,
    //             ];
    //         }
    //     }

    //     $orderDailyCurrentMonth = collect(range(1, now()->daysInMonth))->mapWithKeys(function ($day) use ($outlet) {
    //         $date = now()->startOfMonth()->addDays($day - 1)->format('j M Y');
    //         $order = Order::where('outlet_id', $outlet->id)
    //             ->where('status', 'completed')
    //             ->whereDate('created_at', now()->startOfMonth()->addDays($day - 1)->toDateString())
    //             ->selectRaw('COUNT(*) as total_orders, SUM(total) as total_revenue')
    //             ->first();

    //         return [$date => [
    //             'total_orders' => $order->total_orders ?? 0,
    //             'total_revenue' => $order->total_revenue ?? 0,
    //         ]];
    //     })->toArray();

    //     $expenseDailyCurrentMonth = collect(range(1, now()->daysInMonth))->mapWithKeys(function ($day) use ($outlet) {
    //         $date = now()->startOfMonth()->addDays($day - 1)->format('j M Y');
    //         $expenses = Expense::where('outlet_id', $outlet->id)
    //             ->where('status', 'submitted')
    //             ->whereDate('created_at', now()->startOfMonth()->addDays($day - 1)->toDateString())
    //             ->with('items')
    //             ->get();

    //         $totalExpenses = $expenses->count();
    //         $totalExpenseAmount = $expenses->sum(function ($expense) {
    //             return $expense->items->sum(function ($item) {
    //                 return $item->quantity * $item->price;
    //             });
    //         });

    //         return [$date => [
    //             'total_expenses' => $totalExpenses,
    //             'total_expense_amount' => $totalExpenseAmount,
    //         ]];
    //     })->toArray();


    //     $todayRevenue = Order::where('outlet_id', $outlet->id)
    //         ->where('status', 'completed')
    //         ->whereDate('created_at', now()->toDateString())
    //         ->sum('total');

    //     $todayExpense = Expense::where('outlet_id', $outlet->id)
    //         ->where('status', 'submitted')
    //         ->whereDate('created_at', now()->toDateString())
    //         ->with('items')
    //         ->get()
    //         ->sum(function ($expense) {
    //             return $expense->items->sum(function ($item) {
    //                 return $item->quantity * $item->price;
    //             });
    //         });

    //     return view('dashboard.index', compact('revenueAndExpenseLast13M', 'expenseDailyCurrentMonth', 'orderDailyCurrentMonth', 'todayRevenue', 'todayExpense', 'outlet'));
    // }
}
