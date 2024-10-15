<?php

use App\Http\Controllers\dashboard\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\dashboard\superadmin\OutletManagementController;
use App\Http\Controllers\dashboard\superadmin\StockItemManagementController;
use App\Http\Controllers\dashboard\UnitManagementController;

Auth::routes();

Route::middleware(['auth'])->group(function () {
  Route::get('/', [HomeController::class, 'index'])->name('home');
  Route::get('demo/{any}', [HomeController::class, 'root'])->where('any', '.*');

  // Superadmin
  Route::group(['middleware' => ['role:superadmin']], function () {

    Route::resource('unit', UnitManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('unit/{id}/fetch', [UnitManagementController::class, 'fetch'])->name('unit.fetch');

    Route::resource('outlet', OutletManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('outlet/{id}/fetch', [OutletManagementController::class, 'fetch'])->name('outlet.fetch');
    Route::get('outlet/{outlet:slug}', [DashboardController::class, 'index'])->name('outlet.dashboard');
    Route::prefix('outlet/{outlet:slug}')->name('outlet.')->group(function () {
      Route::resource('stock-item', StockItemManagementController::class);
      Route::get('stock-item/{id}/fetch', [StockItemManagementController::class, 'fetch'])->name('stock-item.fetch');
      Route::put('stock-item/{id}/restock', [StockItemManagementController::class, 'restock'])->name('stock-item.restock');
    });
    // Route::prefix('dashboard')->name('dashboard.')->group(function () {
    //   Route::resource('outlet-management', OutletManagementController::class)->only(['index', 'show']);
    // });
  });

  // Admin
  Route::group(['middleware' => ['role:admin']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
  });
});
