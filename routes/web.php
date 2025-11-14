<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::middleware(['auth', 'role:staff,admin'])->group(function () {
    Route::get('/inventory/items', [InventoryController::class, 'InventoryItems'])->name('inventory.items');
    Route::get('/inventory/transactions', [InventoryController::class, 'InventoryTransactions'])->name('inventory.transactions');
    Route::get('/inventory/acknowledgements', [InventoryController::class, 'InventoryAcknowledgements'])->name('inventory.acknowledgements');
    Route::post('/items/store', [InventoryController::class, 'store'])->name('items.store');
    Route::put('/items/{id}', [InventoryController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [InventoryController::class, 'destroy'])->name('items.destroy');
    Route::get('/dashboard', [DashboardController::class, 'searchBar'])->name('dashboard.index');
    Route::get('/report', [ReportController::class, 'searchBar'])->name('reports.index');
});

Route::get('/suppliers', function () {
    return inertia("Suppliers");
})->name("suppliers.index");

Route::get('/purchase', function () {
    return inertia("Purchase");
})->name("purchase.index");

Route::get('/item_disposal', function () {
    return inertia("ItemDisposal");
})->name("item_disposal.index");


Route::get('/dummy-auth', function () {
    $user_id = 11;

    // Log in user with ID 1
    Auth::loginUsingId($user_id);

    return "User logged in {$user_id}";
});


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';
