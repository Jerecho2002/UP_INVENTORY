<?php

use App\Http\Controllers\Categories;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemArchivingController;
use App\Http\Controllers\TransactionPrintController;
use App\Http\Controllers\AccountablePersonController;

Route::middleware(['auth', 'role:staff,admin'])->group(function () {
    //Dashboard
     Route::get('/dashboard', [DashboardController::class, 'searchBar'])->name('dashboard.index');

    //Inventory
    Route::get('/inventory/items', [InventoryController::class, 'InventoryItems'])->name('inventory.items');
    Route::get('/inventory/transactions', [InventoryController::class, 'InventoryTransactions'])->name('inventory.transactions');
    Route::get('/inventory/acknowledgements', [InventoryController::class, 'InventoryAcknowledgements'])->name('inventory.acknowledgements');
    Route::post('/inventory/acknowledgements/store', [InventoryController::class, 'InventoryAcknowledgementsStore'])->name('inventory.acknowledgements.store');
    Route::put('/inventory/items/update-category', [InventoryController::class, 'updateCategoryForItems'])
        ->name('inventory.items.update-category');
    Route::post('/items/store', [InventoryController::class, 'store'])->name('items.store');
    Route::put('/items/{id}', [InventoryController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [InventoryController::class, 'destroy'])->name('items.destroy');
    Route::post('/convert-excel-to-csv', [InventoryController::class, 'convert']);
    Route::post('/import-csv', [InventoryController::class, 'importCsv']);
    Route::get('/export-csv', [InventoryController::class, 'exportCsv']);

    //Suppliers
    Route::get('/suppliers', [SupplierController::class, 'suppliers'])->name('suppliers.index');
    Route::post('/suppliers', action: [SupplierController::class, 'store'])->name('suppliers.store');
   
    //Accountable Person
    Route::get('/accountable-person', [AccountablePersonController::class, 'accountablePerson'])->name('accountable.index');
    Route::post('/accountable-person', action: [AccountablePersonController::class, 'store'])->name('accountable.store');

    //Categories
    Route::get('/categories', [Categories::class, 'categories'])->name('categories.index');
    Route::post('/categories', [Categories::class, 'store'])->name('categories.store');
    
    //Reports
    Route::get('/report', [ReportController::class, 'searchBar'])->name('reports.index');
    
    //Item Archiving
    Route::get('item_archiving', [ItemArchivingController::class, 'index'])->name('item_archiving.index');
    
    //Printing
    Route::post('/print/receipt', [PrintController::class, 'printReceipt']);
});

Route::get('/dummy-auth', function () {
    // Temporary Authentication
    $user_id = 11;
    Auth::loginUsingId($user_id);


    return "User logged in {$user_id}";
});

