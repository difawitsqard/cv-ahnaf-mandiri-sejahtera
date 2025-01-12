<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Controllers\dashboard\OrderController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\MenuManagementController;
use App\Http\Controllers\dashboard\UnitManagementController;
use App\Http\Controllers\dashboard\UserManagementController;
use App\Http\Controllers\dashboard\ExpenseManagementController;
use App\Http\Controllers\dashboard\StockItemManagementController;
use App\Http\Controllers\dashboard\superadmin\CompanyInfoController;
use App\Http\Controllers\dashboard\superadmin\OutletManagementController;

Auth::routes(['verify' => true, 'register' => false]);

Route::middleware(['auth', 'verified', 'check_password_set', 'set_outlet_role'])->group(function () {
  Route::get('/set-password', [SetPasswordController::class, 'setPasswordForm'])->name('set-password');
  Route::post('/set-password', [SetPasswordController::class, 'setPassword'])->name('set-password.set');

  Route::get('/', [HomeController::class, 'index'])->name('home');
  Route::get('demo/{any}', [HomeController::class, 'root'])->where('any', '.*');

  // Superadmin
  Route::group(['middleware' => ['check_role:superadmin']], function () {
    // outlet management
    Route::resource('outlet', OutletManagementController::class)->only(['index', 'store', 'update']);
    Route::delete('outlet/{slug}', [OutletManagementController::class, 'destroy'])->name('outlet.destroy');
    Route::get('outlet/{id}/fetch', [OutletManagementController::class, 'fetch'])->name('outlet.fetch');
    Route::get('outlet/{outlet:slug}', [DashboardController::class, 'index'])->name('outlet.dashboard');

    Route::prefix('outlet/{outlet:slug}')
      ->name('outlet.')
      ->group(function () {
        Route::put('company-info/create_or_update', [CompanyInfoController::class, 'CreateOrUpdate'])->name('company-info.create_or_update');
        Route::resource('company-info', CompanyInfoController::class)->only(['index']);

        Route::resource('unit', UnitManagementController::class)->only(['index', 'store', 'update', 'destroy']);

        // stock item management
        Route::resource('stock-item', StockItemManagementController::class);
        Route::get('stock-item/{id}/fetch', [StockItemManagementController::class, 'fetch'])->name('stock-item.fetch');
        Route::put('stock-item/{id}/restock', [StockItemManagementController::class, 'restock'])->name('stock-item.restock');

        Route::post('order/export', [OrderController::class, 'export'])->name('order.export');
        Route::resource('order', OrderController::class);
        Route::get('order/{order}/print', [OrderController::class, 'printThermal'])->name('order.print');

        // expense management
        Route::get('expense/fetchAll', [ExpenseManagementController::class, 'fetchAll'])->name('expense.fetchAll');
        Route::get('expense/{id}/fetch', [ExpenseManagementController::class, 'fetch'])->name('expense.fetch');
        Route::put('expense/{id}/cancel', [ExpenseManagementController::class, 'cancel'])->name('expense.cancel');
        Route::post('expense/export', [ExpenseManagementController::class, 'export'])->name('expense.export');
        Route::resource('expense', ExpenseManagementController::class)->except(['destroy']);

        // user management
        Route::resource('user', UserManagementController::class);

        // menu management
        Route::resource('menu', MenuManagementController::class);
      });

    Route::put('company-info/create_or_update', [CompanyInfoController::class, 'CreateOrUpdate'])->name('company-info.create_or_update');
    Route::resource('company-info', CompanyInfoController::class)->only(['index']);
  });

  // Admin
  Route::group(['middleware' => ['check_role:admin']], function () {
    Route::prefix('admin')
      ->name('admin.')
      ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('order/export', [OrderController::class, 'export'])->name('order.export');
        Route::resource('order', OrderController::class);
        Route::get('order/{order}/print', [OrderController::class, 'printThermal'])->name('order.print');

        // stock item management
        Route::resource('stock-item', StockItemManagementController::class);
        Route::get('stock-item/{id}/fetch', [StockItemManagementController::class, 'fetch'])->name('stock-item.fetch');
        Route::put('stock-item/{id}/restock', [StockItemManagementController::class, 'restock'])->name('stock-item.restock');

        // expense management
        Route::get('expense/fetchAll', [ExpenseManagementController::class, 'fetchAll'])->name('expense.fetchAll');
        Route::get('expense/{id}/fetch', [ExpenseManagementController::class, 'fetch'])->name('expense.fetch');
        Route::put('expense/{id}/cancel', [ExpenseManagementController::class, 'cancel'])->name('expense.cancel');
        Route::post('expense/export', [ExpenseManagementController::class, 'export'])->name('expense.export');
        Route::resource('expense', ExpenseManagementController::class)->except(['destroy']);

        // user management
        Route::resource('user', UserManagementController::class);

        // menu management
        Route::resource('menu', MenuManagementController::class);
      });
  });

  // Superadmin & Admin
  Route::group(['middleware' => ['check_role:superadmin|admin']], function () {
    // unit management
    Route::resource('unit', UnitManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('unit/{id}/fetch', [UnitManagementController::class, 'fetch'])->name('unit.fetch');
  });

  // Staff
  Route::group(['middleware' => ['check_role:staff']], function () {
    Route::prefix('staff')
      ->name('staff.')
      ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('order', OrderController::class);
        Route::get('order/{order}/print', [OrderController::class, 'printThermal'])->name('order.print');
      });
  });
});
