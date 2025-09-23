<?php

use App\Http\Controllers\InventoryController;
use App\Models\Item;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::middleware(['auth', 'role:Taper'])->group(function () {
    Route::get('/', [InventoryController::class, 'searchBar']);
    Route::delete('/items/{id}', [InventoryController::class, 'destroy'])->name('items.destroy');
});
Route::get('/dummy-auth', function () {
    // Log in user with ID 1
    Auth::loginUsingId(5);

    return 'User logged in as ID 4';
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
