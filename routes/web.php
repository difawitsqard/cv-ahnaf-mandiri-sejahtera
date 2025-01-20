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
use App\Http\Controllers\dashboard\AccountSettingsController;
use App\Http\Controllers\dashboard\OutletManagementController;
use App\Http\Controllers\dashboard\ExpenseManagementController;
use App\Http\Controllers\dashboard\StockItemManagementController;
use App\Http\Controllers\dashboard\superadmin\CompanyInfoController;
use App\Http\Controllers\dashboard\StockItemCategoryManagementController;

Auth::routes(['verify' => true, 'register' => false]);

Route::middleware(['auth', 'check_password_set', 'set_outlet_role'])->group(function () {

  Route::get('/email/change', [VerificationController::class, 'showChangeEmailForm'])->name('email.change');
  Route::post('/email/change', [VerificationController::class, 'changeEmail'])->name('email.change.update');

  Route::middleware(['verified'])->group(function () {

    Route::get('/set-password', [SetPasswordController::class, 'setPasswordForm'])->name('set-password');
    Route::post('/set-password', [SetPasswordController::class, 'setPassword'])->name('set-password.set');

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('demo/{any}', [HomeController::class, 'root'])->where('any', '.*');

    Route::put('/account-update', [AccountSettingsController::class, 'accountUpdate'])->name('account-update');
    Route::put('/change-password', [AccountSettingsController::class, 'changePassword'])->name('change-password');

    // Superadmin & Admin
    Route::group(['middleware' => ['role:superadmin|admin']], function () {
      // stock item category management
      route::resource('stock-item-category', StockItemCategoryManagementController::class)->only(['store', 'update', 'destroy']);
      route::get('stock-item-category/{id}/fetch', [StockItemCategoryManagementController::class, 'fetch'])->name('stock-item-category.fetch');

      // unit management
      Route::resource('unit', UnitManagementController::class)->only(['store', 'update', 'destroy']);
      Route::get('unit/{id}/fetch', [UnitManagementController::class, 'fetch'])->name('unit.fetch');
    });

    // Superadmin
    Route::group(['middleware' => ['check_role:superadmin']], function () {
      // outlet management
      Route::resource('outlet', OutletManagementController::class)->only(['index', 'store', 'update', 'destroy']);
      Route::get('outlet/{id}/fetch', [OutletManagementController::class, 'fetch'])->name('outlet.fetch');
      Route::get('outlet/{outlet:slug}', [DashboardController::class, 'index'])->name('outlet.dashboard');
      Route::put('company-info/create-or-update', [CompanyInfoController::class, 'CreateOrUpdate'])->name('company-info.create-or-update');

      Route::prefix('outlet/{outlet:slug}')
        ->name('outlet.')
        ->group(function () {
          route::resource('stock-item-category', StockItemCategoryManagementController::class)->only(['index']);
          Route::resource('unit', UnitManagementController::class)->only(['index']);

          Route::resource('account-settings', AccountSettingsController::class)->only(['index']);

          // stock item management
          Route::resource('stock-item', StockItemManagementController::class);
          Route::get('stock-item/{id}/fetch', [StockItemManagementController::class, 'fetch'])->name('stock-item.fetch');
          Route::put('stock-item/{id}/restock', [StockItemManagementController::class, 'restock'])->name('stock-item.restock');

          Route::put('order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
          Route::post('order/export', [OrderController::class, 'export'])->name('order.export');
          Route::resource('order', OrderController::class);
          Route::get('order/{order}/print', [OrderController::class, 'printThermal'])->name('order.print');

          // expense management
          Route::get('expense/{id}/fetch', [ExpenseManagementController::class, 'fetch'])->name('expense.fetch');
          Route::put('expense/{id}/cancel', [ExpenseManagementController::class, 'cancel'])->name('expense.cancel');
          Route::post('expense/export', [ExpenseManagementController::class, 'export'])->name('expense.export');
          Route::resource('expense', ExpenseManagementController::class)->except(['destroy']);

          // user management
          Route::resource('user', UserManagementController::class);
          Route::get('user/{id}/fetch', [UserManagementController::class, 'fetch'])->name('user.fetch');
          Route::put('user/disabled-enabled/{user}', [UserManagementController::class, 'disabledOrEnable'])->name('user.disabled-enabled');
          Route::put('user/{id}/resend-verification', [UserManagementController::class, 'resend'])->name('user.resend-verification');

          // menu management
          Route::resource('menu', MenuManagementController::class);

          Route::get('outlet-settings', [OutletManagementController::class, 'edit'])->name('outlet.edit');
        });
    });

    // Admin
    Route::group(['middleware' => ['check_role:admin']], function () {
      Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
          Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

          Route::resource('account-settings', AccountSettingsController::class)->only(['index']);

          route::resource('stock-item-category', StockItemCategoryManagementController::class)->only(['index']);
          Route::resource('unit', UnitManagementController::class)->only(['index']);

          Route::post('order/export', [OrderController::class, 'export'])->name('order.export');
          Route::put('order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
          Route::resource('order', OrderController::class);
          Route::get('order/{order}/print', [OrderController::class, 'printThermal'])->name('order.print');

          // stock item management
          Route::resource('stock-item', StockItemManagementController::class);
          Route::get('stock-item/{id}/fetch', [StockItemManagementController::class, 'fetch'])->name('stock-item.fetch');
          Route::put('stock-item/{id}/restock', [StockItemManagementController::class, 'restock'])->name('stock-item.restock');

          // expense management
          Route::get('expense/{id}/fetch', [ExpenseManagementController::class, 'fetch'])->name('expense.fetch');
          Route::put('expense/{id}/cancel', [ExpenseManagementController::class, 'cancel'])->name('expense.cancel');
          Route::post('expense/export', [ExpenseManagementController::class, 'export'])->name('expense.export');
          Route::resource('expense', ExpenseManagementController::class)->except(['destroy']);

          // user management
          Route::resource('user', UserManagementController::class);
          Route::get('user/{id}/fetch', [UserManagementController::class, 'fetch'])->name('user.fetch');
          Route::put('user/disabled-enabled/{user}', [UserManagementController::class, 'disabledOrEnable'])->name('user.disabled-enabled');
          Route::put('user/{id}/resend-verification', [UserManagementController::class, 'resend'])->name('user.resend-verification');

          // menu management
          Route::resource('menu', MenuManagementController::class);

          Route::get('outlet-settings', [OutletManagementController::class, 'edit'])->name('outlet.edit');
          Route::put('outlet-settings', [OutletManagementController::class, 'update'])->name('outlet.update');
        });
    });


    // Staff
    Route::group(['middleware' => ['check_role:staff']], function () {
      Route::prefix('staff')
        ->name('staff.')
        ->group(function () {
          Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

          Route::resource('account-settings', AccountSettingsController::class)->only(['index']);

          // order staff
          Route::post('order/export', [OrderController::class, 'export'])->name('order.export');
          Route::put('order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
          Route::resource('order', OrderController::class);
          Route::get('order/{order}/print', [OrderController::class, 'printThermal'])->name('order.print');

          // expense management
          Route::get('expense/{id}/fetch', [ExpenseManagementController::class, 'fetch'])->name('expense.fetch');
          Route::put('expense/{id}/cancel', [ExpenseManagementController::class, 'cancel'])->name('expense.cancel');
          Route::post('expense/export', [ExpenseManagementController::class, 'export'])->name('expense.export');
          Route::resource('expense', ExpenseManagementController::class)->except(['destroy']);
        });
    });
  });
});
