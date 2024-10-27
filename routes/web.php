<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserRole;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\MenuManagementController;
use App\Http\Controllers\dashboard\UnitManagementController;
use App\Http\Controllers\dashboard\superadmin\OutletManagementController;
use App\Http\Controllers\dashboard\StockItemManagementController;

Auth::routes();

Route::middleware(['auth', 'set_outlet_role'])->group(function () {
  Route::get('/', [HomeController::class, 'index'])->name('home');
  Route::get('demo/{any}', [HomeController::class, 'root'])->where('any', '.*');

  // Superadmin
  Route::group(['middleware' => ['role:superadmin']], function () {
    // outlet management
    Route::resource('outlet', OutletManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('outlet/{id}/fetch', [OutletManagementController::class, 'fetch'])->name('outlet.fetch');
    Route::get('outlet/{outlet:slug}', [DashboardController::class, 'index'])->name('outlet.dashboard');

    Route::prefix('outlet/{outlet:slug}')->name('outlet.')->group(function () {
      Route::resource('unit', UnitManagementController::class)->only(['index', 'store', 'update', 'destroy']);

      // stock item management
      Route::resource('stock-item', StockItemManagementController::class);
      Route::get('stock-item/{id}/fetch', [StockItemManagementController::class, 'fetch'])->name('stock-item.fetch');
      Route::put('stock-item/{id}/restock', [StockItemManagementController::class, 'restock'])->name('stock-item.restock');

      // menu management
      Route::resource('menu', MenuManagementController::class);
    });
  });

  // Admin
  Route::group(['middleware' => ['role:admin']], function () {
    Route::name('admin.')->group(function () {
      Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

      // stock item management
      Route::resource('stock-item', StockItemManagementController::class);
      Route::get('stock-item/{id}/fetch', [StockItemManagementController::class, 'fetch'])->name('stock-item.fetch');
      Route::put('stock-item/{id}/restock', [StockItemManagementController::class, 'restock'])->name('stock-item.restock');

      // menu management
      Route::resource('menu', MenuManagementController::class);
    });
  });

  // Superadmin & Admin
  Route::group(['middleware' => ['role:superadmin|admin']], function () {
    // unit management
    Route::resource('unit', UnitManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('unit/{id}/fetch', [UnitManagementController::class, 'fetch'])->name('unit.fetch');
  });
});
