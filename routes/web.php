<?php

use App\Http\Controllers\Categories;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemArchivingController;
use App\Http\Controllers\AccountablePersonController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/register', [AuthController::class, 'register'])->name('register.store');
    Route::post('/register', [AuthController::class, 'store'])->name('store');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'searchBar'])->name('dashboard.index');

    Route::middleware(['role:admin|staff'])->group(function () {
        Route::get('/inventory/items', [InventoryController::class, 'InventoryItems'])->name('inventory.items');
        Route::get('/inventory/transactions', [InventoryController::class, 'InventoryTransactions'])->name('inventory.transactions');
        Route::get('/inventory/acknowledgements', [InventoryController::class, 'InventoryAcknowledgements'])->name('inventory.acknowledgements');
        Route::post('/inventory/acknowledgements/store', [InventoryController::class, 'InventoryAcknowledgementsStore'])->name('inventory.acknowledgements.store');
        Route::get('/export-csv', [InventoryController::class, 'exportCsv']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::put('/inventory/items/update-category', [InventoryController::class, 'updateCategoryForItems'])->name('inventory.items.update-category');
        Route::post('/items/store', [InventoryController::class, 'store'])->name('items.store');
        Route::put('/items/{id}', [InventoryController::class, 'update'])->name('items.update');
        Route::delete('/items/{id}', [InventoryController::class, 'destroy'])->name('items.destroy');
        Route::post('/convert-excel-to-csv', [InventoryController::class, 'convert']);
        Route::post('/import-csv', [InventoryController::class, 'importCsv']);
    });

    Route::middleware(['role:admin|staff'])->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'suppliers'])->name('suppliers.index');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

    Route::middleware(['role:admin|staff'])->group(function () {
        Route::get('/accountable-person', [AccountablePersonController::class, 'accountablePerson'])->name('accountable.index');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::post('/accountable-person', [AccountablePersonController::class, 'store'])->name('accountable.store');
        Route::put('/accountable-person{id}', [AccountablePersonController::class, 'update'])->name('accountable.update');
        Route::delete('/accountable-person{id}', [AccountablePersonController::class, 'destroy'])->name('accountable.destroy');
    });

    Route::middleware(['role:admin|staff'])->group(function () {
        Route::get('/categories', [Categories::class, 'categories'])->name('categories.index');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::post('/categories', [Categories::class, 'store'])->name('categories.store');
        Route::put('/categories{id}', [Categories::class, 'update'])->name('categories.update');
        Route::delete('/categories{id}', [Categories::class, 'destroy'])->name('categories.destroy');
    });

    Route::middleware(['role:admin|staff'])->group(function () {
        Route::get('/report', [ReportController::class, 'searchBar'])->name('reports.index');
        Route::get('item_archiving', [ItemArchivingController::class, 'index'])->name('item_archiving.index');
        Route::post('/print/receipt', [PrintController::class, 'printReceipt'])->name('print.receipt');
    });
});
