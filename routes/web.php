<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\dashboard\superadmin\OutletManagementController;
use App\Http\Controllers\dashboard\superadmin\StockItemManagementController;

Auth::routes();

Route::middleware(['auth'])->group(function () {
  Route::get('/', [HomeController::class, 'index'])->name('home');
  Route::get('demo/{any}', [HomeController::class, 'root'])->where('any', '.*');

  // Superadmin
  Route::group(['middleware' => ['role:superadmin']], function () {
    Route::prefix('outlet/{outlet}')->group(function () {
      Route::resource('stock-item-management', StockItemManagementController::class);
    });

    Route::resource('outlet', OutletManagementController::class);
    // Route::prefix('dashboard')->name('dashboard.')->group(function () {
    //   Route::resource('outlet-management', OutletManagementController::class)->only(['index', 'show']);
    // });
  });
});
