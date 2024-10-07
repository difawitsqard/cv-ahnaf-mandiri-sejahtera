<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OutletManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware(['auth'])->group(function () {
  Route::get('/', [HomeController::class, 'index'])->name('home');
  Route::get('demo/{any}', [HomeController::class, 'root'])->where('any', '.*');

  // Superadmin
  Route::group(['middleware' => ['role:superadmin']], function () {
    Route::resource('outlet', OutletManagementController::class);
    // Route::prefix('dashboard')->name('dashboard.')->group(function () {
    //   Route::resource('outlet-management', OutletManagementController::class)->only(['index', 'show']);
    // });
  });
});
