<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\InventoryApiController;

Route::get('/inventoryitems', [InventoryApiController::class, 'index']);