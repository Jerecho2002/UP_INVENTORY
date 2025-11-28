<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemArchivingController;

Route::middleware(['auth', 'role:staff,admin'])->group(function () {
    Route::get('/inventory/items', [InventoryController::class, 'InventoryItems'])->name('inventory.items');
    Route::get('/inventory/transactions', [InventoryController::class, 'InventoryTransactions'])->name('inventory.transactions');
    Route::get('/inventory/acknowledgements', [InventoryController::class, 'InventoryAcknowledgements'])->name('inventory.acknowledgements');
    Route::post('/inventory/acknowledgements/store', [InventoryController::class, 'InventoryAcknowledgementsStore'])->name('inventory.acknowledgements.store');
    Route::post('/items/store', [InventoryController::class, 'store'])->name('items.store');
    Route::put('/items/{id}', [InventoryController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [InventoryController::class, 'destroy'])->name('items.destroy');
    Route::get('/dashboard', [DashboardController::class, 'searchBar'])->name('dashboard.index');
    Route::get('/report', [ReportController::class, 'searchBar'])->name('reports.index');
    Route::get('/suppliers', [SupplierController::class, 'suppliers'])->name('suppliers.index');
    Route::get('item_archiving', [ItemArchivingController::class, 'index'])->name('item_archiving.index');
    Route::post('/convert-excel-to-csv', [InventoryController::class, 'convert']);
    Route::post('/import-csv', [InventoryController::class, 'importCsv']);
    Route::get('/export-csv', [InventoryController::class, 'exportCsv']);
});

Route::get('/dummy-auth', function () {
    // Temporary Authentication
    $user_id = 11;
    Auth::loginUsingId($user_id);

    return "User logged in {$user_id}";
});

