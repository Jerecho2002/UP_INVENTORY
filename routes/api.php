<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UPSSOController;

Route::prefix('v1')
    ->as('api.v1.')
    ->group(function () {
        Route::post('/auth/up/sso', [UPSSOController::class, 'ssoRedirect']);
    });
