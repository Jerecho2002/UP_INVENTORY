<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\InventoryController;

Route::get('/inventoryitems', [InventoryController::class, 'index']);