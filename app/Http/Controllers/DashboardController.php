<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function searchBar(Request $request, DashboardService $service){
        $search = $request->input('search');
        return Inertia::render('Dashboard', [
        'items' => $service->getPaginatedInventory($search),
        'searchItem' => $search,
    ]);
    }
}
